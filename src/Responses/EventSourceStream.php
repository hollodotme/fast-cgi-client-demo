<?php declare(strict_types=1);

namespace hollodotme\FastCGI\ClientDemo\Responses;

use hollodotme\FastCGI\ClientDemo\Exceptions\LogicException;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

final class EventSourceStream
{
	private const BEGIN_OF_STREAM_EVENT = 'beginOfStream';

	private const END_OF_STREAM_EVENT   = 'endOfStream';

	private int $eventSequence = 0;

	private bool $active = false;

	private AnsiToHtmlConverter $ansiToHtmlConverter;

	public function __construct()
	{
		$this->ansiToHtmlConverter = new AnsiToHtmlConverter( new AnsiTheme() );
	}

	/**
	 * @param bool $flushBuffer
	 *
	 * @throws LogicException
	 */
	public function beginStream( bool $flushBuffer = true ) : void
	{
		$this->active = true;

		header( 'Content-Type: text/event-stream; charset=utf-8' );

		if ( $flushBuffer )
		{
			@ob_end_flush();
			@ob_end_clean();
		}

		@ob_implicit_flush( $this->active );

		$this->streamEvent( '', self::BEGIN_OF_STREAM_EVENT );
	}

	/**
	 * @param string      $data
	 * @param string|null $eventName
	 *
	 * @throws LogicException
	 */
	public function streamEvent( string $data, ?string $eventName = null ) : void
	{
		$this->guardStreamIsActive();

		if ( str_contains( $data, PHP_EOL ) )
		{
			foreach ( explode( PHP_EOL, $data ) as $line )
			{
				$this->streamEvent( $line, $eventName );
			}

			return;
		}

		echo 'id: ' . ++$this->eventSequence . PHP_EOL;
		echo (null !== $eventName) ? ('event: ' . $eventName . PHP_EOL) : '';

		if ( str_contains( $data, "\e[" ) )
		{
			$data = (string)$this->ansiToHtmlConverter->convert( $data );
		}

		echo 'data: ' . $data . PHP_EOL . PHP_EOL;
	}

	/**
	 * @throws LogicException
	 */
	private function guardStreamIsActive() : void
	{
		if ( !$this->active )
		{
			throw new LogicException( 'Event source stream is not active.' );
		}
	}

	/**
	 * @throws LogicException
	 */
	public function endStream() : void
	{
		$this->guardStreamIsActive();

		$this->streamEvent( '', self::END_OF_STREAM_EVENT );

		$this->active = false;

		@ob_implicit_flush( $this->active );
	}
}
