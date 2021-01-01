<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
	<HEAD>
		<title>Prepare Form</title>
		<meta http-equiv="Content-Language" content="tr">
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-9">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Expires" content="now">
	</HEAD>
	<body>
	<?php
		
		
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
		$pXid = "00000000000000000002"; //Firmanýn belirleyecegi bir deger sonuc ile birlikte geri dönmektedir.
		$pSipNo = "10"; 
		$pMPI3D = "true";

		$pCurrency = "949";  
	 	$pOrgNo = $_REQUEST['pOrgNo'];
		$pCartNo =  $_REQUEST['pCardNo'];
		$pFirmNo = $_REQUEST['pFirmNo'];
		$pTermNo =  $_REQUEST['pTermNo'];
		$pCardNo = $_REQUEST['pCardNo'];
		$pCVV2 =  $_REQUEST['pCVV2'];
		$pAmount = hashAmount($_REQUEST['pAmount']);
		$pTaksit =  $_REQUEST['pTaksit'];
		$pXid = $_REQUEST['pXid'];
		$pokUrl =  $_REQUEST['pokUrl'];
		$pfailUrl = $_REQUEST['pfailUrl'];
		$pMPI3D = $_REQUEST['pMPI3D'];
		$merchantKey = "2ajQbd6Y";
		$pExpDate  = $_REQUEST['pExpDate'];
		$h = $pOrgNo.$pFirmNo.$pTermNo.$pCardNo.$pAmount.$merchantKey;
		$pHashB64 =  base64_encode (sha1($h));
		$pHashHex =  strtoupper(sha1($h));


        //$pHashHex = strtoupper(sha1($pOrgNo.$pFirmNo.$pTermNo.$pCardNo.$pAmount.$merchantKey)); //'HEX sonuc doner

        //$pHashHex = sha1($pOrgNo.$pFirmNo.$pTermNo.$pCardNo.$pAmount.$merchantKey); //'HEX sonuc doner
		echo $pHashHex;
$posrtUrl = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/NetProvPost.aspx";

//iþlem baþarýlý olduðunda, iþyerinin dönmesini istediði Url adresi (“&” karakteri kullanmayýnýz)
$pokUrl = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=false";

//'iþlem baþarýsýz olduðunda, iþyerinin dönmesini istediði Url adresi (“&” karakteri kullanmayýnýz)
$pfailUrl = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=true"; 

	
$htmlString = "<html><body>";
$htmlString = $htmlString ."<form action='". $posrtUrl ."' method='Post' name='Gateway' >";
$htmlString = $htmlString ."<input type='hidden' name='pOrgNo' value='". $pOrgNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pFirmNo' value='". $pFirmNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pTermNo' value='". $pTermNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pCardNo' value='". $pCardNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pCVV2' value='". $pCVV2 ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pExpDate' value='". $pExpDate ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pAmount' value='". $pAmount ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pTaksit' value='". $pTaksit ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pXid' value='". $pXid ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pSipNo' value='". $pSipNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pokUrl' value='". $pokUrl ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pfailUrl' value='". $pfailUrl ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pCurrency' value='". $pCurrency ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pMPI3D' value='". $pMPI3D ."'>";

//$pTermNo
//$htmlString = $htmlString ."<input type='hidden' name='pHashB64' value='". $pHashB64 ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pHashHex' value='". $pHashHex ."'><input type='submit' name='Submit' value='Submit''>";
?>

<?
$htmlString = $htmlString ."</form>";
/*$htmlString = $htmlString ."<script language='JavaScript'>";
$htmlString = $htmlString ."Gateway.submit();";
$htmlString = $htmlString ."</script>";*/
$htmlString = $htmlString ."</body></html>";

echo $htmlString;
?>
