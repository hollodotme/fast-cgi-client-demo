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

$requestIds = [];
for ( $i = 0; $i < 5; $i++ )
{
	$documentId = bin2hex( random_bytes( 16 ) );

	$request = new PostRequest(
		'/vagrant/bin/create-pdf.php',
		http_build_query( [ 'documentId' => $documentId ] )
	);

	$requestIds[] = $client->sendAsyncRequest( $request );
}

echo 'Sent requests with IDs:<ol><li>' . implode( '</li><li>', $requestIds ) . '</li></ol><br><br>';

$responses = $client->readResponses( 100000, ...$requestIds );

foreach ( $responses as $index => $response )
{
	echo ($index + 1) . '. Request ID: ' . $response->getRequestId() . '<br>';
	echo $response->getBody();
	echo '<br><br>';
}

flush();
