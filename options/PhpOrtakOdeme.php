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

//i�lem ba�ar�l� oldu�unda, i�yerinin d�nmesini istedi�i Url adresi (�&� karakteri kullanmay�n�z)
$pokUrl = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=false";

//'i�lem ba�ar�s�z oldu�unda, i�yerinin d�nmesini istedi�i Url adresi (�&� karakteri kullanmay�n�z)
$pfailUrl = "https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=true"; 

$pOrgNo = "006";
$pFirmNo = "9618947";
$pTermNo = pathLeft("00520146",8); // 8 haneye tamamlanmal�

//'16 hane s�f�r (0) g�nderilir. Hash Hesaplamas�na kat�l�r ancak form ile birlikte post edilmez!!! 
//E�er gecerli kredi kart� bilgisi girilirse m��teriye ay�r�ca kredi bilgi sorulmamaktad�r. Bu y�ntemem HTTP Post y�ntemi denilmektedir.
$pCardNo = "4543140281037761";

//'12 hane, kuru�lar� 100 ile �arp�lm��. (�rnek 1,50 -> 000000000150)
$pAmount = hashAmount("1.50"); //Cstr(CDbl(Request.Form("pAmount")) * 100);
echo $pAmount;

$pTaksit = "1" ;
$pXid = "00000000000000000002"; //Firman�n belirleyecegi bir deger sonuc ile birlikte geri d�nmektedir.
$pSipNo = "10"; 
$pMPI3D = "true";

$pCurrency = "949";   //'��yerinin farkl� para biriminde i�lem yetkisi var ise bu alanda 
 		    //		    			'ilgili para birimini g�nderir. Bu alan�n g�nderilmemesi, s�f�r 
  		    //		    	'g�nderilmesi durumlar�nda 949-TL olarak provizyona ��k�l�r.

//'Firma key - BKM i�yeri anahtar�
$merchantKey = "2ajQbd6Y";                                   

//Hash parametrelerinde Hex yada Base64 hesaplamalar�n�n biri dogru g�nderilmesi yeterlidir.
//h = pOrgNo   + pFirmNo +  pTermNo +  pCardNo +  pAmount  + MerchanKey

$pHashHex = strtoupper(sha1($orgNo.$firmNo.$termNo.$cardNo.$amount.$merchanKey)); //'HEX sonuc doner
  

$htmlString = "<html><body>";
$htmlString = $htmlString ."<form action='". $posrtUrl ."' method='Post' name='Gateway' >";
$htmlString = $htmlString ."<input type='hidden' name='pOrgNo' value='". $pOrgNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pFirmNo' value='". $pFirmNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pTermNo' value='". $pTermNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pCardNo' value=''>";
$htmlString = $htmlString ."<input type='hidden' name='pCVV2' value=''>";
$htmlString = $htmlString ."<input type='hidden' name='pExpDate' value=''>";
$htmlString = $htmlString ."<input type='hidden' name='pAmount' value='". $pAmount ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pTaksit' value='". $pTaksit ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pXid' value='". $pXid ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pSipNo' value='". $pSipNo ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pokUrl' value='". $pokUrl ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pfailUrl' value='". $pfailUrl ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pCurrency' value='". $pCurrency ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pMPI3D' value='". $pMPI3D ."'>";
$htmlString = $htmlString ."<input type='hidden' name='pHashB64' value=''>";
$htmlString = $htmlString ."<input type='hidden' name='pHashHex' value='". $pHashHex ."'>";
$htmlString = $htmlString ."<input type='submit' name='pHashHex' value='submit'>";
$htmlString = $htmlString ."</form>";
$htmlString = $htmlString ."<script language='JavaScript'>";
$htmlString = $htmlString ."//Gateway.submit();";
$htmlString = $htmlString ."</script>";
$htmlString = $htmlString ."</body></html>";

echo $htmlString;
?>