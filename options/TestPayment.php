<!DOCTYPE html>
<html lang="tr">
<head>

	<meta http-equiv="Content-Language" content="tr">
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">
	<title>Ödeme Sayfası</title>
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="now">
</head>
		<body>
		<form id="PostToMPI" name="PostToMPI" method="post" action="handleForm.php">
					<input type="hidden" name="pOrgNo" runat="server" value="006"> 
					<input type="hidden" name="pFirmNo"  runat="server" value="9618947"> 
					<input type="hidden" name="pTermNo" runat="server" value="00520146">
					<input type="hidden" name="pCardNo" runat="server" value="4543140281037761">
					<input type="hidden" name="pCVV2" runat="server" value="435">
					<input type="hidden" name="pExpDate" runat="server" value="201902">
					<input type="hidden" name="pAmount" runat="server" value="1">
					<input type="hidden" name="pTaksit" runat="server" value="0">
					<input type="hidden" name="pXid" runat="server" value="00000000000000000005">
					<input type="hidden" name="pokUrl" runat="server" value="https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=false">
					<input type="hidden" name="pfailUrl" runat="server" value="https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=true">
					<input type="hidden" name="pMPI3D" runat="server" value="true">

					<input type="submit" name="Submit" value="Submit">
		</form>
</body>
</html>



