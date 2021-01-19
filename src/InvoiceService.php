<?php declare(strict_types=1);

namespace hollodotme\FastCGI\ClientDemo;

use Closure;
use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\ClientDemo\Responses\EventSourceStream;
use hollodotme\FastCGI\Exceptions\ConnectException;
use hollodotme\FastCGI\Exceptions\ReadFailedException;
use hollodotme\FastCGI\Exceptions\TimedoutException;
use hollodotme\FastCGI\Exceptions\WriteFailedException;
use hollodotme\FastCGI\Interfaces\ConfiguresSocketConnection;
use hollodotme\FastCGI\Interfaces\ProvidesResponseData;
use hollodotme\FastCGI\RequestContents\UrlEncodedFormData;
use hollodotme\FastCGI\Requests\PostRequest;
use Throwable;

final class InvoiceService
{
	private const WORKER_SCRIPT = '/repo/bin/createInvoice.php';

	private Client $client;

	public function __construct(
		private ConfiguresSocketConnection $connection,
		private EventSourceStream $responseStream
	)
	{
		$this->client = new Client();
	}

	/**
	 * @throws ConnectException
	 * @throws Exceptions\LogicException
	 * @throws ReadFailedException
	 * @throws TimedoutException
	 * @throws WriteFailedException
	 */
	public function single() : void
	{
		$startTime = microtime( true );
		$this->responseStream->beginStream();

		$requestId = $this->sendAsyncRequests( 1 )[0];

		$this->responseStream->streamEvent(
			sprintf(
				'Sent single request with ID: %s',
				$requestId
			)
		);
		$this->responseStream->streamEvent( '' );

		$this->client->handleResponse( $requestId );

		$this->responseStream->streamEvent( '' );
		$this->responseStream->streamEvent(
			'Duration: ' . round( microtime( true ) - $startTime, 4 ) . ' Seconds'
		);

		$this->responseStream->endStream();
	}

	/**
	 * @throws ConnectException
	 * @throws Exceptions\LogicException
	 * @throws ReadFailedException
	 * @throws TimedoutException
	 * @throws WriteFailedException
	 */
	public function multipleOrdered() : void
	{
		$startTime = microtime( true );
		$this->responseStream->beginStream();

		$requestIds = $this->sendAsyncRequests( 100 );

		$this->responseStream->streamEvent(
			sprintf( 'Sent %d requests', count( $requestIds ) )
		);
		$this->responseStream->streamEvent( '' );

		$this->client->handleResponses( 5000, ...$requestIds );

		$this->responseStream->streamEvent( '' );
		$this->responseStream->streamEvent(
			'Duration: ' . round( microtime( true ) - $startTime, 4 ) . ' Seconds'
		);

		$this->responseStream->endStream();
	}

	/**
	 * @param int $amount
	 *
	 * @return array
	 * @throws TimedoutException
	 * @throws WriteFailedException
	 * @throws ConnectException
	 */
	private function sendAsyncRequests( int $amount ) : array
	{
		$requestIds = [];

		for ( $i = 0; $i < $amount; $i++ )
		{
			$documentId = sprintf( '42%06d', $i );
			$content    = new UrlEncodedFormData( ['documentId' => $documentId] );

			$request = PostRequest::newWithRequestContent( self::WORKER_SCRIPT, $content );
			$request->addResponseCallbacks( $this->getResponseCallback() );

			$requestIds[] = $this->client->sendAsyncRequest( $this->connection, $request );
		}

		return $requestIds;
	}

	private function getResponseCallback() : Closure
	{
		return function ( ProvidesResponseData $response )
		{
			$this->responseStream->streamEvent( $response->getBody() );
		};
	}

	/**
	 * @throws Exceptions\LogicException
	 * @throws Throwable
	 * @throws ReadFailedException
	 */
	public function multipleResponsive() : void
	{
		$startTime = microtime( true );
		$this->responseStream->beginStream();

		$requestIds = $this->sendAsyncRequests( 100 );

		$this->responseStream->streamEvent(
			sprintf( 'Sent %d requests', count( $requestIds ) )
		);
		$this->responseStream->streamEvent( '' );

		$this->client->waitForResponses();

		$this->responseStream->streamEvent( '' );
		$this->responseStream->streamEvent(
			'Duration: ' . round( microtime( true ) - $startTime, 4 ) . ' Seconds'
		);

		$this->responseStream->endStream();
	}
}
