<?php declare(strict_types=1);

namespace hollodotme\FastCGI\ClientDemo;

use JetBrains\PhpStorm\Pure;
use TCPDF;
use function ob_get_clean;
use function ob_start;
use function sprintf;

final class InvoicePdf
{
	private TCPDF $pdf;

	public static function forInvoiceId( string $invoiceId ) : self
	{
		return new self( $invoiceId );
	}

	private function __construct( private string $invoiceId )
	{
		$this->pdf = new TCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
	}

	#[Pure]
	public function getFileName() : string
	{
		return sprintf( 'Invoice-%s.pdf', $this->invoiceId );
	}

	public function generate() : self
	{
		$this->pdf->SetCreator( PDF_CREATOR );
		$this->pdf->SetAuthor( 'Holger Woltersdorf' );
		$this->pdf->SetTitle( sprintf( 'Invoice #%s', $this->invoiceId ) );
		$this->pdf->SetSubject( 'FastCGI Client Demo' );
		$this->pdf->SetKeywords( 'FastCGI, Demo, PHP, OSS' );
		$this->pdf->setHeaderFont( [PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN] );
		$this->pdf->setFooterFont( [PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA] );
		$this->pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );
		$this->pdf->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
		$this->pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
		$this->pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
		$this->pdf->SetAutoPageBreak( true, PDF_MARGIN_BOTTOM );
		$this->pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );
		$this->pdf->SetFont( 'helvetica', '', 10 );

		$this->pdf->AddPage();

		$this->pdf->writeHTML(
			$this->loadHtmlTemplate(
				__DIR__ . '/Templates/Invoice.phtml',
				[
					'invoiceId' => $this->invoiceId,
				]
			),
			true,
			false,
			true,
			false,
			''
		);

		$this->pdf->AddPage();

		$this->pdf->writeHTML(
			$this->loadHtmlTemplate( __DIR__ . '/Templates/Terms.phtml', [] ),
			true,
			false,
			true,
			false,
			''
		);

		$this->pdf->lastPage();

		/** @noinspection UnusedFunctionResultInspection */
		$this->pdf->Output( __DIR__ . '/../public/documents/' . $this->getFileName(), 'F' );

		return $this;
	}

	private function loadHtmlTemplate( string $templatePath, array $data ) : string
	{
		ob_start();

		$context = (object)$data;

		/** @noinspection PhpIncludeInspection */
		include $templatePath;

		return ob_get_clean();
	}
}