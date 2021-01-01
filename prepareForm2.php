<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
	<HEAD>
		<title>Ödeme Sayfası</title>
		<meta http-equiv="Content-Language" content="tr">
		<meta charset="utf-8">		
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Expires" content="now">
	</HEAD>
<?php
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		include_once "shared/ez_sql_core.php";
		include_once "ez_sql_mysqli.php";
		require 'phpMailer/PHPMailerAutoload.php';
		
		
		// Initialise database object and establish a connection
		// at the same time - db_user / db_password / db_name / db_host
		// db_host can "host:port" notation if you need to specify a custom port
		$db = new ezSQL_mysqli('aydimtr1_payment','D1IeUhY9B','aydimtr1_payment','localhost','UTF-8');
		
		$taksit     = $db->escape($_REQUEST['taksit']);
		$amount     = $db->escape($_REQUEST['pAmount']);
		$customerID = $db->escape($_REQUEST['customerID']);
		$pCVV2      = $db->escape($_REQUEST['pCVV2']);
		$customerID = $db->escape($_REQUEST['customerID']);

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

		
		

		function pathLeft($str, $lenght)
		{
			$s = $lenght - strlen($str); 
			$e = '';

			if($s <= 0)
			  return $str;

		  for($i=$s; $i>0; $i--)
		  {
				$e.=0;
		  }
			return $e.$str;
		}		

		function hashAmount($v)
		{ 
		  $decimalStr = $v * 100;
		  $v = pathLeft($decimalStr,12);
		  return $v; 
		}

		//echo "CVV2 ".$pCVV2."<BR>";
		$pAmount = hashAmount($amount);

		$orgNo = '006';
		$pFirmNo = "9618947";
		$pTermNo = "00520146";
		$merchantKey = "2ajQbd6Y";
		$hash = pathLeft($orgNo,3).$pFirmNo.pathLeft($pTermNo,8).$cardNo.$pAmount.$merchantKey;
		$hash = sha1($hash);
		$hash64  = base64_encode($hash);
		$hashHex = strtoupper($hash); 

		$pExpDate = "20".$expiryDate;

        $pfailUrl = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=true";
        $pfailUrl = "https://www.aydinbilgisayar.com/3dodeme/failedPayment.php";
	    $pokUrl   = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=false";
	    $pokUrl = "https://www.aydinbilgisayar.com/3dodeme/successfullPayment.php";

	    
	    //echo $cardNo2.'<br>';
	    //echo 'taksit'.$taksit;
		if ($taksit == '3F')
		{
			$taksit = 3;
		}
		elseif ($taksit == '6F')
		{
			$taksit = 6;
		}
		$notes = $_REQUEST['notes'];
		$sonSipNo = $db->get_var("select order_no from payment order by date_modified desc limit 1");
		/*echo $sonSipNo;
		$db->debug();*/
		$pSipNo = intval($sonSipNo) + 1;
		//echo $pSipNo;
		$Amount = $amount;
		if (!$inserted)
		$insert = $db->query("INSERT INTO `payment` (`id`, `CustomerID`,ePosRef,OnayKodu, `amount`,creditCard,cardHolder,expirydate	,
			`taksit`,notes,order_no, `date_modified`, `completed`,banka) VALUES  (NULL, $customerID,'$ePosRef','$onayKodu', $Amount, 
			'$cardEn','$cardHolder','$sonTarih',$taksit,'$notes',$pSipNo, CURRENT_TIMESTAMP, '0','Finans');");
		// sdfa
		$db->debug();
		//die();  sfas
		//testData
		$pOrgNo  = "003";
		$pFirmNo = "9999999";
		$pTermNo = "00999999";
		$cardNo = "0000000000000000";
		$pCVV2   = "000";
		$pExpDate= "201010";
		$hash = pathLeft($orgNo,3).$pFirmNo.pathLeft($pTermNo,8).$cardNo.$pAmount.$merchantKey;
		$pfailUrl = "https://www.aydinbilgisayar.com/3dodeme/failedPayment.php";
	    $pokUrl = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=false";
/// we will do validation here if something is wrong data won't be sent to the server
?>


	<body>
		<form id="PostToMPI" name="PostToMPI" method="post" action="https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/NetProvPost.aspx">
			<input type="hidden" name="pOrgNo"   runat="server" value="<?php echo $orgNo; ?>"> 
			<input type="hidden" name="pFirmNo"  runat="server" value="<?php echo $pFirmNo; ?>"> 
			<input type="hidden" name="pTermNo"  runat="server" value="<?php echo $pTermNo; ?>">
			<input type="hidden" name="pCardNo"  runat="server" value="<?php echo $cardNo; ?>">
			<input type="hidden" name="pCVV2"    runat="server" value="<?php echo $pCVV2; ?>">
			<input type="hidden" name="pExpDate" runat="server" value="<?php echo $pExpDate; ?>">
			<input type="hidden" name="pAmount"  runat="server" value="<?php echo $pAmount; ?>">
			<input type="hidden" name="pTaksit"  runat="server" value="<?php echo $taksit; ?>">
			<input type="hidden" name="pXid"     runat="server" value="00000000000000000009">
			<input type="hidden" name="pSipNo"   runat="server" value="<?php echo $pSipNo; ?>">
            <input type="hidden" name="pokUrl" runat="server" value="<?php echo $pokUrl; ?>">
            <input type="hidden" name="pfailUrl" runat="server" value="<?php echo $pfailUrl; ?>">
			<input type="hidden" name="pMPI3D"   runat="server" value="true">
			<input type="hidden" name="pHashB64" runat="server" value="" >
			<input type="hidden" name="pHashHex" runat="server" value="<?php echo $hashHex; ?>" >
			<input type="submit" name="Submit" value="Submit">
		</form>
		<script type="text/javascript">
			PostToMPI.submit();
		</script>
	</body>
</HTML>
