<?php
/**
 *
 * Class used as base to create PDF
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
require DoctreatGlobalSettings::get_plugin_path() . 'libraries/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;
if (!class_exists('Doctreat_Prepare_Pdf')) {

    class Doctreat_Prepare_Pdf {

        function __construct() {
			add_action('init', array(&$this, 'doctreat_do_pdf_action'));
        }

		/**
         * @pdf download
         * @return {}
         * @author amentotech
         */

		public function doctreat_do_pdf_action() {
			if( !empty($_POST['pdf_booking_id']) ){
				$booknig_id	= !empty($_POST['pdf_booking_id']) ? intval($_POST['pdf_booking_id']) : '';
				$html		= apply_filters('doctreat_pdf',$booknig_id);
				ob_clean();
				
				$dompdf 	= new Dompdf();
				$dompdf->loadHtml($html);
				$dompdf->set_option('isHtml5ParserEnabled', true);
				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				$dompdf->stream("appointment-".$booknig_id.".pdf");
				ob_flush();
			}

            //pdf medication
			if( !empty($_POST['pdf_doctor_id']) ){
				$booknig_id	= !empty($_POST['pdf_doctor_id']) ? intval($_POST['pdf_doctor_id']) : '';
				$html		= apply_filters('medecine_pdf',$booknig_id);
				ob_clean();
				
				$dompdf 	= new Dompdf();
				$dompdf->loadHtml($html);
				$dompdf->set_option('isHtml5ParserEnabled', true);
				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				$dompdf->stream("medication-".$booknig_id.".pdf");
				ob_flush();
			}
		}
        
	}
   new Doctreat_Prepare_Pdf();
}
