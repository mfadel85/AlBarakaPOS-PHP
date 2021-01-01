<?php
        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
       ?>
<!DOCTYPE html>
<html lang="tr">

<head>
	<meta charset="utf-8">
    <title>Aydın Bilgisayar - 3d ödeme sayfası</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link  href="jquery.fancybox.min.css" rel="stylesheet">
	<script src="jquery.fancybox.min.js"></script>
    <!-- <script type="text/javascript" src="jquery.creditCardValidator.js"> </script> -->
</head>

<body>
    <div class="container">
        <?php 
		include_once "shared/ez_sql_core.php";
		// Include ezSQL database specific component
		include_once "ez_sql_mysqli.php";
    	$style = "display:none;";
    	if ($_REQUEST['msg'] == 'failed')
    	{
    		$message ="Onay Alınmadı. Lütfen Bilgilerinizi Kontrol Ediniz!! ";
    		$style = "display:blocked;";
    		$errorNo = "53";
    		$errorNo = $_REQUEST['hataKodu'];
    	}
    	?>
        <form class="form-horizontal" id="PostToMPI" action="prepareForm.php" method="Post" name="PostToMPI" role="form" accept-charset="UTF-8">
            <input type="hidden" name="customerID" value="<?php echo $_REQUEST['customer_id']; ?>">
            <input type="hidden" size="8" maxlength="4" class="TXT" name="version" value="2.0">

            <fieldset>
                <legend>Ödeme</legend>
                <div class="form-group">
                    <?php 
                	if ( isset($message)  )
                	{
                		echo " <div class='alert alert-danger' role='alert'>
				  					<strong>Onay Alınmadı. Hata Kodu: $errorNo Lütfen Bilgilerinizi Kontrol Ediniz!!</strong>
						</div>";
                	}
                ?>


                    <label class="col-sm-3 control-label" for="total">Tutar</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="pAmount" id="total" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Tutar" required required data-required-message="Lütfen işaretli yerleri doldurunuz">
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="card-holder-name">İsim</label>
                    <div class="col-sm-9">
                    <input type="text" name='card-holder-name' class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" placeholder="Kart Üzerindeki İsim">
                        <!--<input type="text" class="form-control" pattern="" name="card-holder-name" id="card-holder-name" placeholder="Kart Üzerindeki İsim" required data-required-message="Lütfen işaretli yerleri doldurunuz">-->
                    </div>
                </div>

               <div class="form-group">
               <label class="col-sm-3  control-label">Kart Numarası</label>
                
                <div class="col-sm-1">
                  <input type="text" class="input-block-level form-control creditCard" name="pCardNo1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="4" pattern="\d{4}" "required>
                </div>
                <div class="col-sm-1">
                  <input type="text" class="input-block-level form-control creditCard"  name="pCardNo2" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="4" pattern="\d{4}"  required>
                </div>
                <div class="col-sm-1">
                  <input type="text" class="input-block-level form-control creditCard" name="pCardNo3" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="4" pattern="\d{4}"  required>
                </div>
                <div class="col-sm-1">
                  <input type="text" class="input-block-level form-control creditCard" name="pCardNo4" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="4" pattern="\d{4}"  required>
                </div>
                <label class="col-sm-2 control-label" for="cvv">Güvenlik kodu</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="pCVV2" required data-required-message="Lütfen işaretli yerleri doldurunuz" id="cvv" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="cvv" placeholder="Güvenlik kodu" required>
                    </div>
				</div>
                <div class="form-group">

                </div>
                <input type="hidden" name="pXid" runat="server" value="00000000000000000005">
                <input type="hidden" name="pMPI3D" runat="server" value="false">
                <input type="hidden" name="pokUrl" runat="server" value="https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=false">
                <input type="hidden" name="pfailUrl" runat="server" value="https://www.e-tahsildar.com.tr/V2/NetProvOrtakOdeme/ControlFields.aspx?Fail=true">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="expiry-month">Son Kullanma Tarihi </label>
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-xs-3">
                                <select class="form-control col-sm-2" required data-required-message="Lütfen işaretli yerleri doldurunuz" name="expiry-month" id="expiry-month" required>
									<option>Ay</option>
									<option value="01">Ocak (01)</option>
									<option value="02">Şubat (02)</option>
									<option value="03">Mart (03)</option>
									<option value="04">Nissan (04)</option>
									<option value="05">Mayıs (05)</option>
									<option value="06">Haziran (06)</option>
									<option value="07">Temmuz (07)</option>
									<option value="08">Aug (08)</option>
									<option value="09">Eylül (09)</option>
									<option value="10">Ekim (10)</option>
									<option value="11">Kasım (11)</option>
									<option value="12">Aralık (12)</option>
								</select>
							</div>
							<div class="col-xs-3">
							  <select class="form-control" name="expiry-year" id="expiry-year" required>
								<option value="17">2017</option>
								<option value="18">2018</option>
								<option value="19">2019</option>
								<option value="20">2020</option>
								<option value="21">2021</option>
								<option value="22">2022</option>
								<option value="23">2023</option>
								<option value="24">2024</option>
								<option value="25">2025</option>
								<option value="26">2026</option>
								<option value="27">2027</option>
								<option value="28">2028</option>
								<option value="29">2029</option>
							  </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="card-holder-name">Açıklama</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" minlength="5" name="notes" id="card-holder-name" placeholder="Açıklama" required data-required-message="Lütfen işaretli yerleri doldurunuz">
                    </div>
                </div>
                
            </fieldset>
            <fieldset>
                <legend>Taksit Oranı</legend>
                <div class="container">
                    <div class="row">
                        <div class="col-md-2">
                           </div>
                                <div class="col-md-10">
                                    <div class="table-striped">
                                        <table class="table table-striped">
                                            <th>
                                                <td></td>
                                                <td>Banka/Kart</td>
                                                <td>Açıklama</td>
                                                <td>Oran</td>
                                                <td>Taksit Sayısı</td>
                                                <td class="">Taksit Tutarı</td>
                                            </th>						
                                            <tr>
                                                <td></td>
                                                <td><input type="radio" name="taksit" value="1" checked="checked"></td>
                                                <td>Tüm Bankalar</td>
                                                <td> Peşin fiyatına tek çekim</td>
                                                <td>% 0</td>
                                                <td>1</td>
                                                <td class="taksit"></td>

                                            </tr>							                                      <tr >
                                                <td></td>
                                                <td><input type="radio" name="taksit" value="2"></td>
                                                <td><img src="world.png" height='25' width='100'></td>
                                                <td>2 Taksit</td>
                                                <td>% 0</td>
                                                <td>2</td>
                                                <td class="taksit"></td>
                                            </tr>	
                                            <tr >
                                                <td></td>
                                                <td><input type="radio" name="taksit" value="3"></td>
                                                <td><img src="world.png" height='25' width='100'></td>
                                                <td>3 Taksit</td>
                                                <td>% 0</td>
                                                <td>3</td>
                                                <td class="taksit"></td>
                                            </tr>
                                            <tr >
                                                <td></td>
                                                <td><input type="radio" name="taksit" value="5"></td>
                                                <td><img src="world.png" height='25' width='100'></td>
                                                <td>5 Taksit</td>
                                                <td>% 0</td>
                                                <td>5</td>
                                                <td class="taksit"></td>
                                            </tr>
                                            <tr style="display: none;">
                                                <td></td>
                                                <td><input type="radio" name="taksit" value="3F"></td>
                                                <td><img src="finans-kart.jpeg" height='25' width='140'></td>
                                                <td>3 Taksit</td>
                                                <td>% 0</td>
                                                <td>3</td>
                                                <td id="axess3taksit" class="taksit"></td>
                                                <td></td>
											</tr>
											<tr style="display: none;">
                                                <td></td>
                                                <td><input type="radio" name="taksit" value="6F"></td>
                                                <td><img src="finans-kart.jpeg" height='25' width='140'></td>
                                                <td>6 Taksit</td>
                                                <td>% 0</td>
                                                <td>6</td>
                                                <td id="axess3taksit" class="taksit"></td>
                                                <td></td>
											</tr>											
                                        </table>
                                    </div>
									<div class="alert alert-info" role="alert"><h3>Notlar</h3>
									
									1.	Asgari çekim tutarı 50 TL dir <br>
								

                                </div>
                        </div>
                    </div>
                    </div>
					<div class="form-group">
						
					</div>
					<div class="form-group">
                    <label class="col-sm-offset-3 col-sm-5 control-label" for="card-holder-name">
					<input type="checkbox" id="agree" value="1">
					<a data-fancybox data-src="#hidden-content" href="javascript:;" > Kredi kartı ödeme şartları</a>'nı kabul ediyorum</label>
                    <div class="col-sm-9">
					</div>
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" id='submit' class="btn btn-success">Ödeme Yap</button>
                    </div>
                </div>
            </fieldset>
        </form>
        </div>
<div style="display: none; width:800px;" id="hidden-content">
	<h2>Kredi kartı ödeme şartları</h2>
	<p>
Bayi ödeme sistemi ile yapılan normal/3D ödeme işlemlerinde doğacak ihtilaflarda, kullanılan kredi kartının kayıp, çalıntı ya da iptal edilmiş olması vs, dolandırıcılık gibi durumlarda AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ’nin hiçbir sorumluluğu olmadığını, her türlü risk ve sorumluluğun Bayi’ye ait olduğu Bayi tarafından kabul, beyan ve taahhüt edilmiştir. Bu gibi durumlarda oluşacak zararlarla ilgili olarak Bayi hiçbir şekilde AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ’ne rücu edemez, herhangi bir tazminat ve alacak talebinde bulunamaz. Bayi, AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ’nin bu sebeple 3. Kişilere ödemek zorunda kalacağı miktarların, Bayii’nin cari hesabına borç olarak kaydedileceğini, talep halinde nakden ve defaten derhal AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ’ne ödeneceğini kabul beyan ve taahhüt eder.
</p><p>
Bayi ödeme sistemi ile yapılan normal/3D ödeme işlemlerinde, taksitli satışı yasaklı olan mal ve hizmetlerde sorumluluk Bayi’dedir. AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ’nin bu sebeple karşılaşacağı her türlü yaptırım ve tazminat sebebiyle Bayi’ye rücu hakkı saklı olup Bayi bu tür durumlarda tüm sorumluluğun kendisinde olduğunu peşinen kabul beyan ve taahhüt etmiştir
Yasal olarak buradaki ödeme sistemi ile yapılan normal/3D ödeme işlemlerinde fatura kart sahibine Bayi tarafından düzenlenecektir. Buna uyulmadığı takdirde doğacak tüm riskler ve sorumluluk Bayi’dedir. AYDIN Bilgisayar DIŞ TİC. LTD. ŞTİ’nin bu sebeple karşılaşacağı her türlü yaptırım ve tazminat sebebiyle Bayi’ye rücu hakkı saklı olup Bayi bu tür durumlarda tüm sorumluluğun kendisinde olduğunu peşinen kabul beyan ve taahhüt etmiştir. Bayii yukarıdaki şartların bağlayıcılığı ve mahkeme aşamasında delil sayılacağını, bu şartlara uymayı kabul, beyan ve taahhüt eder.</p>
</div>
        <script type="text/javascript">
			$( ".creditCard" ).keyup(function() {
				if($(this).val().length>3)
					$(".creditCard").eq( $(".creditCard").index( $(this) ) + 1 ).focus();
			});
            var total = document.getElementById('total').value;
            //total = parseFloat();
            $("#PostToMPI").submit(function() {
                /*$("#card-number").validateCreditCard(function(result){
        		alert('CC type: ' + result.card_type.name
		          + '\nLength validation: ' + result.length_valid
		          + '\nLuhn validation: ' + result.luhn_valid);
        	});*/

                var total = $("#total").val();
                var cardNumber = $("#card-number").val();
                var cvv = $("#card-number").val();
                var expiryMonth = $("#expiry-month").val();
                var expiryYear = $("#expiry-year").val();
				if (total < 50)
				{
					//event.preventDefault();
                    //alert("Asgari çekim tutarı 50 TL dir");
				}
                if (total == '' || cardNumber == '' || cvv == '' || expiryMonth == '' || expiryYear == '') {
                    event.preventDefault();
                    alert("Lütfen alanları boş bırakmıyın");
                }
					if(!document.getElementById('agree').checked)
				{
					alert(" Uyarı:  Kredi kartı ödeme koşulları'nı kabul etmelisiniz!");
						event.preventDefault();
				}

            });
            /*$('input[required]').on('invalid', function() {
		    this.setCustomValidity($(this).data("required-message"));
		});*/
            $("#total").blur(function() {
                total = this.value;
                totalTL = parseFloat(total.replace(",", "."));
                var usdToTL = 1;
                //console.log(total);
                console.log(totalTL);
                if (total != '') {
                    totalTL = totalTL * usdToTL;
                    $("#totaltl").val(totalTL);
                    $(".taksit").html(totalTL * 1);

                }
            });
        </script>
</body>

</html>