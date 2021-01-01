<?php
  ob_start();
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		require_once 'vendor/autoload.php';
		require_once 'pjmail.php'; 

		require 'phpMailer/PHPMailerAutoload.php';
		include_once "shared/ez_sql_core.php";
		// Include ezSQL database specific component
		include_once "ez_sql_mysqli.php";
		use Spipu\Html2Pdf\Html2Pdf;
		use Spipu\Html2Pdf\Exception\Html2PdfException;
		use Spipu\Html2Pdf\Exception\ExceptionFormatter;
		$db  = new ezSQL_mysqli('aydimtr1_payment','D1IeUhY9B','aydimtr1_payment','localhost');


		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$str = "<hostlogkey>0001000004P0503281</hostlogkey><authCode>007912</authCode>";
		$start = '<hostlogkey>';
		$end = '</hostlogkey>';

		$pattern = sprintf(
			'/%s(.+?)%s/ims',
			preg_quote($start, '/'), preg_quote($end, '/')
		);

		if (preg_match($pattern, $str, $matches)) {
			list(, $match) = $matches;
			//echo $match;
		}
		$start = '<authCode>';
		$end = '</authCode>';
		$pattern2 = sprintf(
			'/%s(.+?)%s/ims',
			preg_quote($start, '/'), preg_quote($end, '/')
		);
		if (preg_match($pattern2, $str, $matches)) {
			list(, $match) = $matches;
			//echo $match;
		}

		$amount = "500";
		//echo "<br>";
		//echo substr($amount, 0, -2);
		//
		//echo $db->get_var("select cardHolder from payment where CustomerID = 7");
		$taksit = 3;
		if($taksit == 3 || $taksit==5 ){
			$taksitString = "<installment>".$taksit."</installment>";
		
		//echo "<br>$taksitString<br>";
		}
		
		try {
			$html2pdf = new Html2Pdf('P', 'A4', 'fr');

			//$content = file_get_contents(K_PATH_MAIN.'examples/data/utf8test.txt');
			//$content = '<page style="font-family: freeserif"><br />'.nl2br($content).'</page>';
			$content = '<page style="font-family: freeserif">test</page>';

			$html2pdf->pdf->SetDisplayMode('real');
			$html2pdf->writeHTML($content);
			$pdf = $html2pdf->Output(__DIR__.'/utf7.pdf');
			$mail = new PJmail(); 
		    $mail->setAllFrom('webmaster@my_site.net', "My personal site"); 
		    $mail->addrecipient('faddel.homsi@gmail.com'); 
		    $mail->addsubject("Example sending PDF"); 
		    $mail->text = "This is an example of sending a PDF file"; 
		    $mail->addbinattachement("my_document.pdf", $pdf,'F' ); 
		    //$res = $mail->sendmail(); 
			echo "test2";
		} catch (Html2PdfException $e) {
			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();
		}
		  ob_end_flush(); 