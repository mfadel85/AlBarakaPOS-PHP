<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	include_once "shared/ez_sql_core.php";
	include_once "ez_sql_mysqli.php";
	require 'phpMailer/PHPMailerAutoload.php';
	print_r($_REQUEST['customerID']);
	die();
 ?>
<HTML>
	<HEAD>
		<title>Ödeme Sayfası</title>
		<meta http-equiv="Content-Language" content="tr">
		<meta charset="utf-8">		
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Expires" content="now">
	</HEAD>
<?php
		
		$db = new ezSQL_mysqli('aydimtr1_payment','D1IeUhY9B','aydimtr1_payment','localhost','UTF-8');
		
		$taksit     = $db->escape($_REQUEST['taksit']);
		$amount     = $db->escape($_REQUEST['pAmount']);
		$customerID = $db->escape($_REQUEST['customerID']);
		$pCVV2      = $db->escape($_REQUEST['pCVV2']);
		$customerID = $db->escape($_REQUEST['customerID']);
		$customerID = 1;

		$cardNo     = $db->escape($_REQUEST['pCardNo1']).$db->escape($_REQUEST['pCardNo2']).$db->escape($_REQUEST['pCardNo3']).$db->escape($_REQUEST['pCardNo4']);
		$cardEn     = $db->escape($_REQUEST['pCardNo1'])."-xxxx-"."-xxxx-".$db->escape($_REQUEST['pCardNo4']);
			
		$notes = $_REQUEST['notes'];
		$sonSipNo = $db->get_var("select order_no from payment order by date_modified desc limit 1");
		$pSipNo = intval($sonSipNo) + 1;

		$cardHolder = $db->escape($_POST['card-holder-name']);

		//$sonTarih = $_REQUEST['expiry-year']."-".$_REQUEST['expiry-month'];
		$expiryMonth = $db->escape($_REQUEST['expiry-month']);
		$expiryYear = $db->escape($_REQUEST['expiry-year']);
		$expiryDate = $expiryYear.$expiryMonth;
		$sonTarih = $expiryMonth."/20".$expiryYear;
		$a = mt_rand(100000,999999); 
		$inserted = false;

		
		if($taksit == 3 || $taksit == 2 || $taksit== 1 || $taksit== 5){
			$taksitString = "<installment>".$taksit."</installment>";
			$amount = $amount."00";
			$xml = "<posnetRequest><mid>6792360494</mid><tid>67128078</tid><sale><amount>".$amount."</amount><ccno>".$cardNo."</ccno><currencyCode>YT</currencyCode><cvc>".$pCVV2."</cvc><expDate>".$expiryDate."</expDate><orderID>000000000000000000".$a."</orderID>".$taksitString."</sale></posnetRequest>";
			echo '<pre>', htmlentities($xml), '</pre>'; 
			$url = "https://epos.albarakaturk.com.tr/EPosWebService/XML?ENCODING=gzip&xmldata=";
			$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
			$headers[] = 'Content-Length:'.strlen($xml);
			
			$ch = curl_init($url.$xml);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)");
			curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			//curl_setopt($ch, CURLOPT_INTERFACE,$_SERVER['SERVER_ADDR']);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			$str = curl_exec($ch);
			curl_close($ch);
			
			$resArr = array();
			$resArr = json_decode($str);


			$Amount = substr($amount, 0, -2);
			
			if (strpos($str, '<approved>0</approved>') !== false) {
				echo "Başarısız";
			echo "<pre>"; print_r($resArr); echo "</pre>";
			echo $str;
			//die();				
				$insert = $db->query("INSERT INTO `payment` (`id`, `CustomerID`,ePosRef,OnayKodu, `amount`,creditCard,cardHolder,expirydate	,
			`taksit`,notes,order_no, `date_modified`, `completed`) VALUES  (NULL, $customerID,'$ePosRef','$onayKodu',$Amount , 
			'$cardEn','$cardHolder','$sonTarih',$taksit,'$notes',$pSipNo, CURRENT_TIMESTAMP, '0');");
			$db->debug();
				header('Location: https://www.aydinbilgisayar.com/3dodeme/failedPayment.php');


			}
			else if (strpos($str, '<approved>1</approved>') !== false) {
			// get onay kodu, ve ePos Ref
			//echo $pSipNo;
			$start = '<hostlogkey>';
			$end = '</hostlogkey>';

			$pattern = sprintf(
				'/%s(.+?)%s/ims',
				preg_quote($start, '/'), preg_quote($end, '/')
			);
			$ePosRef  = '';
			$onayKodu = '';
			if (preg_match($pattern, $str, $matches)) {
				list(, $match) = $matches;
				$ePosRef = $match;
			}
			$start = '<authCode>';
			$end = '</authCode>';
			$pattern2 = sprintf(
				'/%s(.+?)%s/ims',
				preg_quote($start, '/'), preg_quote($end, '/')
			);
			if (preg_match($pattern2, $str, $matches)) {
				list(, $match) = $matches;
				$onayKodu =  $match;
			}
			if(!isset($customerID))
				$customerID = 0;
			$insert = $db->query("INSERT INTO `payment` (`id`, `CustomerID`,ePosRef,OnayKodu, `amount`,creditCard,cardHolder,expirydate	,
			`taksit`,notes,order_no, `date_modified`, `completed`,banka) VALUES  (NULL, $customerID,'$ePosRef','$onayKodu', $Amount, 
			'$cardEn','$cardHolder','$sonTarih',$taksit,'$notes',$pSipNo, CURRENT_TIMESTAMP, '1','Albaraka');");
			$inserted = true;
			$db->debug();
			//die();
			header('Location: https://www.aydinbilgisayar.com/3dodeme/successfullPayment.php');
			}	
		}

?>
<body>
</body>
</HTML>
