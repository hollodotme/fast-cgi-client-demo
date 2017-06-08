<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

$documentId = $_POST['documentId'] ?? $argv[1];
$tempfile   = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $documentId . '.html';
$outputFile = '/vagrant/public/documents/' . $documentId . '.pdf';

$htmlContent = file_get_contents( __DIR__ . '/Template.html' );
$htmlContent = str_replace( '[DOCUMENT_ID]', $documentId, $htmlContent );
file_put_contents( $tempfile, $htmlContent );
chmod( $tempfile, 0777 );

$command = sprintf(
	'xvfb-run wkhtmltopdf %s %s',
	escapeshellarg( 'file://' . $tempfile ),
	escapeshellarg( $outputFile )
);

sleep( random_int( 2, 6 ) );

shell_exec( $command );

@unlink( $tempfile );

echo sprintf(
	'<a href="http://demo.fast-cgi-client.de/documents/%s.pdf" target="_blank">%s.pdf</a>',
	$documentId,
	$documentId
);
flush();
