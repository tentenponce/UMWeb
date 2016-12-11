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
                     <li><a href="company_account.php?company_id=<?php echo $_COOKIE['company_id']?>"><span class="fa fa-user"></span>My Account</a></li>
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
                        <label class="active">
                          <a href="company_index.php"><i class="glyphicon glyphicon-list-alt"></i> MY PRODUCTS</a>
                        </label><br>
                        <label>
                          <a href="company_transaction_history.php"><i class="glyphicon glyphicon-th-list"></i> TRANSACTION HISTORY</a>
                          </label><br>
                        <label>
                          <a href="company_reservation.php"><i class="glyphicon glyphicon-menu-hamburger"></i>RESERVATION</a>
                        </label><br>
                      </div>
                </div>
            </div>

            <div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h1 class="animated fadeInLeft">MY PRODUCTS</h1>
                        <p class="animated fadeInDown" >
                          This section will show the products information.
                        </p>
                    </div>
                </div>
              </div>
              <div class="col-md-12 padding-0">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>MY PRODUCTS</h3></div>
                    <?php
                      if(isset($_GET['success']))
                      {
                        print '<div class="row">
                                <div class="col-md-12">
                                  <div class="col-md-12" id="success">
                                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                                      <i class="glyphicon fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">×</span></button>
                                      <strong>Sucessfully Added!</strong> Click the company info on the sidebar to view the information.
                                    </div>
                                  </div>
                                </div>
                              </div>';
                        }
                        if(isset($_GET['success1']))
                      {
                        print '<div class="row">
                                <div class="col-md-12">
                                  <div class="col-md-12" id="success">
                                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                                      <i class="glyphicon fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">×</span></button>
                                      <strong>Sucessfully Updated!</strong> Click the company info on the sidebar to view the information.
                                    </div>
                                  </div>
                                </div>
                              </div>';
                        }
                        ?>
                      <div class="row" style="padding-top:10px;">
                          <div class="col-md-12">
                            <div class="col-md-2">
                                <a href="add_company_product.php">
                                  <button type="button" class="btn btn-outline btn-block btn-success">
                                  <span class="glyphicon glyphicon-plus"></span>&nbsp; ADD PRODUCT &nbsp;
                                  </button>
                                </a>
                            </div>
                            <div class="col-md-6"></div>
                             <div class="col-md-4">
                             <div id="buttons" class="pull-right" style="padding-top:6px;"></div>
                            </div>
                          </div>
                      </div>
                      <div class="panel-body">
                        <div class="responsive-table">
                        <table id="datatables" class="table table-bordered table-hover table-condensed table-reflow" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                             <th class="text-center">PRODUCT ID</th>
                            <th class="text-center">PRODUCT NAME</th>
                            <th class="text-center">QUANTITY</th>
                            <th class="text-center">ORDER LIMIT</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">PRICE</th>
                            <th class="text-center">CODE</th>
                            <th class="text-center">CATEGORY</th>
                            <th class="text-center">ACTION</th>
                          </tr>
                        </thead>
                          <tbody>
                            <?php
                              $table = "product_tbl";
                              $columns = "*";
                              $where = ["company_id" => $company_id];
                              $q_product = $database->select($table,$columns,$where);

                            foreach($q_product AS $q_product_data){
                                $prod_id = $q_product_data["prod_id"];
                                $prod_name = $q_product_data["prod_name"];
                                $prod_qty = $q_product_data["prod_qty"];
                                $prod_order_limit = $q_product_data["prod_order_limit"];
                                $prod_status = $q_product_data["prod_status"];
                                $prod_price = $q_product_data["prod_price"];
                                $prod_code = $q_product_data["prod_code"];
                                $pc_id = $q_product_data["pc_id"];

                                $table = "product_category_tbl";
                                $columns = "*";
                                $where = ["pc_id" => $pc_id];
                                $q_pc = $database->select($table,$columns,$where);

                                foreach($q_pc AS $q_pc_data){
                                  $pc_name = $q_pc_data["pc_name"];
                            ?>
                            <?php
                              if($prod_status==1){
                                $status = 'Available';
                              }
                              else{
                                $status = 'Not Available';
                              }
                             ?>
                            <tr>
                              <td><?php echo $prod_id; ?></td>
                              <td><?php echo $prod_name; ?></td>
                              <td><?php echo $prod_qty; ?></td>
                              <td><?php echo $prod_order_limit; ?></td>
                              <td><?php echo $status; ?></td>
                              <td><?php echo "₱".number_format($prod_price,2); ?></td>
                              <td><?php echo $prod_code; ?></td>
                              <td><?php echo $pc_name; ?></td>
                              <td>
                                <a href="update_company_product.php?prod_id=<?php echo $prod_id; ?>" class="btn btn-outline btn-block btn-info">
                                  <span class="glyphicon glyphicon-pencil"></span> Update
                                </a>
                              </td>

                            </tr>
                            <?php
                            }
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
                title: 'My Products',
                text: '<i class="glyphicon glyphicon-save-file"></i> EXCEL',
                className: 'btn btn-info btn-outline',
                exportOptions: {
                    columns: [1,2,3,4,5,6,7]
                }
            },
            {
                extend: 'print',
                title: 'My Products',
                text: '<i class="glyphicon glyphicon-print"></i> PRINT',
                 className: 'btn btn-info btn-outline',
                exportOptions: {
                    columns:  [1,2,3,4,5,6,7]
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
