<?php declare(strict_types=1);

namespace hollodotme\FastCGI\ClientDemo;

use hollodotme\FastCGI\ClientDemo\Responses\EventSourceStream;
use function ignore_user_abort;
use function register_shutdown_function;
use function set_time_limit;
use function str_replace;

require __DIR__ . '/../vendor/autoload.php';

ignore_user_abort( false );
set_time_limit( 0 );

$command = 'ps -p $(pgrep -d \',\' php) -o pid,time,args --sort -time';

$eventSourceStream = new EventSourceStream();
$eventSourceStream->beginStream();

register_shutdown_function(
	static function () use ( $eventSourceStream )
	{
		$eventSourceStream->endStream();
	}
);

while ( true )
{
	$output = shell_exec( $command );
	$eventSourceStream->streamEvent( '', 'clear' );

	if ( $output )
	{
		$processCount = substr_count( $output, "\n" ) - 1;
		$eventSourceStream->streamEvent( 'Processes: ' . $processCount );
		$eventSourceStream->streamEvent( '' );

		$output = str_replace(
			['master process', 'www', 'static', 'on-demand'],
			["\e[31mmaster process\e[0m", "\e[33mwww\e[0m", "\e[34mstatic\e[0m", "\e[32mon-demand\e[0m"],
			$output
		);

		$eventSourceStream->streamEvent( $output );
	}
	else
	{
		$eventSourceStream->streamEvent( 'Processes: 0' );
	}

	sleep( 1 );
}