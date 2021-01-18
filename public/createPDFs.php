<?php declare(strict_types=1);

namespace hollodotme\FastCGI\ClientDemo;

use hollodotme\FastCGI\ClientDemo\Responses\EventSourceStream;
use hollodotme\FastCGI\SocketConnections\NetworkSocket;
use hollodotme\FastCGI\SocketConnections\UnixDomainSocket;

require __DIR__ . '/../vendor/autoload.php';

$connections = [
	'network-socket'     => new NetworkSocket( 'worker', 9001 ),
	'unix-domain-socket' => new UnixDomainSocket( '/socket/php-uds.sock' ),
];

$callMethod = $_GET['callMethod'] ?? 'single';
$connection = $connections[ $_GET['connection'] ] ?? $connections['network-socket'];
$creator    = new PDFCreator( $connection, new EventSourceStream() );

switch ( $callMethod )
{
	case 'multipleOrdered':
		$creator->multipleOrdered();
		break;
	case 'multipleResponsive':
		$creator->multipleResponsive();
		break;
	default:
	case 'single':
		$creator->single();
		break;
}
