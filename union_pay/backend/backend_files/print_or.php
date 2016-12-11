[<?php
date_default_timezone_set("Asia/Manila");
require 'asset/connection/mysqli_dbconnection.php';
if(!isset($_COOKIE["company_id"])) {
  header ("Location: company_login.php");
  exit;
}
  date_default_timezone_set('Asia/Manila');
  $date_today = date('Y-m-d H:i:s');

  $company_id = $_COOKIE["company_id"];

  $companyData = $database->get("company_tbl", "*", ["company_id" => $company_id]);

  $company_name = $companyData['company_name'];
  $company_fname = $companyData['company_fname'];
  $company_mname = $companyData['company_mname'];
  $company_lname = $companyData['company_lname'];
  $company_address = $companyData['company_address'];
  $tin_number = $companyData['tin_number'];
  $company_img = $companyData['company_img'];

  $bai_account_no = $database->get("bank_account_tbl", "bai_account_no", ["bai_id" => $_GET['bai_id']]);
  $bai_account_no2  = substr($bai_account_no, 8);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<style type="text/css" media="print">
    @page
    {
        margin: 0mm;
    }

    html
    {
        background-color: #FFFFFF;
        margin: 0px;
    }

    </style>
  <meta charset="utf-8">
  <meta name="description" content="Miminium Admin Template v.1">
  <meta name="author" content="Isna Nur Azis">
  <meta name="keyword" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MIMINIUM</title>
  <?php
    require ('include_css.php');
  ?>
  <link rel="shortcut icon" href="asset/img/logomi.png">
    <?php
      header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
    ?>
</head>

  <body id="mimin" class="dashboard">
        <nav class="navbar navbar-custom header navbar-fixed-top">
          <div class="col-md-12">
            <div class="navbar-header" style="width:100%;">
              <div class="opener-left-menu is-open">
                <span class="top"></span>
                <span class="middle"></span>
                <span class="bottom"></span>
              </div>
                <a href="company_index.php" class="navbar-brand">
                    <b><?php echo $company_name; ?></b>
                </a>
              <ul class="nav navbar-nav navbar-right user-nav">
                <li class="dropdown avatar-dropdown">
                        <br>
                        <?php
                        $q_ns = $database->query("SELECT COUNT(*) AS notify_status FROM (SELECT notify_status, company_id FROM reservation_tbl AS a LEFT JOIN product_tbl AS b ON a.prod_id=b.prod_id GROUP BY reserve_code) AS group_reservation_tbl WHERE notify_status='unread' AND company_id=$company_id");
                        $count_ns = 0;
                        foreach($q_ns AS $q_ns_data){
                          $count_ns = $q_ns_data["notify_status"];
                        }
                        ?>
                         <span data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                          style="padding:1px 1px 1px 1px;">
                              <i class="glyphicon glyphicon-globe" style="color:blue;font-size:18px;"></i>
                              <label style="font-size:15px;padding:2px 6px 2px 6px;background-color:red;" class="badge"><?php echo $count_ns; ?></label></label></span>
                            <ul class="dropdown-menu user-dropdown">
                              <div style="background-color:#f5f5f0;border-radius:5px;margin-top:2px;">
                                <?php
                                  $sql = "SELECT DISTINCT(a.reserve_code), a.bai_id, b.bai_fname, b.bai_mname, b.bai_lname, b.bai_account_no, a.deliver_id, a.deliver_address FROM reservation_tbl AS a LEFT JOIN bank_account_tbl AS b ON a.bai_id=b.bai_id WHERE a.notify_status = 'unread'";
                                  $q_s = $database->query($sql)->fetchAll();
                                  foreach($q_s AS $q_data){
                                    $bai_id = $q_data["bai_id"];
                                    $bai_fname = $q_data["bai_fname"];
                                    $bai_mname = $q_data["bai_mname"];
                                    $bai_lname = $q_data["bai_lname"];
                                    $bai_account_no = $q_data["bai_account_no"];
                                    $reserve_code = $q_data["reserve_code"];

                                    $full_name = $bai_lname.", ".$bai_fname." ".$bai_mname;

                                    if ($q_data['deliver_id'] == 1) { //delivery
                                      echo "
                                      <li>
                                        <a href='view_reservation.php?reserve_code=$reserve_code&click=1&deliver_id=" . $q_data['deliver_id'] . "&delivery_address=" . $q_data['deliver_address'] . "&type=1'>
                                          <div style='font-size:12px; margin-left:2px; margin-right:2px; margin-top:2px; margin-bottom:2px;'>
                                            <label class='text-primary'>
                                              $full_name &nbsp; </label>has new reserve item(s) with reserve code of ".$reserve_code." for deliver.
                                          </div>
                                        </a>
                                      </li><br>";
                                    } else { //pick up
                                      echo "
                                      <li>
                                        <a href='view_reservation.php?reserve_code=$reserve_code&click=1'>
                                          <div style='font-size:12px; margin-left:2px; margin-right:2px; margin-top:2px; margin-bottom:2px;'>
                                            <label class='text-primary'>
                                              $full_name &nbsp; </label>has new reserve item(s) with reserve code of ".$reserve_code." for pick up.
                                          </div>
                                        </a>
                                      </li><br>";
                                    }
                                  }
                                ?>
                              </div>
                            </ul>
                   </li>
                  <li class="user-name"><span>&nbsp; Hi <?php echo $company_lname; ?> &nbsp;</span></li>
                  <li class="dropdown avatar-dropdown">
                  <br>
                   <span data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="fa fa-reorder" style="padding-right:10px;color:white;"/></span>

                   <ul class="dropdown-menu user-dropdown">
                     <li><a href="company_account.php"><span class="fa fa-user"></span>My Account</a></li>
                      <li><a href="company_logout.php"><span class="fa fa-power-off ">Log Out</span></a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </nav>
   <div class="container-fluid mimin-wrapper">
            <div id="left-menu">
              <div class="sub-left-menu scroll">
                    <div  style="background: linear-gradient(#ebebe0, 50%,#ebebe0);height:90px;">
                      <img src="asset/img/uhaclogo.png" class="animated fadeInLeft" style="width:230px;height:230px;">
                    </div>
                    <div  style="margin-top:-20px;background: linear-gradient(#ebebe0, 50%,#ebebe0);height:210px;">
                    <br>
                       <p class="animated fadeInRight" style="color:gray;margin-left:20px;margin-top:160px;">
                               <?php
                                 echo  date("l, F j, Y");
                               ?>
                        </p>
                    </div>
                      <div class="nav-side-menu">
                        <label>
                          <a href="company_index.php"><i class="glyphicon glyphicon-globe"></i> MY PRODUCTS</a>
                        </label><br>
                        <label>
                          <a href="company_transaction_history.php"><i class="glyphicon glyphicon-globe"></i> TRANSACTION HISTORY</a></label><br>
                        <label  class="active">
                          <a href="company_reservation.php"><i class="glyphicon glyphicon-globe"></i>RESERVATION</a></label><br>
                      </div>
                </div>
            </div>
            <div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h1 class="animated fadeInLeft">PRINT OR</h1>
                        <p class="animated fadeInDown">
                        </p>
                    </div>
                </div>
              </div>
              <div class="col-md-12 padding-0">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>PRINT OFFICIAL RECEIPT</h3></div>
                    <br>
                    <div class="row">
                    	<div class="col-md-12">
                    		<div class="col-md-8"></div>
                    			<?php
		                        if(isset($_GET['confirmation_code'])){
		                          print '<div class="col-md-4" style="display:none;">
		                            <button id="confirmation_code" class="btn btn-info btn-block btn-lg">
		                              <span class="fa fa-exchange"> </span>&nbsp;CONFIRMATION CODE
		                            </button>
		                          </div>';
		                        }else{
		                          print '<div class="col-md-4">
		                            <button id="confirmation_code" class="btn btn-info btn-block btn-lg">
		                              <span class="fa fa-exchange"> </span>&nbsp;CONFIRMATION CODE
		                            </button>
		                          </div>';
		                        }
		                        ?>
		                       </div>
                    	</div>

                      <div class="panel-body" id="print_or_div">
                      <div class="row">
                        <div class="col-md-12">
                        	<div class="col-md-4">
                               <img src="../../<?php echo $company_img; ?>" style="width:120px;height:120px">
                          </div>
                          <div class="col-md-4">
                               <h4 class="text-center"><b><?php echo strtoupper($company_name); ?><b></h4>

                               <h4 class="text-center"><b><?php echo $company_address; ?><b></h4>
                                <h4 class="text-center"><b>TIN : <?php echo $tin_number; ?><b></h4>
                               <br>
                          </div>
                         </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="col-md-6">
                              <?php
                                $accountData = $database->get("bank_account_tbl", [
                                  "bai_fname", "bai_mname", "bai_lname"
                                ]);

                                $accountFullName = $accountData['bai_fname'] . " " . $accountData['bai_mname'] . " " . $accountData['bai_lname'];
                              ?>
                              <h5 class="text-center"><b>CLIENT NAME : <?php echo $accountFullName; ?>
                                <b></h5>
                          </div>
                           <div class="col-md-6">
                              <h5 class="text-center"><b>ACCOUNT NUMBER : <?php echo "********".$bai_account_no2; ?><b></h5>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                           <div class="col-md-6">
                              <?php
                                if (isset($_GET['or_gen'])) {
                                  $reserve_date = $database->get("payment_tbl", "reserve_date", ["reserve_code" => $_GET['reserve_code']]);
                                } else {
                                  $reserve_date = $database->get("reservation_tbl", "reserve_date", ["reserve_code" => $_GET['reserve_code']]);
                                }

                              ?>
                              <h5 class="text-center"><b>DATE RESERVE : <?php echo date("l - F j, Y",strtotime($reserve_date)); ?>
                                <b></h5>
                          </div>
                          <div class="col-md-6">
                              <h5 class="text-center"><b>DATE PAID : <?php echo date("l - F j, Y",strtotime($date_today)); ?>
                                <b></h5>
                          </div>
                        </div>
                      </div>
                      <br>
                        <div class="responsive-table">
                        <table id="datatables" width="100%" cellspacing="0" style="border:0;">
                        <thead style="background-color:">
                          <tr>
                             <th class="text-center">PRODUCT NAME</th>
                             <th class="text-center">QUANTITY</th>
                             <th class="text-center">PRODUCT AMOUNT</th>
                             <th class="text-center">SUB AMOUNT</th>

                          </tr>
                        </thead>
                          <tbody>
                            <?php
                              $total_amount = 0;

                              if (isset($_GET['or_gen'])) {
                                $itemDatas = $database->select("payment_tbl", [
                                  "[>]product_tbl" => ["prod_id" => "prod_id"]
                                ], [
                                  "payment_tbl.reserve_code", "product_tbl.prod_name", "product_tbl.prod_price", "payment_tbl.prod_qty"
                                ], [
                                  "or_no" => $_GET['or_gen']
                                ]);
                              } else {
                                $itemDatas = $database->select("reservation_tbl", [
                                  "[>]product_tbl" => ["prod_id" => "prod_id"]
                                ], [
                                  "reservation_tbl.reserve_code", "product_tbl.prod_name", "product_tbl.prod_price", "reservation_tbl.prod_qty"
                                ], [
                                  "reserve_code" => $_GET['reserve_code']
                                ]);
                              }

                                foreach($itemDatas AS $itemData) {
                                  $prod_total_price = $itemData['prod_price'] * $itemData['prod_qty'];
                                  $total_amount += $prod_total_price;
                            ?>
                            <tr>
                              <td class="text-center"><?php echo $itemData['prod_name']; ?></td>
                              <td class="text-center"><?php echo $itemData['prod_qty']; ?></td>
                              <td class="text-center"><?php echo "P ".number_format($itemData['prod_price'],2); ?></td>
                              <td class="text-center"><?php echo "P ".number_format($prod_total_price,2); ?></td>
                            </tr>
                            <?php
                            }
                            ?>
                          </tbody>
                       </table>
                       </div>
                       <br>
                       <div class="row">
                       		<div class="col-md-12">
                       				<h4 class="text-center"><b>TOTAL</b>
                       					<?php echo "P ".number_format($total_amount,2); ?></b>
                       				</h4>

                       		</div>
                       </div>

                       <div class="row">
                       		<div class="col-md-12">
                       			<h4 class="text-center">
                       				<b><?php echo $company_lname.", ".$company_fname." ".$company_mname; ?></b>
                       			</h4>
                       		</div>
                       </div>

                       <div class="row">
                       		<div class="col-md-12">
                       			<h5 class="text-center"><b>CASHIER NAME</h5>
                       		</div>
                       </div>
                        <div class="row">
                       		<div class="col-md-12">
                       			<div class="col-md-4"></div>
                      		 	 <?php
	                       		   if(isset($_GET['or_gen'])){
	                       		   	$or_gen = $_GET['or_gen'];
	                    			print "<div class='col-md-4'>
	                    						<h4 class='text-center'>".$or_gen."</h4>
			                       		 		<h5 class='text-center'><b>OR NUMBER</b></h5>
			                       		 	</div>";
		                    		}else{
		                    			print "";
		                    		}
                       			?>
                       		</div>
                       	</div>
                       <br>
                       <br>


                     </div>
                     <br>
                     <div class="row">
                       		<div class="col-md-12">
                       			<div class="col-md-4"></div>
                       			<?php
                       			if(isset($_GET['confirmation_code'])){

                    			print '<div class="col-md-4">';
                    			?>

                       		 		<button  onclick='function_pr("print_or_div")'
                       		 			class="btn btn-primary btn-block">
                       		 			<span class="fa fa-print"> </span>&nbsp;PRINT OFFICIAL RECEIPT
                       		 		</button>
                       		 	<?php
                       		 	print '</div>';
	                    		}
	                    		else{
	                    			print "";
	                    		}
	                    		?>
                       		</div>
                       </div>
                       <br>
                  </div>
                </div>
              </div>
            </div>
             <input type="hidden" id="bai_id" value="<?php echo $_GET['bai_id']; ?>">
	         <input type="hidden" id="total_amount" value="<?php echo $total_amount; ?>">
	         <input type="hidden" id="reserve_code" value="<?php echo $_GET['reserve_code']; ?>">
     </div>
      <?php
        require('include_mobilebar.php');
        require('include_javascript.php');
      ?>
</body>

<script type="text/javascript">
function function_pr(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}
 $('#confirmation_code').click(function(){
 	var bai_id = document.getElementById("bai_id").value;
    var total_amount = document.getElementById("total_amount").value;
    var reserve_code = document.getElementById("reserve_code").value;
    swal({
  title: "Confirmation Code",
  text: "Type Confirmation Code:",
  type: "input",
  inputType: "password",
  showCancelButton: true,
  closeOnConfirm: false,
  animation: "slide-from-top",
  inputPlaceholder: "Type confirmation code here..",
  showLoaderOnConfirm: true,
},
  function(inputValue){
    if (inputValue === false) return false;
    if (inputValue === "") {
      swal.showInputError("You need to write something!");
      return false;
    }

    else{
        $.ajax({
          method:'GET',
          url: 'check_confirmation_code.php?confirmation_code='+inputValue+'&bai_id='+bai_id+'&reserve_code='+reserve_code,
          success : function(response){

            if(response=="error"){
              swal.showInputError("Incorrect Code!");

            }else{
              setTimeout(function(){
              	swal({
                      title: "Success!",
                       text: "Trasaction Completed!",
                      type: "success",
                      showCancelButton: false,
                      confirmButtonText: "OK",
                      closeOnConfirm: false,
                      closeOnCancel: false
                    },
                    function(isConfirm){

                      if (isConfirm) {
                       window.location.href="print_or.php?bai_id="+bai_id+'&total_amount='+total_amount+'&reserve_code='+reserve_code+'&confirmation_code="confirmed"'+'&or_gen='+response;
                      }
                    }, 3000);

              });
            }
          }
        });
        return false;
      }
    });
  });
</script>
</html>
