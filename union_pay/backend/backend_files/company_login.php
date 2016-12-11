<?php
require 'asset/connection/mysqli_dbconnection.php';
error_reporting(E_ALL | E_STRICT);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="keyword" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UMP</title>
  <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">


  <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/nouislider.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/select2.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/ionrangeslider/ion.rangeSlider.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/ionrangeslider/ion.rangeSlider.skinFlat.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/bootstrap-material-datetimepicker.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/spinkit.css"/>
  <link href="asset/css/style.css" rel="stylesheet">

  <link rel="shortcut icon" href="asset/img/htlogo.png">

    <?php
      header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
    ?>
    </head>

    <body id="mimin" class="dashboard form-signin-wrapper">
      <div class="container">
        <div  class="form-signin">
          <div class="panel periodic-login">
              <span class="atomic-number">COMPANY</span>
              <div class="panel-body text-center">
                  <div>
                  <img src="asset/img/uhaclogo.png" alt="uhaclogo.png" >
                   </div>
                 <div id="error">

                 </div>
              <?php
              if(isset($_POST["btn-login"])) {
                      sleep(2);
                      $username = $_POST["validate_username"];
                      $password = $_POST["validate_password"];

                      $table = "company_tbl";
                      $columns = "*";
                      $where = ["AND"=>
                          [
                           "company_username"=>$username,
                           "company_password"=>$password
                          ]
                      ];

                           $sqlcu=$database->select($table,$columns,$where);

                           foreach ($sqlcu as $sqlcu_data)
                           {
                              setcookie('company_id',$sqlcu_data["company_id"],time() + 86400);
                              header ("Location: company_index.php?company_id=$username");
                              echo "ok";
                              exit;
                        }
                      }

              ?>
            <form id="signupForm" method="POST" class="form-signin">
              <div class="form-group form-animate-text" style="margin-top:40px !important;" >
              <input type="text" class="form-text" id="validate_username" name="validate_username">
                      <span class="bar"></span>
                      <label class="customlabel">Username</label>
                    </div>
                    <div class="form-group form-animate-text" style="margin-top:40px !important;">
                      <input type="password" class="form-text" id="validate_password" name="validate_password" maxlength="32">
                      <span class="bar"></span>
                      <label class="customlabel">Password</label>
                    </div>
                     <button type="submit" class="btn btn-default btn-lg btn-block" name="btn-login" id="btn-login">
                    <span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In
                    </button>
              </form>
              </div>
                <div class="text-center">
                    <a href="#" ><label style="color:#f2f2f2;"> Forgot Password ? Click here! </label> </a>
                </div>

          </div>
        </div>
      </div>
      <script src="asset/js/jquery.min.js"></script>
      <script src="asset/js/jquery.ui.min.js"></script>
      <script src="asset/js/bootstrap.min.js"></script>
      <script src="asset/js/plugins/moment.min.js"></script>
      <script src="asset/js/plugins/icheck.min.js"></script>
     <script src="asset/js/plugins/jquery.validate.min.js"></script>
      <script src="asset/js/main.js"></script>
      <script type="text/javascript">
   $(document).ready(function(){

  $("#signupForm").validate({
      errorElement: "em",
      errorPlacement: function(error, element) {
        $(element.parent("div").addClass("form-animate-error"));
        error.appendTo(element.parent("div"));
      },
      success: function(label) {
        $(label.parent("div").removeClass("form-animate-error"));
      },
      rules: {
        validate_password: {
          required: true,
          minlength: 5
        },
        validate_username: {
          required: true,
          minlength: 5
        }
    },
      messages: {
        validate_username: {
          required: "Please enter a username",
          minlength: "Your username must consist of 5 characters"
        },
        validate_password: {
          required: "Please provide a password",
          minlength: "Your password must be at least 5 characters long"
        }
      },
     submitHandler: submitForm
    });

   function submitForm()
     {
      var data = $("#signupForm").serialize();
      var error  ="Incorrect usernname or password";
      $.ajax({
      type : 'POST',
      url  : 'company_login_process.php',
      data : data,
      beforeSend: function()
      {
        $("#error").fadeOut();
        $("#btn-login").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp;Loading ...');
      },
      success :  function(response)
         {
          if(response=="ok"){
            $("#btn-login").html('<img src="btn-ajax-loader.gif"/> &nbsp; Signing In ...');
            setTimeout('window.location.href = "find_cookie_company.php"; ',1000);
          }
          else{
            $("#error").fadeIn(1000, function(){
              $("#error").html('<div class="alert alert-danger"><span class="glyphicon glyphicon-info-sign"></span> &nbsp; '+error+'</div>');
                      $("#btn-login").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In');
                  });
          }
        }
      });
        return false;
    }

});
     </script>
   </body>
   </html>
