<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<?php  
//echo phpversion()."<br>";

function hashAmount($v)
{ 
  $decimalStr = $v * 100;
  $v = pathLeft($decimalStr,12);
  return $v; 
}

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

function odemeYap($orgNo,$firmNo,$termNo,$cardNo,$cvv2,$expiry,$amount,$ip,$sipNo,$merchanKey){
	
	//echo pathLeft($orgNo,3).$firmNo.pathLeft($termNo,8).$cardNo.hashAmount($amount).$merchanKey."<br><br>";
	
	$hash = strtoupper(sha1(pathLeft($orgNo,3).$firmNo.pathLeft($termNo,8).$cardNo.hashAmount($amount).$merchanKey));
	
	$xml  = '<?xml version="1.0" encoding="utf-8"?>
				<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
				  <soap:Body>
					<GetAuthorization3DMPI xmlns="https://www.e-tahsildar.com.tr/V2/NetProvWS">
					  <pOrgNo>'.$orgNo.'</pOrgNo>
					  <pFirmNo>'.$firmNo.'</pFirmNo>
					  <pTermNo>'.$termNo.'</pTermNo>
					  <pCardNo>'.$cardNo.'</pCardNo>
					  <pCvv2No>'.$cvv2.'</pCvv2No>
					  <pExpiry>'.$expiry.'</pExpiry>
					  <pAmount>'.$amount.'</pAmount>
					  <pTaksit>0</pTaksit>
					  <pCustIP>'.$ip.'</pCustIP>
					  <pBonusAmount>0</pBonusAmount>
					  <pMPIcavv></pMPIcavv>
					  <pMPIxid></pMPIxid>
					  <pMPIeci></pMPIeci>
					  <pMPIversion></pMPIversion>
					  <pMPImerchantID></pMPImerchantID>
					  <pMPImdStatus></pMPImdStatus>
					  <pMPImdErrorMsg></pMPImdErrorMsg>
					  <pMPItxstatus></pMPItxstatus>
					  <pMPIiReqCode></pMPIiReqCode>
					  <pMPIiReqDetail></pMPIiReqDetail>
					  <pMPIvendorCode></pMPIvendorCode>
					  <pMPIcavvAlgorithm></pMPIcavvAlgorithm>
					  <pMPIPAResVerified></pMPIPAResVerified>
					  <pMPIPAResSyntaxOK></pMPIPAResSyntaxOK>
					  <pSipNo>'.$sipNo.'</pSipNo>
					  <pCurrency>949</pCurrency>
					  <pWaitForSaleCompleted>false</pWaitForSaleCompleted>
					  <pHashB64></pHashB64>
					  <pHashHex>'.$hash.'</pHashHex>
					</GetAuthorization3DMPI>
				  </soap:Body>
				</soap:Envelope>';

		
	echo 	$xml."<br><br>";
	
	$headers[] = 'POST /v2/NetProvWS/NetProvWS.asmx HTTP/1.1';
	$headers[] = 'Host: www.e-tahsildar.com.tr';
	$headers[] = 'Content-Type: text/xml; charset=utf-8';
	$headers[] = 'Content-Length:'.strlen($xml);
	$headers[] = 'SOAPAction: "https://www.e-tahsildar.com.tr/V2/NetProvWS/GetAuthorization3DMPI"';

	$ch = curl_init("https://www.e-tahsildar.com.tr/v2/NetProvWS/NetProvWS.asmx");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)");
	curl_setopt($ch, CURLOPT_ENCODING , 'gzip');
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$str = curl_exec($ch);
	curl_close($ch);
	preg_match("/<GetAuthorization3DMPIResult>(.*)<\/GetAuthorization3DMPIResult>/i",$str, $sonuc);
	$sonuc = strip_tags(trim($sonuc[1]));
	
	if(!empty($sonuc)){
		return array("kod" => substr($sonuc,0,2),"hata" => substr($sonuc,2,strlen($sonuc)) );
	}else{
		echo "<pre>".$str."</pre>";
		return false;
	}
}
$cart = '4025918831922253';
$expire = '202603';
$ccv= '345';
//print_r(odemeYap("006","9618947","00520146","4543140281037761","435","201902","150",$_SERVER['REMOTE_ADDR'],uniqid("aa"),"2ajQbd6Y") );
print_r(odemeYap("006","9618947","00520146",$cart,$ccv,    $expire,"1",$_SERVER['REMOTE_ADDR'],uniqid("aa"),"2ajQbd6Y") );
//              $orgNo,$firmNo,   $termNo,  $cardNo, $cvv2,$expiry,$amount,$ip,$sipNo,$merchanKey
?> 
</body>
</html>