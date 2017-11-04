<?php declare(strict_types=1);
/**
 * @author  hollodotme
 * @license MIT (See LICENSE file)
 */

namespace hollodotme\FastCGI\ClientDemo;

use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\ClientDemo\Responses\EventSourceStream;
use hollodotme\FastCGI\SocketConnections\NetworkSocket;

require __DIR__ . '/../vendor/autoload.php';

$callMethod = $_GET['callMethod'] ?? 'single';
$connection = new NetworkSocket( '127.0.0.1', 9001 );
$client     = new Client( $connection );
$creator    = new PDFCreator( $client, new EventSourceStream() );

$creator->{$callMethod}();
