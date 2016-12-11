<?php
session_start();
date_default_timezone_set("Asia/Manila");
$date_today =  date("Y-m-d");
require 'asset/connection/mysqli_dbconnection.php';
if(!isset($_COOKIE["user_id"])) {
  header ("Location: login.php");
  exit;
}
$user_id = $_COOKIE["user_id"];
$usertype = $_COOKIE["user_type"];
$company_id = $_GET["company_id"];
if($usertype == 1)
{
    $table = "admin_tbl";
    $columns = "*";
    $where = ["user_id" =>$user_id];

    $q_admin_info =$database->select($table,$columns,$where);

    foreach ($q_admin_info as $q_admin_info_data)
    {
          $lname = $q_admin_info_data["lname"];
          $fname = $q_admin_info_data["fname"];
          $mname = $q_admin_info_data["mname"];

    }
      $full_name = $lname.", ".$fname." ".$mname;
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
  <title>PMU</title>
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
                <a href="index.php" class="navbar-brand">
                    <b>Union Mobile Pay</b>
                </a>
              <ul class="nav navbar-nav navbar-right user-nav">
                <li class="dropdown avatar-dropdown">
                        <br>
                        <?php
                        $q_ns = $database->query("SELECT COUNT(notify_status) as notify_status FROM bank_account_tbl WHERE notify_status='unread'");
                        foreach($q_ns AS $q_ns_data){
                          $count_ns = $q_ns_data["notify_status"];
                        }
                        ?>
                         <span data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                          style="padding:1px 1px 1px 1px;">
                              <i class="glyphicon glyphicon-globe" style="color:blue;font-size:18px;"></i>
                              <label style="font-size:15px;padding:2px 6px 2px 6px;background-color:red;" class="badge">
                                <?php echo $count_ns; ?></label></span>
                                <ul class="dropdown-menu user-dropdown">';
                                  <div style="background-color:#f5f5f0;border-radius:5px;margin-top:2px;">
                                      <?php
                                      $table = "company_tbl";
                                      $columns = "*";
                                      $where = ["notify_status"=>'unread'];
                                      $q_s = $database->select($table,$columns,$where);
                                      foreach($q_s AS $q_data){

                                        $company_idx = $q_data["company_id"];
                                        $company_fname = $q_data["company_fname"];
                                        $company_mname = $q_data["company_mname"];
                                        $company_lname = $q_data["company_lname"];
                                        $company_account_no = $q_data["company_account_no"];

                                        $full_name = $company_lname.", ".$company_fname." ".$company_mname;
                                      ?>
                                      <li>
                                        <a href="update_bank_info.php?company_id=<?php echo $company_id; ?>&click=1">
                                          <div style="font-size:12px; margin-left:2px; margin-right:2px; margin-top:2px; margin-bottom:2px;">
                                              <label class="text-primary"><?php echo $full_name ;?> was locked the account with &nbsp; </label>with account number of
                                              <br> <?php echo $company_account_no; ?>
                                         </div>
                                      </a>
                                      </li>
                                      <?php
                                      }
                                      ?>
                                  </div>
                              </ul>
                   </li>
                  <li class="user-name"><span>&nbsp; Hi' Admin <?php echo $lname; ?>&nbsp;</span></li>
                  <li class="dropdown avatar-dropdown">
                  <br>
                   <span data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="fa fa-reorder" style="padding-right:10px;color:white;"/></span>

                   <ul class="dropdown-menu user-dropdown">
                     <li><a href="update_admin.php"><span class="fa fa-user"></span> My Account</a></li>
                      <li><a href="logout.php"><span class="fa fa-power-off "> Log Out</span></a></li>
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
                           <img src="asset/img/htlogo.png" class="animated fadeInLeft" style="width:230px;height:230px;">
                         </div>
                         <div  style="margin-top:-20px;background: linear-gradient(#ebebe0, 50%,#ebebe0);height:210px;">
                         <br>
                            <p class="animated fadeInRight" style="color:gray;margin-left:20px;margin-top:160px;">
                               <?php
                                 //echo  date("l, F j, Y");
                               ?>
                        </p>
                    </div>
                    <div class="nav-side-menu">
                      <label class="">
                        <a href="index.php"><i class="glyphicon glyphicon-globe"></i>BANK ACCOUNT</a>
                      </label><br>
                      <label class="active">
                        <a href="partner_company.php"><i class="fa fa-building-o"></i>PARTNER COMPANIES</a>
                      </label><br>
                    </div>
                </div>
            </div>

            <div id="content">
               <div class="panel box-shadow-none content-header">

                  <div class="panel-body">
                    <div class="col-md-12">
                        <h1 class="animated fadeInLeft">COMPANY TRANSACTION HISTORY</h1>
                        <p class="animated fadeInDown" >
                          This section will show the transaction history of an account holder.
                        </p>
                    </div>
                </div>
              </div>
              <div class="col-md-12 padding-0">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>Account Transaction History</h3></div>
                      <div class="row" style="padding-top:10px;">
                          <div class="col-md-12">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-3 pull-right">
                            </div>

                          </div>
                      </div>
                      <div class="panel-body">

                      <div class="row">
                        <div class="col-md-12" style="background-color:#fb8c00;">
                          <div class="col-md-4">
                              <!-- <h5 style="color:white;font-size:14px;"><b>CLIENT NAME : <?php //echo $company_fname.", ".$company_fname." ".$company_mname; ?>
                                <b></h5> -->
                          </div>
                           <div class="col-md-4">
                              <!-- <h5 style="color:white;font-size:14px;"><b>DATE RESERVED : <?php //echo date("l - F j, Y",strtotime($reserve_date)); ?>
                                <b></h5> -->
                          </div>
                          <div class="col-md-4">
                              <!-- <h5 style="color:white;font-size:14px;"><b>DATE PAYMENT : <?php //echo date("l - F j, Y",strtotime($payment_date)); ?>
                              <b></h5> -->
                          </div>
                        </div>
                      </div>
                      <br>
                      <div class="panel-heading"><h3>Company Account History</h3></div>
                        <div class="row" style="padding-top:10px;">
                            <div class="col-md-12">
                              <div class="col-md-2">
                                  <a href="add_bank_account_info.php">
                                    <!-- <button type="button" class="btn btn-outline btn-block btn-success">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp; ADD COMPANY &nbsp;
                                    </button> -->
                                  </a>
                              </div>
                              <div class="col-md-6"></div>
                               <div class="col-md-4">
                               <div id="buttons" class="pull-right" style="padding-top:6px;"></div>
                              </div>
                            </div>
                        </div>
                        <br>
                        <div class="responsive-table">
                        <table id="datatables" class="table table-bordered table-hover table-condensed table-reflow" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                             <th class="text-center">DATE</th>
                             <th class="text-center">O.R.</th>
                             <th class="text-center">Total</th>
                             <!-- <th class="text-center">COMPANY ID</th> -->
                          </tr>
                        </thead>
                          <tbody>
                            <?php
                              // $query = "SELECT * FROM payment_tbl as a LEFT JOIN product_tbl as b ON a.prod_id=b.prod_id LEFT JOIN company_tbl as c ON b.company_id=c.company_id WHERE company_id=$company_id";
                              //
                              // $a_ah = $database->query($query)->fetchAll();
                              //   foreach($a_ah as $a_ah_data){
                              //     $payment_date = $a_ah_data["payment_date"];
                              //     $or_no = $a_ah_data["or_no"];
                              //     $prod_id = $a_ah_data["prod_id"];
                              //     $prod_price = $database->get("product_tbl","prod_price",["prod_id"=>$prod_id]);
                              //     $prod_qty = $a_ah_data["prod_qty"];
                              //     $prod_total = $prod_price * $prod_qty;
                              //     // $companyID = $database->query("SELECT company_tbl.company_id FROM company_tbl LEFT JOIN product_tbl ON company_tbl.prod_id=product_tbl.prod_id WHERE product_tbl.prod_id=$prod_id")->fetchAll();
                            ?>
                            <tr>
                              <!-- <td><?php //echo $payment_date; ?></td>
                              <td><?php //echo $or_no; ?></td>
                              <td><?php //echo $prod_total; ?></td>
                              <!-- <td><?php //echo $companyTW; ?></td> --> -->

                            </tr>
                            <?php
                          // }

                            ?>
                          </tbody>
                       </table>
                         <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                          <a href="partner_company.php"><button type="button" class="btn btn-default btn-block"><span class="glyphicon glyphicon-chevron-left"> &nbsp;BACK</span></button></a>
                        </div>
                       </div>
                       </div>
                     </div>
                     <input type="hidden" id="company_id" value="<?php //echo $company_id; ?>">
                     <input type="hidden" id="total_amount" value="<?php //echo $total_amount; ?>">
                     <input type="hidden" id="reserve_code" value="<?php //echo $reserve_code; ?>">
                  </div>
                </div>
              </div>
            </div>
     </div>
     <?php
       require('include_mobilebar.php');
       require('include_javascript.php');
     ?>
<script type="text/javascript">
 $(document).ready(function(){
 var table = $('#datatables').DataTable();
var buttons = new $.fn.dataTable.Buttons(table, {

    buttons: [
           {
               extend: 'excelHtml5',
               title: 'COMPANY TRANS HISTORY INFO',
               text: '<i class="glyphicon glyphicon-save-file"></i> EXCEL',
               className: 'btn btn-info btn-outline',
               exportOptions: {
                   columns: [0,1,2,3,4,5]
               }
           },
           {
               extend: 'print',
               title: 'COMPANY TRANS HISTORY INFO',
               text: '<i class="glyphicon glyphicon-print"></i> PRINT',
                className: 'btn btn-info btn-outline',
               exportOptions: {
                   columns:  [0,1,2,3,4,5]
               }
           },
           {
             extend:'colvis',
             text: '<i class="fa fa-eye"></i> VISIBILITY',
             className: 'btn btn-info btn-outline'
           }
       ]
   }).container().appendTo($('#buttons'));
 });
</script>
</body>
</html>
