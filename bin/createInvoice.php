<?php declare(strict_types=1);

use hollodotme\FastCGI\ClientDemo\InvoicePdf;

require __DIR__ . '/../vendor/autoload.php';

error_reporting( E_ALL & ~E_DEPRECATED );
ini_set( 'display_errors', 'On' );

$invoiceId = $_POST['documentId'] ?? '1234';

$fileName = InvoicePdf::forInvoiceId( $invoiceId )->generate()->getFileName();

sleep( random_int( 0, 2 ) );

echo sprintf(
	'<a href="/documents/%s" target="_blank">%s</a>',
	$fileName,
	$fileName
);
flush();
