<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*require_once(dirname(__FILE__) . '/dompdf/lib/html5lib/Parser.php');
//require_once(dirname(__FILE__) . '/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php');
//require_once(dirname(__FILE__) . '/dompdf/lib/php-svg-lib/src/autoload.php');
require(dirname(__FILE__) . '/dompdf/src/Autoloader.php');*/

require_once 'dompdf/lib/html5lib/Parser.php';
require_once 'dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;


class Libdompdf
{

	protected function ci()
	{
		return get_instance();
	}

	public function load_view($html, $name_pdf = "welcome.pdf", $arreglo_at = array("Attachment" => false), $ruta = null)
	{
		$options = new Options();
		$options->set(array(
		        'pdfBackend'=>'PDFLib',
		        'defaultMediaType'=>'print',
		        'defaultPaperSize'=>'A4',
		        'defaultFont'=>'arial',
		        'enable_html5_parser'=>true,
		        'enable_font_subsetting'=>true
		));
		$dompdf = new Dompdf($options);

		// instantiate and use the dompdf class
		$dompdf->loadHtml($html);

		// Render the HTML as PDF
		$dompdf->render();

		if(empty($ruta)){
			// Output the generated PDF to Browser
			$dompdf->stream($name_pdf, $arreglo_at);
		}
		else{
			// Output the generated PDF to Browser
			//$dompdf->stream($ruta.$name_pdf);
			$output = $dompdf->output();
    		file_put_contents($ruta.$name_pdf, $output);
		}
	}
}
