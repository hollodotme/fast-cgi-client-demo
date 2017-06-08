<?php declare(strict_types=1);
/**
 * @author h.woltersdorf
 */

namespace hollodotme\FastCGI\Demo;

use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\Requests\PostRequest;
use hollodotme\FastCGI\SocketConnections\NetworkSocket;

require __DIR__ . '/../../vendor/autoload.php';

$socket = new NetworkSocket( '127.0.0.1', 9000, 5000, 100000 );
$client = new Client( $socket );

$documentId = bin2hex( random_bytes( 16 ) );

$request = new PostRequest(
	'/vagrant/bin/create-pdf.php',
	http_build_query( [ 'documentId' => $documentId ] )
);

$response = $client->sendRequest( $request );

echo $response->getBody();
flush();
