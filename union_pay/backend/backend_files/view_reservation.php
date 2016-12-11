<?php
session_start();
date_default_timezone_set("Asia/Manila");
$date_today =  date("Y-m-d");
require 'asset/connection/mysqli_dbconnection.php';
if(!isset($_COOKIE["company_id"])) {
  header ("Location: company_login.php");
  exit;
}
$company_id = $_COOKIE["company_id"];

if($company_id)
{
    $table = "company_tbl";
    $columns = "*";
    $where = ["company_id" =>$company_id];

    $q_admin_info =$database->select($table,$columns,$where);

    foreach ($q_admin_info as $q_admin_info_data)
    {
          $company_lname = $q_admin_info_data["company_lname"];
          $company_fname = $q_admin_info_data["company_fname"];
          $company_mname = $q_admin_info_data["company_mname"];
          $company_name = $q_admin_info_data["company_name"];
          $company_img = $q_admin_info_data["company_img"];
    }
}

if(isset($_GET['reserve_code'])){
  $reserve_code=$_GET['reserve_code'];
  $q_res = $database->query("SELECT * FROM reservation_tbl where reserve_code = '$reserve_code'");

  foreach($q_res AS $q_res_data){
  $bai_id = $q_res_data["bai_id"];
  $reserve_date = $q_res_data["reserve_date"];

  $q_bai_name = $database->query("SELECT * FROM bank_account_tbl WHERE bai_id = '$bai_id'");
  foreach($q_bai_name as $q_bai_data){
      $bai_fname = $q_bai_data["bai_fname"];
      $bai_mname = $q_bai_data["bai_mname"];
      $bai_lname = $q_bai_data["bai_lname"];
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="description" content="Miminium Admin Template v.1">
  <meta name="author" content="Isna Nur Azis">
  <meta name="keyword" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UMP</title>
  <?php
    require ('include_css.php');
  ?>
  <link rel="shortcut icon" href="asset/img/htlogo.png">
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
                      <img src="../../<?php echo $company_img; ?>" class="animated fadeInLeft" style="width:230px;height:230px;">
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
                          <a href="company_index.php"><i class="glyphicon glyphicon-list-alt"></i> MY PRODUCTS</a>
                        </label><br>
                        <label>
                          <a href="company_transaction_history.php"><i class="glyphicon glyphicon-th-list"></i> TRANSACTION HISTORY</a>
                          </label><br>
                        <label class="active">
                          <a href="company_reservation.php"><i class="glyphicon glyphicon-menu-hamburger"></i>RESERVATION</a>
                        </label><br>
                      </div>
                </div>
            </div>
            <div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h1 class="animated fadeInLeft">COMPANY RESERVATION</h1>
                        <p class="animated fadeInDown" >
                          This section will show the company reservation.
                        </p>
                    </div>
                </div>
              </div>
              <div class="col-md-12 padding-0">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>VIEW RESERVATION PRODUCT(S)</h3></div>
                      <div class="row" style="padding-top:10px;">
                          <div class="col-md-12">
                            
                          </div>
                      </div>
                      <div class="panel-body">

                      <div class="row">
                        <div class="col-md-12" style="background-color:#fb8c00;">
                          <div class="col-md-5">
                              <h4 style="color:white;"><b>CLIENT NAME : <?php echo $bai_fname.", ".$bai_fname." ".$bai_mname; ?>
                                <b></h4>
                          </div>
                           <div class="col-md-5">
                              <h4 style="color:white;"><b>DATE RESERVE : <?php echo date("l - F j, Y",strtotime($reserve_date)); ?>
                                <b></h4>
                          </div>
                        </div>
                      </div>
                          <div class="row" >
                          </br>
                              <div class="col-md-2">
                              <a href="send_request.php?bai_id=<?php echo $bai_id; ?>" class="btn btn-primary"> SEND SMS REQUEST </a>
                              </div>
                              <div class="col-md-12">

                                <?php
                            if(isset($_GET['type']) && isset($_GET['delivery_address']) && isset($_GET['click']) && isset($_GET['reserve_code'])){
                              $deliver_address = $_GET['delivery_address'];
                              $reserve_code = $_GET['reserve_code'];
                              $table = "reservation_tbl";
                              $columns = ["notify_status"=>'read'];
                              $where = ["reserve_code"=>$reserve_code];
                              $q_sas = $database->update($table,$columns,$where);
                              print '<div class="col-md-5">
                                      <h4><b>
                                         TYPE :
                                        <span class="fa fa-truck"></span>&nbsp;For Deliver</h4>
                                        </b>
                                    </div>
                                    <div class="col-md-6">
                                      <h4><b>
                                         ADDRESS :
                                        <span class="icons icon-home"></span>&nbsp;'.$deliver_address.'</h4>
                                        </b>
                                    </div>';
                            }elseif(isset($_GET['click']) && isset($_GET['reserve_code'])){
                               
                              $reserve_code = $_GET['reserve_code'];
                              $table = "reservation_tbl";
                              $columns = ["notify_status"=>'read'];
                              $where = ["reserve_code"=>$reserve_code];
                              $q_sas = $database->update($table,$columns,$where);
                                print '<br><div class="col-md-4 pull-right">
                                <button class="btn btn-primary btn-block btn-lg" id="check_cob">
                                  <span class="fa fa-check-square"> </span> PROCEED TO PAYMENT
                                </button>
                            </div>';
                            } 
                            
                            ?>

                              </div>
                            </div>
                      <br>
                        <div class="responsive-table">
                        <table id="datatables" class="table table-bordered table-hover table-condensed table-reflow" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                             <th class="text-center">PRODUCT NAME</th>
                             <th class="text-center">QUANTITY</th>
                             <th class="text-center">PRODUCT AMOUNT</th>
                             <th class="text-center">TOTAL AMOUNT</th>

                          </tr>
                        </thead>
                          <tbody>
                            <?php
                              $total_amount = 0;
                              $q_res = $database->query("SELECT * FROM reservation_tbl where reserve_code = '$reserve_code'");

                                foreach($q_res AS $q_res_data){
                                $reserve_code = $q_res_data["reserve_code"];
                                $prod_id = $q_res_data["prod_id"];
                                $bai_id = $q_res_data["bai_id"];
                                $prod_qty = $q_res_data["prod_qty"];

                                $q_qs=$database->query("SELECT * FROM product_tbl WHERE prod_id = '$prod_id'");
                                foreach($q_qs AS $q_qs_data){
                                    $prod_name = $q_qs_data["prod_name"];
                                    $prod_price = $q_qs_data["prod_price"];
                                    $total_amount += $prod_total_price = ($q_qs_data["prod_price"] * $prod_qty);
                            ?>
                            <tr>
                              <td><?php echo $prod_name; ?></td>
                              <td><?php echo $prod_qty; ?></td>
                              <td><?php echo "₱ ".number_format($prod_price,2); ?></td>
                              <td><?php echo "₱ ".number_format($prod_total_price,2); ?></td>

                            </tr>
                            <?php
                            }
                          }
                            ?>
                          </tbody>
                       </table>
                       <br>
                       <div class="row">
                        <div class="col-md-12">
                          <div class="col-md-8">
                          </div>
                          <div class="col-md-4">
                            <a href="company_reservation.php" class="btn btn-default btn-block btn-lg"><span class="icons icon-action-undo"></span>&nbsp;Back</a>
                          </div>
                        </div>
                       </div>
                       </div>
                     </div>
                     <input type="hidden" id="bai_id" value="<?php echo $bai_id; ?>">
                     <input type="hidden" id="total_amount" value="<?php echo $total_amount; ?>">
                     <input type="hidden" id="reserve_code" value="<?php echo $reserve_code; ?>">
                  </div>
                </div>
              </div>
            </div>
     </div>
      <?php
        require('include_mobilebar.php');
        require('include_javascript.php');
      ?>
</body>

<script type="text/javascript">

 $(document).ready(function(){
  $('#datatables').DataTable();
  var table = $('#datatables').DataTable();
  $('#check_cob').click(function(){
    var bai_id = document.getElementById("bai_id").value;
    var total_amount = document.getElementById("total_amount").value;
    var reserve_code = document.getElementById("reserve_code").value;
    swal({
    title: "Check Account",
    text: "click ok to check the account of the customer",
    type: "info",
    showCancelButton: true,
    closeOnConfirm: false,
    showLoaderOnConfirm: true,

    },
    function(){
        $.ajax({
          method:'GET',
          url: 'check_account_process.php?bai_id='+bai_id+'&total_amount='+total_amount,
          success : function(response){
            if(response=="error"){
              swal.showInputError("Insufficient Balance!");
            }else{
              setTimeout(function(){
                 window.location.href="print_or.php?bai_id="+bai_id+'&total_amount='+total_amount+'&reserve_code='+reserve_code;
              }, 1200);
            }
          }
        });
        return false;
    });
  });
});
  </script>
</html>
