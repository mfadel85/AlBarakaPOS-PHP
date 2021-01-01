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
    <!-- <script type="text/javascript" src="jquery.creditCardValidator.js"> </script> -->
</head>

<body>
    <div class="container">
        <?php 
    	$style = "display:none;";
    	if ($_REQUEST['msg'] == 'failed')
    	{
    		$message ="Onay Alınmadı. Lütfen Bilgilerinizi Kontrol Ediniz!! ";
    		$style = "display:blocked;";
    		$errorNo = "53";
    		$errorNo = $_REQUEST['hataKodu'];
    	}
    	?>
        <form class="form-horizontal" id="PostToMPI" action="prepareForm.php" method="Post" name="PostToMPI" role="form">
            <input type="hidden" name="customerID" value="<?php echo $_REQUEST['customer_id']; ?>">
            <input type="hidden" size="8" maxlength="4" class="TXT" name="version" value="2.0">
            <input type="hidden" name="pOrgNo" runat="server" value="006">
            <input type="hidden" name="pFirmNo" runat="server" value="9618947">
            <input type="hidden" name="pTermNo" runat="server" value="00520146">
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
                        <input type="text" class="form-control" pattern="\w+ \w+.*" name="card-holder-name" id="card-holder-name" placeholder="Kart Üzerindeki İsim" required data-required-message="Lütfen işaretli yerleri doldurunuz">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="card-number">Kart Numerası</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="pCardNo" maxlength="19" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="card-number" placeholder="Kart Numarası" required required data-required-message="Lütfen işaretli yerleri doldurunuz">
                    </div>
                    <label class="col-sm-2 control-label" for="cvv">Güvenlik kodu</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="pCVV2" required data-required-message="Lütfen işaretli yerleri doldurunuz" id="cvv" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="cvv" placeholder="Security Code" required>
                    </div>
                </div>
            <div class="form-group">
          
             
               
                <label class="col-sm-3  control-label">Kart Numerası</label>
                
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
                        <input type="text" class="form-control" name="pCVV2" required data-required-message="Lütfen işaretli yerleri doldurunuz" id="cvv" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="cvv" placeholder="Security Code" required>
                    </div>
            </div>
                <input type="hidden" name="pExpDate" runat="server" value="201010">
                <input type="hidden" name="pTaksit" runat="server" value="0">
                <input type="hidden" name="pXid" runat="server" value="00000000000000000005">
                <input type="hidden" name="pMPI3D" runat="server" value="true">
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
                <option value="10">Kasım (10)</option>
                <option value="11">Ekim (11)</option>
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
                <option value="21">2024</option>
                <option value="22">2025</option>
                <option value="23">2026</option>
                <option value="21">2027</option>
                <option value="22">2028</option>
                <option value="23">2029</option>
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
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" id='submit' class="btn btn-success">Ödeyin</button>
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
                                                <td><input type="radio" name="taksit" value="0" checked="checked"></td>
                                                <td>Tüm Bankalar</td>
                                                <td> Peşin fiyatına tek çekim</td>
                                                <td>% 0</td>
                                                <td>1</td>
                                                <td class="taksit"></td>

                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><input type="radio" name="taksit" value="3"></td>
                                                <td>Worldcard Kart </td>
                                                <td>3 Taksit</td>
                                                <td>% 0</td>
                                                <td>3</td>
                                                <td class="taksit"></td>
                                            </tr>


                                            <tr>
                                                <td></td>
                                                <td><input type="radio" name="taksit" value="3"></td>
                                                <td>Türkiye Finans Katılım Bonus </td>
                                                <td>3 Taksit</td>
                                                <td>% 0</td>
                                                <td>3</td>
                                                <td id="axess3taksit" class="taksit"></td>
                                                <td></td>

                                        </table>
                                    </div>
                                </div>
                        </div>
                    </div>
                    </div>
            </fieldset>
        </form>
        </div>

        <script type="text/javascript">
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

                if (total == '' || cardNumber == '' || cvv == '' || expiryMonth == '' || expiryYear == '') {
                    event.preventDefault();
                    alert("Lütfen alanları boş bırakmıyın");
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