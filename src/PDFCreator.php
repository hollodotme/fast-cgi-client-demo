<?php declare(strict_types=1);
/**
 * @author  hollodotme
 * @license MIT (See LICENSE file)
 */

namespace hollodotme\FastCGI\ClientDemo;

use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\ClientDemo\Responses\EventSourceStream;
use hollodotme\FastCGI\Interfaces\ProvidesResponseData;
use hollodotme\FastCGI\Requests\PostRequest;

/**
 * Class PDFCreator
 * @package hollodotme\FastCGI\ClientDemo
 */
final class PDFCreator
{
	private const WORKER_SCRIPT = '/vagrant/bin/createPDF.php';

	/** @var Client */
	private $client;

	/** @var EventSourceStream */
	private $responseStream;

	public function __construct( Client $client, EventSourceStream $responseStream )
	{
		$this->client         = $client;
		$this->responseStream = $responseStream;
	}

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

	private function sendAsyncRequests( int $amount ) : array
	{
		$requestIds = [];
		for ( $i = 0; $i < $amount; $i++ )
		{
			$documentId = sprintf( 'Document-%02d', $i );
			$body       = http_build_query( ['documentId' => $documentId] );
			$request    = new PostRequest( self::WORKER_SCRIPT, $body );
			$request->addResponseCallbacks( $this->getResponseCallback() );
			$requestIds[] = $this->client->sendAsyncRequest( $request );
		}

		return $requestIds;
	}

	private function getResponseCallback() : \Closure
	{
		return function ( ProvidesResponseData $response )
		{
			$this->responseStream->streamEvent( $response->getBody() );
		};
	}

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
