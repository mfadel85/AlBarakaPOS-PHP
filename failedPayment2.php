<?php 
		ob_start();

		header('Content-Type: text/html; charset=utf-8');

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		include_once "shared/ez_sql_core.php";
		// Include ezSQL database specific component
		include_once "ez_sql_mysqli.php";
		require 'phpMailer/PHPMailerAutoload.php';
		require_once 'vendor/autoload.php';

		$mail = new PHPMailer;
		// Initialise database object and establish a connection
		// at the same time - db_user / db_password / db_name / db_host
		// db_host can "host:port" notation if you need to specify a custom port
		use Spipu\Html2Pdf\Html2Pdf;
		use Spipu\Html2Pdf\Exception\Html2PdfException;
		use Spipu\Html2Pdf\Exception\ExceptionFormatter;
 
?>
<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="utf-8">
	<!--<meta http-equiv="refresh" content="15;url=https://www.aydinbilgisayar.com/3dodeme/?msg=failed&hataKodu=<?php echo $errorNo; ?>&customer_id=<?php echo $customerId ?>">-->

	<title>Ödeme Başarısız</title>
</head>
<body>

</body>
</html>
<?php
		$db  = new ezSQL_mysqli('aydimtr1_payment','D1IeUhY9B','aydimtr1_payment','localhost');

		$lastpayment = $db->get_var("select id from payment order by date_modified desc limit 1");

		$cart  = $db->get_var("select cardHolder from payment where id= $lastpayment");

		if (isset($_REQUEST['ResCode']))
			$errorNo = $_REQUEST['ResCode'];
		
		//$payment = $db->get_row("select * from payment order by date_modified desc limit 1");
		$sonTarih = $db->get_var("select expirydate from payment where id= $lastpayment");
		$ePosRef = $db->get_var("select ePosRef from payment where id= $lastpayment");
		$onayKodu = $db->get_var("select OnayKodu from payment where id= $lastpayment");

		$customerId = $db->get_var("select CustomerID from payment where id= $lastpayment");
		$customerId = 7;
		$notes = $db->get_var("select notes from payment where id= $lastpayment");
		$amount = $db->get_var("select amount from payment where id= $lastpayment");
		$amount = 1;

		$cartNumerasi = $db->get_var("select creditCard from payment where id= $lastpayment");
		$taksit = $db->get_var("select taksit from payment where id= $lastpayment");
		if ($taksit == 0)
			$taksit = 1;

		$update = $db->query("update `payment` set completed = 0 where id= $lastpayment");
		$db2 = new ezSQL_mysqli('aydimtr1_guven','p33hrvYea','aydimtr1_guven','localhost','UTF-8');
		//$db2->set_charset('utf8');
		/*$insert = $db2->query("INSERT INTO `aydimtr1_guven`.`oc_customer_transaction` (`customer_transaction_id`, `customer_id`, `order_id`, `description`, `amount`, `date_added`) VALUES (NULL, $customerId, '0', '$notes', $amount, '2017-03-02 00:00:00');");*/
		$email = $db2->get_var("select email from oc_customer where customer_id = $customerId");
		//$db2->debug();
		//$db2->debug();
		echo "Ödeme başarısız : Mağazaya yönlendirecek";
		
		$name = $db2->get_var("SELECT CONCAT(`firstname`, ' ',lastname) FROM `oc_customer` where customer_id=$customerId");
		//$db2->debug();

		$customer = $name;
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'mail.aydinbilgisayar.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'info@aydinbilgisayar.com';                 // SMTP username
		$mail->Password = 'aydinfo34';                           // SMTP password                          // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to
		$mail->setFrom('info@aydinbilgisayar.com', 'AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ');
		$mail->addAddress('faddel.homsi@prizmamedya.com', 'Joe User');     // Add a recipient
		//echo "<br>$email<br>";
		//$mail->addAddress($email, 'User');  
		$mail->AddBCC('faddel.homsi@gmail.com', 'AYDIN Bilgisayar');     // Add a recipient
		$mail->CharSet = 'UTF-8';
		//$taksit = 3;
		$takit_tutari = $amount / $taksit;
		//echo "<br>$takit_tutari <br>";
		//die();
		$mail->isHTML(true);                                  // Set email format to HTML
		//$amount = 5;
				//$customer = "Vadi Bilişim";
		
		$mail->Subject = "Test email çekimsiz: Sanal Pos Cekim [$amount TL]";
		$body = '';
		$body = "<table style='border:solid gray 1.0pt;padding:0.6pt 0.6pt 0.6pt 0.6pt' ><tr><td colspan='3' >
		<strong><p class='MsoNormal' align='center' style='font-size:14px'>ELEKTRONIK TİCARET SISTEMİ (Sanal-POS Kullanım Detayı)</p>
		</strong></td></tr>";
		$body .= "<tr><td style='width:20px;'>&nbsp;</td><td colspan='2' style='    font-size: 7.0pt;'>Sayın <strong>$customer</strong><br>AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ. Ticaret Sistemini kullandığınız için teşekkür ederiz. 
Yapmış olduğunuz işlem kaydedilmiştir <br>";
		$body .= "İşlem Detayları Aşağıdaki Gibidir;<br></td></tr>";

		$body .= "<tr><td style='width:20px;'>&nbsp;</td><td style='width:150px;    font-size: 8.0pt;'><strong>CARİ ADI      </strong>         </td><td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:".$customer."</td></tr><tr>";
		$body .= "<td style='width:20px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>ÖDEME TUTARI </strong></td><td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:".$amount."</td></tr><tr>";
		$body .= "<td style='width:20px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>TAKSİT SAYISI </strong></td><td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:".$taksit."</td></tr><tr>";
		$body .= "<td style='width:20px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>TAKSİT TUTARI </strong></td><td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:".$amount."</td></tr><tr>";
		$body .= "<td style='width:20px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>KOMİSYON </strong></td><td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:0,00</td></tr><tr>";
		$body .= "<td style='width:20px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>HESABA GEÇECEK TUTAR </strong></td><td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:".$amount."</td></tr><tr>";
		$body .= "<td style='width:20px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>AÇIKLAMA </strong></td><td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:".$notes."</td></tr><tr>";
		$body .= "</tr>";

		$body .= "<tr><td style='width:40px;'>&nbsp;</td><td colspan='2'><span style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>SAYGILARIMIZLA</span>,
		<br><strong>AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ.</strong></td></tr></table>";
		$body .= "<span style='font-size:9.0pt'><strong>KREDİ KARTI ÖDEME TALIMATI</strong></span>";
		$body .= "<table>";
		$body .= "<tr><td style='width:15px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>FİRMA ADI</strong></td><td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:$customer </td></tr>";
		$body .= "<tr><td style='width:15px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>ÜYE İŞYERİ</strong></td><td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ. </td></tr>";
		$body .= "<tr><td style='width:15px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>ADRES</strong></td><td>: </td></tr>";
		$body .= "</table>";
		$body .= "<span style='font-size:9.0pt'>KART HAMİLİNİN</span><br>";
		$body .= "<table>";
	
		$body .= "<tr><td style='width:15px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>ADI SOYADI</strong></td>
		<td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:$cart </td></tr>";
		$body .= "<tr><td style='width:15px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>KREDİ KARTI NUMRASI</strong></td>
		<td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:$cartNumerasi </td></tr>";
		$expiry_date = "10/2018";
		$body .= "<tr><td style='width:15px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>SON KULLANMA TARİHİ</strong></td>
		<td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:$sonTarih </td></tr>";
		$body .= "<tr><td style='width:15px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>TAKSİ SAYISI (RAKAMLA)</strong> </td>
		<td style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>:$taksit </td></tr>";
		$body .= "<tr><td style='width:15px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>REFERANS NO</strong></td>
		<td>:$ePosRef </td></tr>";
		$body .= "<tr><td style='width:15px;'>&nbsp;</td><td style='width:150px;font-size: 8.0pt;'><strong>ONAY KODU</strong></td>
		<td>:$onayKodu </td></tr></table>";
		$body .= "<br><span style='font-size:8.0pt;font-family:Tahoma,sans-serif;'>Yukarıda bilgisini vermiş oldugum kredi kartımdan <strong> $amount </strong> TL tutarın çekilmesini ve çekilen bu tutarın 
				AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ.'ın alacaklı olduğu <strong>$name</strong> firması adına AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ. 
				adlı firmaya ödemiş bulunduğumu, ilgili firma ile aramda oluşabilecek anlaşmazlıklardan dolayı AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ.'ni 
				sorumlu tutmayacağımı, karttan ve tarafımdan kaynaklanabilecek sorunlardan dolayı tutarın çekilmemesi halinde gecikme faizi ve 
				uğranılan zararları ödeyeceğimi beyan ve taahhüt ederim.</span><br>";
		$myDate = date("Y-m-d H:i:s");
		/*$body .= "<table><tr><td style='width:350px;'>&nbsp;</td><td width='20%'></td><td width='50%'><strong>Tarih </strong>: $myDate &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>İmza</strong>:.......................</td></tr></table>";
		$body .= "<h5>İŞ ORTAĞI</h5>";
		$body .= "<p style='font-size:8.0pt;font-family:Tahoma,sans-serif;' >Yukarıda bilgileri verilen kredi kartından Çekilen <strong>$amount</strong> TL tutarın AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ.'ın nezdindeki 
				cari hesabımıza alacak kaydedilmesini, çekilen tutara kredi kartı sahibinin isteği üzerine veya 3. bir kişi tarafından bloke konur ve 
				hesabınıza aktarılmaz ise, ilgili tutarın her türlü gecikme faizleri ile birlikte herhangi bir talebe gerek kalmadan AYDIN Bilgisayar 
				DIŞ TİC. LTD. ŞTİ.'a ödeyecegimizi kabul ve taahhüt ederiz.</p>";
		$body .= "<table><tr><td style='width:450px;'>&nbsp;</td><td width='20%'>&nbsp;</td><td width='30%'>   <strong>  Kaşe ve Yetkili İmza</strong></td></tr></table>";
		$body .= "<h5>GEREKLİ EVRAKLAR</h5>";
		$body .= "<ol><li>Kredi Kartı Sureti</li><li>Kredi Kartı Sahibi Kimlik Sureti</li><li>Şirket İmza Sirküleri</li></ol>";*/		


				
		$mail->Body    = $body;
		//echo $body;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		/*if(!$mail->send()) {
			//echo 'Message could not be sent.';
			//echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			//echo 'Message has been sent';
		}*/
		/*$body = "<table style='border:solid gray 1.0pt;padding:0.6pt 0.6pt 0.6pt 0.6pt'>
    <tr>
        <td colspan='3'>
            <strong><p class='MsoNormal' align='center' style='font-size:14px'>ELEKTRONIK TİCARET SISTEMİ (Sanal-POS Kullanım Detayı)</p>
		</strong></td>
    </tr>
    <tr>
        <td style='width:20px;'>&nbsp;</td>
        <td colspan='2' style='    font-size: 7.0pt;'>Sayın <strong>$customer</strong><br>AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ. Ticaret Sistemini kullandığınız için teşekkür ederiz. Yapmış olduğunuz işlem kaydedilmiştir <br> İşlem Detayları Aşağıdaki Gibidir;<br></td>
    </tr>

    <tr>
        <td style='width:20px;'>&nbsp;</td>
        <td style='width:150px;    font-size: 8.0pt;'><strong>CARİ ADI      </strong> </td>
        <td style='font-size:8.0pt;'>:".$customer."</td>
    </tr>
    <tr>
        <td style='width:20px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>ÖDEME TUTARI </strong></td>
        <td style='font-size:8.0pt;'>:".$amount."</td>
    </tr>
    <tr>
        <td style='width:20px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>TAKSİT SAYISI </strong></td>
        <td style='font-size:8.0pt;'>:".$taksit."</td>
    </tr>
    <tr>
        <td style='width:20px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>TAKSİT TUTARI </strong></td>
        <td style='font-size:8.0pt;'>:".$amount."</td>
    </tr>
    <tr>
        <td style='width:20px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>KOMİSYON </strong></td>
        <td style='font-size:8.0pt;'>:0,00</td>
    </tr>
    <tr>
        <td style='width:20px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>HESABA GEÇECEK TUTAR </strong></td>
        <td style='font-size:8.0pt;'>:".$amount."</td>
    </tr>
    <tr>
        <td style='width:20px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>AÇIKLAMA </strong></td>
        <td style='font-size:8.0pt;'>:".$notes."</td>
    </tr>
    <tr>
    </tr>

    <tr>
        <td style='width:40px;'>&nbsp;</td>
        <td colspan='2'><span style='font-size:8.0pt;'>SAYGILARIMIZLA</span>,
            <br><strong>AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ.</strong></td>
    </tr>
</table>
<span style='font-size:9.0pt'><strong>KREDİ KARTI ÖDEME TALIMATI</strong></span>
<table>
    <tr>
        <td style='width:15px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>FİRMA ADI</strong></td>
        <td style='font-size:8.0pt;'>:$customer </td>
    </tr>
    <tr>
        <td style='width:15px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>ÜYE İŞYERİ</strong></td>
        <td style='font-size:8.0pt;'>:AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ. </td>
    </tr>
    <tr>
        <td style='width:15px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>ADRES</strong></td>
        <td>: </td>
    </tr>
</table>
<span style='font-size:9.0pt'>KART HAMİLİNİN</span><br>
<table>

    <tr>
        <td style='width:15px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>ADI SOYADI</strong></td>
        <td style='font-size:8.0pt;'>:$cart </td>
    </tr>
    <tr>
        <td style='width:15px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>KREDİ KARTI NUMRASI</strong></td>
        <td style='font-size:8.0pt;'>:$cartNumerasi </td>
    </tr>
    <tr>
        <td style='width:15px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>SON KULLANMA TARİHİ</strong></td>
        <td style='font-size:8.0pt;'>:$sonTarih </td>
    </tr>
    <tr>
        <td style='width:15px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>TAKSİT SAYISI (RAKAMLA)</strong> </td>
        <td style='font-size:8.0pt;'>:$taksit </td>
    </tr>
    <tr>
        <td style='width:15px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>REFERANS NO</strong></td>
        <td>:$ePosRef </td>
    </tr>
    <tr>
        <td style='width:15px;'>&nbsp;</td>
        <td style='width:150px;font-size: 8.0pt;'><strong>ONAY KODU</strong></td>
        <td>:$onayKodu </td>
    </tr>
</table>
<br><span style='font-size:8.0pt;'>Yukarıda bilgisini vermiş oldugum kredi kartımdan <strong> $amount </strong> TL tutarın çekilmesini ve çekilen bu tutarın 
				AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ.'ın alacaklı olduğu <strong>$name</strong> firması adına AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ. 
				adlı firmaya ödemiş bulunduğumu, ilgili firma ile aramda oluşabilecek anlaşmazlıklardan dolayı AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ.'ni 
				sorumlu tutmayacağımı, karttan ve tarafımdan kaynaklanabilecek sorunlardan dolayı tutarın çekilmemesi halinde gecikme faizi ve 
				uğranılan zararları ödeyeceğimi beyan ve taahhüt ederim.</span><br>

<table>
    <tr>
        <td style='width:350px;'>&nbsp;</td>
        <td width='20%'></td>
        <td width='50%'><strong>Tarih </strong>: $myDate &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            <strong>İmza</strong>:.......................</td>
    </tr>
</table>
<h5>İŞ ORTAĞI</h5>
<p style='font-size:8.0pt;'>Yukarıda bilgileri verilen kredi kartından Çekilen <strong>$amount</strong> TL tutarın AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ.'ın nezdindeki cari hesabımıza alacak kaydedilmesini, çekilen tutara kredi kartı sahibinin isteği üzerine veya 3. bir kişi tarafından bloke konur ve hesabınıza aktarılmaz ise, ilgili tutarın her türlü gecikme faizleri ile birlikte herhangi bir talebe gerek kalmadan AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ.'a ödeyecegimizi kabul ve taahhüt ederiz.</p>
<table>
    <tr>
        <td style='width:450px;'>&nbsp;</td>
        <td width='20%'>&nbsp;</td>
        <td width='30%'> <strong>  Kaşe ve Yetkili İmza</strong></td>
    </tr>
</table>
<h5>GEREKLİ EVRAKLAR</h5>
<ol>
    <li>Kredi Kartı Sureti</li>
    <li>Kredi Kartı Sahibi Kimlik Sureti</li>
    <li>Şirket İmza Sirküleri</li>
</ol>";*/
		try {
			//$html2pdf = new Html2Pdf('P', 'A4', 'en',true,'utf-8');
			/*$html2pdf = new Html2Pdf('P', 'A4', 'fr');

			//$content = file_get_contents(K_PATH_MAIN.'examples/data/utf8test.txt');
			//$content = '<page style="font-family: freeserif"><br />'.nl2br($content).'</page>';
			$content = "<page style='font-family: courier'>$body</page>";
			//$fontname = TCPDF_FONTS::addTTFfont(__DIR__.'/invoices/Ariel.ttf', 'TrueTypeUnicode', '', 96);
			//$fontname = TCPDF_FONTS::addTTFfont(__DIR__.'/invoices/Ariel.ttf', 'TrueTypeUnicode', '', 96);
			//$html2pdf->setDefaultFont($fontname, '', 14, '', false);

			$html2pdf->pdf->SetDisplayMode('real');
			$html2pdf->writeHTML($content);
			//$pdf = $html2pdf->Output(__DIR__.'/utf7.pdf');
			//$pdf = $html2pdf->Output(__DIR__.'/invoices/utf7.pdf');
			$html2pdf->Output(__DIR__.'/invoices/exemple01.pdf');*/
			/*$html2pdf = new Html2Pdf('P', 'A4', 'tr');

			//$content = file_get_contents(K_PATH_MAIN.'examples/data/utf8test.txt');
			//$content = '<page style="font-family: freeserif"><br />'.nl2br($content).'</page>';
			$content = '<page style="font-family: freeserif">'.$body.'</page>';

			$html2pdf->pdf->SetDisplayMode('real');
			$html2pdf->writeHTML($content);
			$pdf = $html2pdf->Output(__DIR__.'/utf7.pdf');

		    //$res = $mail->sendmail(); 
		} catch (Html2PdfException $e) {
			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();*/
		}
		//ob_end_flush(); 

?>