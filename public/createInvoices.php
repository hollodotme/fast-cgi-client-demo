<?php declare(strict_types=1);

namespace hollodotme\FastCGI\ClientDemo;

use hollodotme\FastCGI\ClientDemo\Responses\EventSourceStream;
use hollodotme\FastCGI\SocketConnections\NetworkSocket;
use hollodotme\FastCGI\SocketConnections\UnixDomainSocket;

require __DIR__ . '/../vendor/autoload.php';

$pools = [
	'www-network-socket'    => new NetworkSocket( 'web', 9000 ),
	'static-network-socket' => new NetworkSocket( 'web', 9001 ),
	'on-demand-unix-socket' => new UnixDomainSocket( '/var/run/php-uds.sock' ),
];

$callMethod = $_GET['create'] ?? 'single';
$connection = $pools[ $_GET['pool'] ] ?? $pools['www-network-socket'];
$service    = new InvoiceService( $connection, new EventSourceStream() );

$service->$callMethod();
