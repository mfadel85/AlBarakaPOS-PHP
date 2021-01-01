<?php
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


$posrtUrl = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/NetProvPost.aspx";

//iþlem baþarýlý olduðunda, iþyerinin dönmesini istediði Url adresi (“&” karakteri kullanmayýnýz)
$pokUrl = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=false";

//'iþlem baþarýsýz olduðunda, iþyerinin dönmesini istediði Url adresi (“&” karakteri kullanmayýnýz)
$pfailUrl = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=true"; 

$pOrgNo = "003";
$pFirmNo = "9999999";
$pTermNo = pathLeft("999999",8); // 8 haneye tamamlanmalý
// real data
    $pOrgNo = '006';
    $pFirmNo = "9618947";
    $pTermNo = "00520146";
$pCardNo = "4025902996925995";
$pAmount = hashAmount("1.50"); //Cstr(CDbl(Request.Form("pAmount")) * 100);
$pTaksit = "1" ;
$pXid = "00000000000000000002"; 
$pSipNo = "10"; 
$pMPI3D = "true";
$pCurrency = "949"; 
//'Firma key - BKM iþyeri anahtarý
$merchantKey = "2ajQbd6Y";                                   
//Hash parametrelerinde Hex yada Base64 hesaplamalarýnýn biri dogru gönderilmesi yeterlidir.
//h = pOrgNo   + pFirmNo +  pTermNo +  pCardNo +  pAmount  + MerchanKey

$pHashHex = strtoupper(sha1($pOrgNo.$pFirmNo.$pTermNo.$pCardNo.$pAmount.$merchantKey)); //'HEX sonuc doner
      $hash = pathLeft($pOrgNo,3).$pFirmNo.pathLeft($pTermNo,8).$pCardNo.$pAmount.$merchantKey;

    $hash = sha1($hash);
    $hash64  = base64_encode($hash);
    $hashHex = strtoupper($hash); 
echo $pHashHex." ".$hashHex;
  //die();
$htmlString = "<html><body>";
$htmlString = $htmlString ."<form action='". $posrtUrl ."' method='Post' name='Gateway' >";
$htmlString = $htmlString ."<input type='hidden' name='pOrgNo' value='". $pOrgNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pFirmNo' value='". $pFirmNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pTermNo' value='". $pTermNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pCardNo' value='4025902996925995'>";
$htmlString = $htmlString ."<input type='hidden' name='pCVV2' value='085'>";
$htmlString = $htmlString ."<input type='hidden' name='pExpDate' value='202710'>";
$htmlString = $htmlString ."<input type='hidden' name='pAmount' value='". $pAmount ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pTaksit' value='". $pTaksit ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pXid' value='". $pXid ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pSipNo' value='". $pSipNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pokUrl' value='". $pokUrl ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pfailUrl' value='". $pfailUrl ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pCurrency' value='". $pCurrency ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pMPI3D' value='". $pMPI3D ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pHashB64' value=''>";
$htmlString = $htmlString .
"<input type='hidden' name='pHashHex' value='". $hashHex ."'>";
$htmlString = $htmlString ."</form>";
$htmlString = $htmlString ."<script language='JavaScript'>";
$htmlString = $htmlString ."Gateway.submit();";
$htmlString = $htmlString ."</script>";
$htmlString = $htmlString ."</body></html>";

echo $htmlString;
