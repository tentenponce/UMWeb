<?php
ob_start("ob_gzhandler");

date_default_timezone_set("Asia/Manila");
$date_today =  date("Y-m-d");
$date2=rand(00000000,99999999);
$account_no = '0001'.$date2;
require 'asset/connection/mysqli_dbconnection.php';
if(!isset($_COOKIE["user_id"])) {
  header ("Location: login.php");
  exit;
}
$user_id = $_COOKIE["user_id"];
$usertype = $_COOKIE["user_type"];

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
  <link rel="shortcut icon" href="asset/img/uhaclogo.png">
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
                    <b>Union Mobile Payment</b>
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
                  <li class="user-name"><span>&nbsp; Hi' <?php echo $fname; ?> &nbsp;</span></li>
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
                        <label class="active">
                          <a href="index.php"><i class="glyphicon glyphicon-globe"></i>BANK ACCOUNT</a>
                        </label><br>
                        <label>
                          <a href="partner_company.php"><i class="fa fa-building-o"></i>PARTNER COMPANIES</a>
                        </label><br>
                      </div>
                </div>
            </div>
            <div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h1 class="animated fadeInLeft">BANK ACCOUNT</h1>
                        <p class="animated fadeInDown" >
                          > Add Bank Account
                        </p>
                    </div>
                </div>
              </div>
              <div class="col-md-12 padding-0">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>Add Bank Account</h3></div>

                      <div class="panel-body">
                        <form action="add_bank_info_process.php" method="POST" id="defaultForm">
                         <div class="row">
                          <div class="col-md-3">
                            <h5>ACCOUNT NUMBER</h5>
                               <div class="form-group has-feedback">
                                 <h4><?php echo $account_no ; ?></h4>
                                 <input type="hidden" name="bai_account_no" value="<?php echo $account_no; ?>"/>
                               </div>
                          </div>
                          <div class="col-md-3">
                            <h5>STATUS</h5>
                               <div class="form-group has-feedback">
                                  <select class="form-control" name="bai_status">
                                      <option value=""></option>
                                      <option value="Active">Active</option>
                                      <option value="Locked">Locked</option>
                                      <option value="Deactivated">Deactivated</option>
                                  </select>
                               </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3">
                            <h5>LASTNAME</h5>
                               <div class="form-group has-feedback">
                               <input type="text" class="form-control" name="bai_lname" id="bai_lname" placeholder="lastname"/>
                               </div>
                          </div>
                          <div class="col-md-3">
                            <h5>FIRSTNAME</h5>
                               <div class="form-group has-feedback">
                               <input type="text" class="form-control" name="bai_fname" placeholder="fastname"/>
                               </div>
                          </div>
                          <div class="col-md-3">
                            <h5>MIDDLENAME</h5>
                               <div class="form-group has-feedback">
                               <input type="text" class="form-control" name="bai_mname" placeholder="mastname"
                              />
                               </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                              <h5 style="padding-left:5px;">DATE ADDED</h5>
                              <div class="form-group">
                                <div class="dateContainer">
                                  <div class="input-group input-append date" id="bai_date_added">
                                    <input type="text" class="form-control" name="bai_date_added"  placeholder="(e.g. MM/DD/YYYY)" />
                                    <span class="input-group-addon add-on">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </div>
                         <div class="col-md-3">
                              <h5 style="padding-left:5px;">DATE EXPIRED</h5>
                              <div class="form-group">
                                <div class="dateContainer">
                                  <div class="input-group input-append date" id="bai_date_expired">
                                    <input type="text" class="form-control" name="bai_date_expired"  placeholder="(e.g. MM/DD/YYYY)" />
                                    <span class="input-group-addon add-on">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <h3>CONTACT INFO</h3>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-10">
                            <h5>ADDRESS</h5>
                            <div class="form-group has-feedback">
                              <textarea row="4" class="form-control" name="bai_address"></textarea>
                            </div>
                          </div>

                        </div>
                        <div class="row">
                          <div class="col-md-4">
                            <h5>MOBILE NUMBER </h5>
                            <div class="form-group has-feedback">
                              <input type="text" class="form-control" name="bai_contactno">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <h5>EMAIL</h5>
                              <div class="form-group has-feedback">
                              <input type="text" class="form-control" name="bai_email"/>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <button type="submit" name="btn_add_bank_account" class="btn btn-block btn-success">
                                  <span class="glyphicon glyphicon-save-file"></span>&nbsp; Add Account</button>
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
$('#bai_date_expired')
        .datepicker({
            format: 'mm/dd/yyyy',
            autoClose: true
        })
        .on('changeDate', function(e) {
            $('#defaultForm').bootstrapValidator('revalidateField', 'bai_date_expired');
        });

  $('#bai_date_added')
        .datepicker({
            format: 'mm/dd/yyyy',
            autoClose: true
        })
        .on('changeDate', function(e) {
            $('#defaultForm').bootstrapValidator('revalidateField', 'bai_date_added');
        });

  $('#defaultForm')
    .bootstrapValidator({
      message: 'This value is invalid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid:'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
              bai_lname: {
                    message: 'Lastname is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Lastname is required and can\'t be empty'
                        },
                  stringLength: {
                            min: 1,
                            max: 100,
                            message: 'Lastname must be more than 1 and less than 32 characters long'
                        },
                  regexp: {
                            regexp: /^[a-zA-Z ]+$/,
                            message: 'Invalid characters'
                        },
                    }
                },
                bai_mname: {
                    message: 'Middlename is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Middlename is required and can\'t be empty. if the person dont have a middle name just input period(".").'
                        },
                  stringLength: {
                            min: 1,
                            max: 100,
                            message: 'Middlename must be more than 1 and less than 32 characters long'
                        },
                  regexp: {
                            regexp: /^[a-zA-Z ]+$/,
                            message: 'Invalid characters'
                        },
                    }
                },
                bai_fname: {
                    message: 'Firstname is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Firstname is required and can\'t be empty.'
                        },
                  stringLength: {
                            min: 1,
                            max: 100,
                            message: 'Firstname must be more than 1 and less than 32 characters long'
                        },
                  regexp: {
                            regexp: /^[a-zA-Z ]+$/,
                            message: 'Invalid characters'
                        },
                    }
                },
                bai_address: {
                    message: 'Address is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Address is required and can\'t be empty'
                        },
                  stringLength: {
                            min: 1,
                            max: 100,
                            message: 'Address must be more than 1 and less than 100 characters long'
                        },
                  regexp: {
                            regexp: /^[a-zA-Z \-=.!@#$%^&*()_+{}|<>:,/? 0-9]+$/,
                            message: 'Invalid characters'
                        },
                    }
                },
                bai_contactno: {
                    message: 'Contact Number is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Contact Number is required and can\'t be empty'
                        },
                  stringLength: {
                            min: 1,
                            max: 100,
                            message: 'Contact Number must be 11 Numbers'
                        },
                  regexp: {
                            regexp: /^[0-9]+$/,
                            message: 'Invalid characters! It must be numbers only.'
                        },
                    }
                },
                bai_status: {
                    message: 'Status is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Status is required and can\'t be empty'
                        }
                    }
                },
                 bai_email: {
                    message: 'Email is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Email is required and can\'t be empty'
                        },
                        emailAddress:{
                            message: 'Incorrect mail address'
                        },
                  stringLength: {
                            min: 1,
                            max: 50,
                            message: 'Email must be more than 1 and less than 50 characters long'
                        },
                    }
                },
                bai_date_added: {
                    validators: {
                        date: {
                            max: 'bai_date_expired',
                            message: 'Date added is invalid'
                        },
                        date: {
                            format: 'MM/DD/YYYY',
                            message: 'Invalid format of date date added(e.g. MM/DD/YYYY)'
                        },
                        notEmpty: {
                            message: 'Date added is required and can\'t be empty'
                        }
                    }
                },
                bai_date_expired: {
                    validators: {
                        date: {
                            max: 'bai_date_added',
                            message: 'Date notary is invalid'
                        },
                        date: {
                            format: 'MM/DD/YYYY',
                            message: 'Invalid format of date expired(e.g. MM/DD/YYYY)'
                        },
                        notEmpty: {
                            message: 'Date expired is required and can\'t be empty'
                        }
                    }
                },

            }
      })
      .on('success.field.fv', function(e, data) {
            if (data.field === 'bai_date_added' && !data.fv.isValidField('bai_date_expired')) {
                data.fv.revalidateField('bai_date_expired');
            }

            if (data.field === 'bai_date_expired' && !data.fv.isValidField('bai_date_added')) {
                data.fv.revalidateField('bai_date_added');
            }
        });
    });
</script>
</script>
</body>
</html>
