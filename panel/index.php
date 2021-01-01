<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ödeme Paneli</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
	<?Php
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		include_once "../shared/ez_sql_core.php";
		// Include ezSQL database specific component
		include_once "../ez_sql_mysqli.php";
		$db  = new ezSQL_mysqli('aydimtr1_payment','D1IeUhY9B','aydimtr1_payment','localhost','UTF-8');
		$db2 = new ezSQL_mysqli('aydimtr1_guven','p33hrvYea','aydimtr1_guven','localhost','UTF-8');


	?>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="#" class="site_title"><i class="fa fa-paw"></i> <span>AYDIN Bilgisayar!</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="images/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Hoş Geldiniz,</span>
                <h2>AYDIN Bilgisayar</h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
				      <li>
					     <a><i class="fa fa-table"></i> Albarka Ödemeleri <span class="fa fa-chevron-down"></span></a>
				      </li>				  
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small" style="display:none;">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>


            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Ödemeler <small>En Son Ödemeler</small></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

			  
			      <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Albaraka Ödemeler <small>Albarka</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30"></p>
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr >
                          <th>No</th>
                          <th>İşlem No</th>
                          <th>KK. No</th>
                          <th>Onay Kodu</th>
                          <th>Provision NO</th>
                          <th>Çekim Yapan Firma</th>
                          <th>Tarih</th>
                          <th>Banka</th>
            						  <th>Taksit Sayısı</th>
            						  <th>Tutar</th>
            						  <th>Açıklama</th>
            						  <th>Çıktı al</th>
                        </tr>
                      </thead>
                      <tbody>
						<?php 
							$i = 0;
							$odemeler = $db->get_results("SELECT id,CustomerID, taksit, creditCard,ePosRef, cardHolder, banka,OnayKodu,amount,date_modified FROM  `payment` where completed = 1 and banka = 'Albaraka' order by date_modified desc");
							foreach ( $odemeler as $odeme )
							{
								//echo $odeme->cardHolder;
								$company = $db2->get_var("select custom_field from oc_customer where customer_id = $odeme->CustomerID");
								//$data = "123_String";    
								$company = substr($company, strpos($company , '1;s:18:"') + 8); 
								$arr = explode("\"", $company, 2);
								$company =  $arr[0];
								$cardType = '';
								if ($odeme->taksit > 1 && $odeme->banka == 'Albaraka' )
									$cardType = 'Worldcard';
								else if ($odeme->taksit > 1 && $odeme->banka == 'Finans')
									$cardType = "Finans Bonus";
								else
									$cardType = "Kredit Kart";
								$i += 1;
							?>
						<tr>
                          <td><?php echo $i;?></td>
                          <td><?php  echo $odeme->id; ?></td>
                          <td><?php  echo $odeme->creditCard; ?></td>
                          <td><?php  echo $odeme->OnayKodu; ?></td>
                          <td><?php  echo $odeme->ePosRef; ?></td>

                          <td><?php echo $company;?></td>
						  <td><?php  echo $odeme->date_modified; ?></td>

						  <td><?php  echo $odeme->banka; ?></td>
						  <td>
							<?php 
							if($odeme->taksit == 1)
							{
								echo "Tek Çekim"; 
								$takistExperssion = "Tek Çekim";
							}
							else
							{
								
								echo $odeme->taksit;
								$takistExperssion = $odeme->taksit." Takist ";
							}
							?>
							</td>
						  <td><?php  
						  echo $odeme->amount; ?> TL</td>
						  <?php
								$description = "$cardType - $odeme->creditCard  - $takistExperssion - ".$odeme->cardHolder." - RN:$odeme->ePosRef - OK: $odeme->OnayKodu";

						  ?>
						  <td><?php echo $description;?></td>
						  <td><button><span class=" fa fa-print">&nbsp;&nbsp;&nbsp;</span></button></td>


                        </tr>
							<?
							}
						?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>

  </body>
</html>