<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace hollodotme\FastCGI\ClientDemo\Responses;

use hollodotme\FastCGI\ClientDemo\Exceptions\LogicException;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

/**
 * Class EventSourceStream
 * @package hollodotme\FastCGI\ClientDemo\Responses
 */
final class EventSourceStream
{
	private const BEGIN_OF_STREAM_EVENT = 'beginOfStream';

	private const END_OF_STREAM_EVENT   = 'endOfStream';

	/** @var int */
	private $eventSequence = 0;

	/** @var bool */
	private $active = false;

	/** @var AnsiToHtmlConverter */
	private $ansiToHtmlConverter;

	public function __construct()
	{
		$this->ansiToHtmlConverter = new AnsiToHtmlConverter( new AnsiTheme() );
	}

	public function beginStream( bool $flushBuffer = true ) : void
	{
		$this->active = true;

		header( 'Content-Type: text/event-stream; charset=utf-8' );

		if ( $flushBuffer )
		{
			@ob_end_flush();
			@ob_end_clean();
		}

		@ob_implicit_flush( 1 );

		$this->streamEvent( '', self::BEGIN_OF_STREAM_EVENT );
	}

	public function streamEvent( string $data, ?string $eventName = null ) : void
	{
		$this->guardStreamIsActive();

		if ( false !== strpos( $data, PHP_EOL ) )
		{
			foreach ( explode( PHP_EOL, $data ) as $line )
			{
				$this->streamEvent( $line, $eventName );
			}

			return;
		}

		echo 'id: ' . ++$this->eventSequence . PHP_EOL;
		echo (null !== $eventName) ? ('event: ' . $eventName . PHP_EOL) : '';

		if ( false !== strpos( $data, "\e[" ) )
		{
			$data = $this->ansiToHtmlConverter->convert( $data );
		}

		echo 'data: ' . $data . PHP_EOL . PHP_EOL;
	}

	private function guardStreamIsActive() : void
	{
		if ( !$this->active )
		{
			throw new LogicException( 'Event source stream is not active.' );
		}
	}

	public function endStream() : void
	{
		$this->guardStreamIsActive();

		$this->streamEvent( '', self::END_OF_STREAM_EVENT );

		$this->active = false;

		@ob_implicit_flush( 0 );
	}
}
