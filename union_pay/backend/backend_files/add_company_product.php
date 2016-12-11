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
                         <span data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                          style="padding:1px 1px 1px 1px;">
                              <i class="glyphicon glyphicon-globe" style="color:blue;font-size:18px;"></i>
                              <label style="font-size:15px;padding:2px 6px 2px 6px;background-color:red;" class="badge">1</label></span>
                            <ul class="dropdown-menu user-dropdown">';
                                  <div style="background-color:#f5f5f0;border-radius:5px;margin-top:2px;">
                                      <li>
                                        <a href="#">
                                          <div style="font-size:12px; margin-left:2px; margin-right:2px; margin-top:2px; margin-bottom:2px;">
                                              <label class="text-primary">MIMINIUM</label>MIMINIUM
                                         </div>
                                      </a>
                                      </li>
                                  </div>
                              </ul>
                   </li>
                  <li class="user-name"><span>&nbsp; Hi <?php echo $company_lname; ?> &nbsp;</span></li>
                  <li class="dropdown avatar-dropdown">
                  <br>
                   <span data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="fa fa-reorder" style="padding-right:10px;color:white;"/></span>

                   <ul class="dropdown-menu user-dropdown">
                     <li><a href="#"><span class="fa fa-user"></span>My Account</a></li>
                      <li><a href="logout.php"><span class="fa fa-power-off ">Log Out</span></a></li>
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
                        <h1 class="animated fadeInLeft">ADD NEW PRODUCT</h1>
                        <p class="animated fadeInDown" >
                         > This section will show adding a new product.
                        </p>
                    </div>
                </div>
              </div>
              <div class="col-md-12 padding-0">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>Add New Product</h3></div>
                      <div class="panel-body">
                        <form action="add_company_product_process.php" method="POST" id="defaultForm">
                          <input type="hidden" name="company_id" value="<?php echo $company_id; ?>"/>
                         <div class="row">
                          <div class="col-md-5">
                            <h5>Product Name</h5>
                               <div class="form-group has-feedback">
                               <input type="text" class="form-control" name="prod_name" placeholder="Product Name"/>
                               </div>
                          </div>
                          <div class="col-md-2">
                            <h5>Quantity</h5>
                               <div class="form-group has-feedback">
                               <input type="text" class="form-control" name="prod_qty" placeholder="Quantity"/>
                               </div>
                          </div>
                          <div class="col-md-2">
                            <h5>Order Limit</h5>
                               <div class="form-group has-feedback">
                               <input type="text" class="form-control" name="prod_order_limit" placeholder="Order Limit"/>
                               </div>
                          </div>
                          <div class="col-md-3">
                            <h5>Status</h5>
                            <div class="form-group has-feedback">
                              <select class="form-control" name="prod_status" id="prod_status">
                                <option></option>
                                <option value="1">Available</option>
                                <option value="0">Not Available</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <h5>Product Description</h5>
                            <div class="form-group has-feedback">
                            <textarea class="form-control" rows="2" name="prod_desc" placeholder="Company Description(Optional)"></textarea>
                          </div>
                          </div>
                        </div><br>
                        <div class="row">
                          <div class="col-md-2">
                            <h5>Price</h5>
                               <div class="form-group has-feedback">
                               <input type="text" class="form-control" name="prod_price" placeholder="Price"/>
                               </div>
                          </div>
                          <div class="col-md-3">
                            <h5>Product Code</h5>
                            <div class="form-group has-feedback">
                              <input type="text" class="form-control" name="prod_code" placeholder="Product Code"/>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <h5>Category</h5>
                            <div class="form-group has-feedback">
                              <select class="form-control" name="pc_id">
                                <option disabled selected></option>
                                <option value="1">Main</option>
                                <option value="2">Sides</option>
                                <option value="3">Desserts</option>
                                <option value="4">Drinks</option>
                                <option value="5">Foods</option>
                                <option value="6">Clothes</option>
                                <option value="7">Beverages</option>
                              </select>
                            </div>
                          </div>
                        </div><br><br>
                          <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                    <button class="btn btn-block btn-success" type="submit" name="button_add">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp; Add &nbsp;
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
                        notEmpty: {
                            message: 'Status is required and can\'t be empty'
                        },
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
                            max: 5,
                            message: 'Price must be 5 digits'
                        },
                  regexp: {
                            regexp: /^[0-9]+$/,
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
                        notEmpty: {
                            message: 'Category is required and can\'t be empty'
                        },
                    }
                }, 
                    
            }
      })
});
</script>
</body>
</html>
