<?php

require 'asset/connection/mysqli_dbconnection.php';

date_default_timezone_set("Asia/Manila");
$date_today =  date("Y-m-d");

error_reporting(E_ALL | E_STRICT);
if(isset($_POST['btn-login']))
  {
    $username = $_POST['validate_username'];
    $password = $_POST['validate_password'];

        $table = "company_tbl";
        $columns = ["company_id","company_username","company_password"];
        $where = ["AND"=>
                [
                 "company_username"=>$username,
                 "company_password"=>$password
                ]
            ];

             $sql=$database->select($table,$columns,$where);

             foreach ($sql as $sql_data)
             {
              if($sql_data)
              {
                setcookie('company_id',$sql_data['company_id'],time() + 86400);
                echo "ok";
                exit;
              }

              elseif($sql_data['company_username'] != $username && $sql_data['company_password'] != $password)
              {
              setcookie('error','1',time() + 86400);
              echo "Username or Password incorrect.";
              exit;
            }
             }
  }
?>
