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
if(isset($_POST['update_product']))
{
  if(!isset($_POST['prod_status']) || !isset($_POST['pc_id']))
  {
    $database->update("product_tbl",
    [
      "prod_name" => trim($_POST['prod_name']),
      "prod_qty" => trim($_POST['prod_qty']),
      "prod_price" => trim($_POST['prod_price']),
      "prod_order_limit" => trim($_POST['prod_order_limit']),
      "prod_desc" => trim($_POST['prod_desc']),
    ],
    ["prod_id" => $_POST['product_id']]
    );
    header("location: company_index.php?success1=OK");
  }
  else {
    $database->update("product_tbl",
    [
      "prod_name" => trim($_POST['prod_name']),
      "prod_qty" => trim($_POST['prod_qty']),
      "prod_price" => trim($_POST['prod_price']),
      "prod_order_limit" => trim($_POST['prod_order_limit']),
      "prod_status" => trim($_POST['prod_status']),
      "prod_desc" => trim($_POST['prod_desc']),
      "pc_id" => trim($_POST['pc_id'])
    ],
    ["prod_id" => $_POST['product_id']]
    );
  }
  header("location: company_index.php?success1=OK");
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
                        <h1 class="animated fadeInLeft">My Product</h1>
                        <p class="animated fadeInDown" >
                          Update Product Information
                        </p>
                    </div>
                </div>
              </div>
              <div class="col-md-12 padding-0">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>Update Product</h3></div>
                      <div class="row" style="padding-top:10px;">
                          <div class="col-md-12">
                            <div class="col-md-8"></div>
                             <div class="col-md-4">
                             <div id="buttons" class="pull-right" style="padding-top:6px;"></div>
                            </div>
                          </div>
                      </div>
                      <div class="panel-body">
                        <form action="update_company_product.php?prod_id=<?php echo $_GET['prod_id']?>" method="POST" id="defaultForm">
                         <div class="row">
                           <?php
                             $data = $database->get("product_tbl", "*",["prod_id" => $_GET['prod_id']]);
                           ?>
                           <input type="hidden" value="<?php echo $data['prod_id']; ?>" name="product_id"/>
                          <div class="col-md-4">
                            <h5>Product Name</h5>
                               <div class="form-group has-feedback">
                                 <input type="text" class="form-control" name="prod_name" placeholder="Product Name" value="<?php echo $data['prod_name']; ?>"/>
                               </div>
                          </div>
                          <div class="col-md-4">
                            <h5>Product Quantity</h5>
                               <div class="form-group has-feedback">
                               <input type="text" class="form-control" name="prod_qty"  value="<?php echo $data['prod_qty']; ?> "/>
                               </div>
                          </div>
                          <div class="col-md-4">
                            <h5>Product Order Limit</h5>
                               <div class="form-group has-feedback">
                               <input type="text" class="form-control" name="prod_order_limit" placeholder="Order Limit" value="<?php echo $data['prod_order_limit'] ?> "/>
                               </div>
                          </div>

                          <div class="col-md-12">
                            <h5>Product Description</h5>
                            <div class="form-group has-feedback">
                            <textarea class="form-control" rows="2" name="prod_desc" placeholder="Company Description(Optional)"></textarea>
                          </div>

                        </div><br>
                          <div class="col-md-4">
                            <h5>Product Price</h5>
                               <div class="form-group has-feedback">
                               <input type="text" class="form-control" name="prod_price" placeholder="Product Price" value="<?php echo $data['prod_price'] ?> "/>
                               </div>
                          </div>
                          <div class="col-md-4">
                            <h5>Product Status</h5>
                               <div class="form-group has-feedback">
                                 <select type="text" class="form-control" name="prod_status">
                                   <?php
                                      $dataSelected = $database -> get("product_tbl",["prod_status"],["prod_id"=>$_GET['prod_id']]);
                                        if($dataSelected['prod_status']==1)
                                        {
                                          ?>
                                            <option value="1" selected disabled>Active</ooption>
                                          <?php
                                        }
                                        elseif($dataSelected['prod_status']==0)
                                        {
                                          ?>
                                            <option value="0" selected disabled>Inactive</ooption>
                                          <?php
                                        }

                                   ?>
                                   <option value="1">Active</ooption>
                                   <option value="0">Inactive</ooption>
                                 </select>
                               </div>
                          </div>
                          <div class="col-md-4">
                            <h5>Product Category</h5>
                               <div class="form-group has-feedback">
                                 <select type="text" class="form-control" name="pc_id">
                                   <?php
                                    $sql = "SELECT a.pc_id , b.pc_name from product_tbl as a inner join product_category_tbl as b on a.pc_id = b.pc_id where a.prod_id =" . $_GET['prod_id'];
                                    $selectedCategories = $database->query($sql)->fetchAll();
                                    foreach($selectedCategories as $selectedCategorie)
                                    {
                                      ?>
                                        <option value="<?php echo $selectedCategorie['pc_id']?>" selected disabled><?php echo $selectedCategorie['pc_name']?></option>
                                      <?php
                                    }
                                    $productCategorys = $database->select("product_category_tbl",["pc_id","pc_name"] );
                                    foreach ($productCategorys AS $productCategory) {
                                      ?>
                                        <option value="<?php echo $productCategory['pc_id']?>"><?php echo $productCategory['pc_name']?></option>
                                      <?php
                                    }
                                   ?>
                                 </select>
                               </div>
                          </div>
                          </div><br><br>
                          <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                    <button class="btn  btn-block btn-success" type="submit" name="update_product">
                                    <span class="glyphicon glyphicon-check"></span>&nbsp; Update Product &nbsp;
                                    </button>
                            </div>
                            <div class="col-md-4">
                            <a href="company_index.php"><button type="button" class="btn btn-default btn-block"><span class="glyphicon glyphicon-chevron-left"> &nbsp;BACK</span></button></a>
                        </div>
                          </div>
                        </div>
                      </form>

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
  $('#defaultForm')
    .bootstrapValidator({
      message: 'This value is invalid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid:'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
              prod_name: {
                    message: 'Product name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Product name is required and can\'t be empty'
                        },
                  stringLength: {
                            min: 1,
                            max: 50,
                            message: 'Product name must be more than 3 and less than 50 characters long'
                        },
                  regexp: {
                            regexp: /^[a-zA-Z 0-9 -]+$/,
                            message: 'Invalid characters'
                        },
                    }
                },
                prod_qty: {
                    message: 'Quantity is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Quantity is required and can\'t be empty'
                        },
                  stringLength: {
                            min: 1,
                            max: 4,
                            message: 'Quantity must be more than 1 and less than 4 characters long'
                        },
                  regexp: {
                            regexp: /^[0-9 ]+$/,
                            message: 'Invalid characters'
                        },
                    }
                },
                prod_order_limit: {
                    message: 'Order limit is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Order limit is required and can\'t be empty.'
                        },
                  stringLength: {
                            min: 1,
                            max: 3,
                            message: 'Order limit must be more than 1 and less than 3 characters long'
                        },
                  regexp: {
                            regexp: /^[0-9 ]+$/,
                            message: 'Invalid characters'
                        },
                    }
                },
                prod_desc: {
                    message: 'Product Description is not valid',
                    validators: {

                  stringLength: {
                            min: 1,
                            max: 150,
                            message: 'Company decription must be more than 1 and less than 150 characters long'
                        },
                  regexp: {
                            regexp: /^[0-9 .,a-zA-Z :;/-]+$/,
                            message: 'Invalid characters'
                        },
                    }
                },
                prod_status: {
                    message: 'Status is not valid',
                    validators: {

                    }
                },
                prod_price: {
                    message: 'Price is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Price is required and can\'t be empty'
                        },

                  stringLength: {
                            min: 1,
                            max: 8,
                            message: 'Price must be 8 digits'
                        },
                  regexp: {
                            regexp: /^[0-9 .]+$/,
                            message: 'Invalid characters! It must be numbers only.'
                        },
                    }
                },
                prod_code: {
                    message: 'Product code is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Product code is required and can\'t be empty'
                        },
                        stringLength: {
                            min: 1,
                            max: 25,
                            message: 'Order limit must be more than 1 and less than 25 characters long'
                        },
                    }
                },
                 pc_id: {
                    message: 'Category is not valid',
                    validators: {

                    }
                },

            }
      })
});
      </script>
</body>
</html>
