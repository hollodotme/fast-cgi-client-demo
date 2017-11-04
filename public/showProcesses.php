<?php declare(strict_types=1);
/**
 * @author  hollodotme
 * @license MIT (See LICENSE file)
 */

namespace hollodotme\FastCGI\ClientDemo;

use hollodotme\FastCGI\ClientDemo\Responses\EventSourceStream;

require __DIR__ . '/../vendor/autoload.php';

$command = 'ps -p $(pgrep -d \',\' php) -o pid,time,args --sort -time | grep background';

$eventSourceStream = new EventSourceStream();
$eventSourceStream->beginStream();

for ( $i = 0; $i < 500; $i++ )
{
	$output = shell_exec( $command );
	$eventSourceStream->streamEvent( '', 'clear' );

	if ( $output )
	{
		$processCount = substr_count( $output, "\n" );
		$eventSourceStream->streamEvent( 'Processes: ' . $processCount );
		$eventSourceStream->streamEvent( '' );
		$eventSourceStream->streamEvent( $output );
	}
	else
	{
		$eventSourceStream->streamEvent( 'Processes: 0' );
	}

	sleep( 1 );
}

$eventSourceStream->endStream();
