<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>PROVUS MPI PAYMENT TEST PAGE</title>
<meta http-equiv="Content-Language" content="tr">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-9">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="now">
<STYLE>
BODY{font-size:8;font-family:tahoma,Arial,Verdana}
TD{font-size:12;font-family:tahoma,Arial,Verdana}
.TXT{Background-Color: #EFEFEF;Color:#0000a0;Border:2;Border-Style:Groove;Border-Color:Dashed Silver;Font-Size:12PX;Font-Weight:normal;Font-Family:tahoma,Arial,Verdana;}
.SEC{Font-family:tahoma,Arial,Verdana;font-weight:400;font-size:8pt;color:#000000;border-style:none;background-color:#EFEFEF;}
.BTN{Font-family:Tahoma,Verdana,Arial;font-weight:400;font-size:8pt;color:#000000;background-color:#EFEFEF;width:100px;height:20px;border-left: 1px #ffffff solid;border-top: 1px #ffffff solid;border-right: 1px #C0C0C0 solid;border-bottom: 1px #C0C0C0 solid;cursor:hand}
</STYLE>
<?php
$productionUrl = "https://mpi.bkm.com.tr/mdpaympi/mpiserver";
$testUrl = "https://testmpi.bkm.com.tr/mdpaympi/mpiserver";


?>
<script language="javaScript">

var merchantKey = "2ajQbd6Y";

function doldur()
{

	var m=document.payform;	

	m.version.value = "2.0";
	m.Pan.value = "4543140281037761";
	m.expiry.value = "1902";
	m.purchAmount.value = "1";
	m.exponent.value = "2";
	m.description.value = "desc";
	m.currency.value = "949";
	m.merchantID.value = "206000000009618947";
	m.deviceCategory.value = "";
	m.xid.value = "3U9H1r+rcFqBo7ALgZSp5qSXseU=";
 	m.okUrl.value  = "https://www.aydinbilgisayar.com/3dodeme/2ndLevel.php";
 	m.failUrl.value  = "https://www.aydinbilgisayar.com/3dodeme/2ndLevel1.php" ;

//	m.okUrl.value  = "http://localhost/BkmMPIReturn/BkmMPIReturn.aspx?prms=" + m.Pan.value + "x" + m.expiry.value + "x" + m.purchAmount.value ;
	//m.failUrl.value = "https://www.e-tahsildar.com.tr/BkmMPIReturn/BkmMPIReturn.aspx";
	m.MD.value = "Merchant data";

	createHash();
}

function createHash()
{

	var m=document.payform;
	var hashdata = m.version.value + m.cardType.value + m.Pan.value + m.expiry.value;
	hashdata += m.deviceCategory.value + m.purchAmount.value + m.exponent.value + m.description.value;
	hashdata += m.currency.value + m.merchantID.value + m.xid.value + m.okUrl.value + m.failUrl.value;
	hashdata += m.MD.value + merchantKey;
	
	m.digest.value = encode64(sha1Hash(hashdata));

	return 0;
}


function send() 
{
	payform.submit();

}


var keyStr = "ABCDEFGHIJKLMNOP" +
             "QRSTUVWXYZabcdef" +
             "ghijklmnopqrstuv" +
             "wxyz0123456789+/" +
             "=";

function encode64(input) {
   var output = "";
   var chr1, chr2, chr3 = "";
   var enc1, enc2, enc3, enc4 = "";
   var i = 0;

   do {
       chr1 = eval('0x' + input.charAt(i++) + input.charAt(i++));
       if (i<input.length)
            chr2 = eval('0x' + input.charAt(i++) + input.charAt(i++));
       else 
            i=i+2;
       if (i<input.length) 
            chr3 = eval('0x' + input.charAt(i++) + input.charAt(i++));
       else 
            i=i+2;
		
       enc1 = chr1 >> 2;
       enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
       enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
       enc4 = chr3 & 63;
 
       if (i == input.length + 4) {
          enc3 = enc4 = 64;
       } else if (i == input.length + 2) {
          enc4 = 64;
       } 

       output = output + 
          keyStr.charAt(enc1) + 
          keyStr.charAt(enc2) + 
          keyStr.charAt(enc3) + 
          keyStr.charAt(enc4);
       chr1 = chr2 = chr3 = "";
       enc1 = enc2 = enc3 = enc4 = "";
    } while (i < input.length);

    return output;
}


function sha1Hash(msg)
{
    // constants [4.2.1]
    var K = [0x5a827999, 0x6ed9eba1, 0x8f1bbcdc, 0xca62c1d6];

    // PREPROCESSING 
 
    msg += String.fromCharCode(0x80); // add trailing '1' bit to string [5.1.1]

    // convert string msg into 512-bit/16-integer blocks arrays of ints [5.2.1]
    var l = Math.ceil(msg.length/4) + 2;  // long enough to contain msg plus 2-word length
    var N = Math.ceil(l/16);              // in N 16-int blocks
    var M = new Array(N);
    for (var i=0; i<N; i++) {
        M[i] = new Array(16);
        for (var j=0; j<16; j++) {  // encode 4 chars per integer, big-endian encoding
            M[i][j] = (msg.charCodeAt(i*64+j*4)<<24) | (msg.charCodeAt(i*64+j*4+1)<<16) | 
                      (msg.charCodeAt(i*64+j*4+2)<<8) | (msg.charCodeAt(i*64+j*4+3));
        }
    }
    // add length (in bits) into final pair of 32-bit integers (big-endian) [5.1.1]
    // note: most significant word would be ((len-1)*8 >>> 32, but since JS converts
    // bitwise-op args to 32 bits, we need to simulate this by arithmetic operators
    M[N-1][14] = ((msg.length-1)*8) / Math.pow(2, 32); M[N-1][14] = Math.floor(M[N-1][14])
    M[N-1][15] = ((msg.length-1)*8) & 0xffffffff;

    // set initial hash value [5.3.1]
    var H0 = 0x67452301;
    var H1 = 0xefcdab89;
    var H2 = 0x98badcfe;
    var H3 = 0x10325476;
    var H4 = 0xc3d2e1f0;

    // HASH COMPUTATION [6.1.2]

    var W = new Array(80); var a, b, c, d, e;
    for (var i=0; i<N; i++) {

        // 1 - prepare message schedule 'W'
        for (var t=0;  t<16; t++) W[t] = M[i][t];
        for (var t=16; t<80; t++) W[t] = ROTL(W[t-3] ^ W[t-8] ^ W[t-14] ^ W[t-16], 1);

        // 2 - initialise five working variables a, b, c, d, e with previous hash value
        a = H0; b = H1; c = H2; d = H3; e = H4;

        // 3 - main loop
        for (var t=0; t<80; t++) {
            var s = Math.floor(t/20); // seq for blocks of 'f' functions and 'K' constants
            var T = (ROTL(a,5) + f(s,b,c,d) + e + K[s] + W[t]) & 0xffffffff;
            e = d;
            d = c;
            c = ROTL(b, 30);
            b = a;
            a = T;
        }

        // 4 - compute the new intermediate hash value
        H0 = (H0+a) & 0xffffffff;  // note 'addition modulo 2^32'
        H1 = (H1+b) & 0xffffffff; 
        H2 = (H2+c) & 0xffffffff; 
        H3 = (H3+d) & 0xffffffff; 
        H4 = (H4+e) & 0xffffffff;
    }

    return H0.toHexStr() + H1.toHexStr() + H2.toHexStr() + H3.toHexStr() + H4.toHexStr();
}

//
// function 'f' [4.1.1]
//
function f(s, x, y, z) 
{
    switch (s) {
    case 0: return (x & y) ^ (~x & z);
    case 1: return x ^ y ^ z;
    case 2: return (x & y) ^ (x & z) ^ (y & z);
    case 3: return x ^ y ^ z;
    }
}

//
// rotate left (circular left shift) value x by n positions [3.2.5]
//
function ROTL(x, n)
{
    return (x<<n) | (x>>>(32-n));
}

//
// extend Number class with a tailored hex-string method 
//   (note toString(16) is implementation-dependant, and 
//   in IE returns signed numbers when used on full words)
//
Number.prototype.toHexStr = function()
{
    var s="", v;
    for (var i=7; i>=0; i--) { v = (this>>>(i*4)) & 0xf; s += v.toString(16); }
    return s;
}

</script>

</head>
<BODY bgColor=#FBFBFB leftMargin=80 topMargin=10>
<form action="<?php echo $productionUrl; ?>" method="Post" name="payform">
<table border="0" width="450" cellspacing="0" cellpadding="0">
<tr><td>
	<table border="0" width="450" cellspacing="0" cellpadding="0">
	<tr><td><b>3-D SECURE TEST PAGE</b></td></tr>
	</table>
<br>
</td></tr>
<tr><td><hr color="Red"><br></td></tr>
<tr><td>
	<table border="0" width="450" cellspacing="0" cellpadding="3">
	<tr>
	<td width="130"><b>Version<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="8" maxlength="4" class="TXT" name="version"></td>
	</tr>
	<tr>
	<td width="130"><b>Card Number<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="25" maxlength="19" class="TXT" name="Pan"></td>
	</tr>
	<tr>
	<td width="130"><b>Expire Date<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="8" maxlength="4" class="TXT" name="expiry"> (yymm)</td>
	</tr>
	<tr>
	<td width="130"><b>Amount<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="8" maxlength="10" class="TXT" name="purchAmount"></td>
	</tr>
	<tr>
	<td width="130"><b>Exponent<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="8" maxlength="10" class="TXT" name="exponent"></td>
	</tr>
	<tr>
	<td width="130"><b>Explanation<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="25" maxlength="25" class="TXT" name="description"></td>
	</tr>
	<tr>
	<td width="130"><b>Currency Code<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="8" maxlength="3" class="TXT" name="currency"></td>
	</tr>
	<tr>
	<td width="130"><b>Merchant Number<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="8" maxlength="4" class="TXT" name="merchantID"></td>
	</tr>
	<tr>
	<td width="130"><b>Merchant xID<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="35" class="TXT" name="xid"></td>
	</tr>
	<tr>
	<td width="130"><b>OK Url<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="150" class="TXT" name="okUrl"></td>
	</tr>
	<tr>
	<td width="130"><b>Fail Url<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="150" class="TXT" name="failUrl"></td>
	</tr>
	<tr>
	<td width="130"><b>Merchant Data<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="35" class="TXT" name="MD"></td>
	</tr>
	<tr>
	<td width="130"><b>Digest<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="28" class="TXT" name="digest"></td>
	</tr>
	<tr>
	<td width="130"><b>Card Type<b></td>
	<td width="6"><b>:<b></td>
	<td><select name="cardType">
		<option value="1" selected>Visa</option>
		<option value="2">MasterCard</option>
		<option value="3">Test CT3</option>
	    </select>
	</td>
	</tr>
	<tr>
	<td width="130"><b>Device Category<b></td>
	<td width="6"><b>:<b></td>
	<td><input type="text" size="35" class="TXT" name="deviceCategory"></td>
	</tr>



	</table>
</td></tr>
<tr><td height="30" STYLE="padding-left=320px"><input type="button" class="BTN" name="Doldur" value="Fill" onclick="doldur();"> </td></tr>
<tr><td height="30" STYLE="padding-left=320px"><input type="button" class="BTN" name="ODE" value="Pay" onclick="send();"> </td></tr>
<tr><td>
	<hr color="Red">
        <table border="0" width="450" cellspacing="0" cellpadding="0">
        <tr>
        <td align="right">Copyright � 2006 AKK-PROVUS All rights reserved.<br></td>
        </tr>
        </table>
</td></tr>
</table>

