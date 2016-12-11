<?php

require 'asset/connection/mysqli_dbconnection.php';

date_default_timezone_set("Asia/Manila");
$date_today =  date("Y-m-d");

error_reporting(E_ALL | E_STRICT);
if(isset($_POST['btn-login']))
  {
    $username = $_POST['validate_username'];
    $password = $_POST['validate_password'];

        $table = "admin_tbl";
        $columns = ["user_id","username","password","user_type"];
        $where = ["AND"=>
                [
                 "username"=>$username,
                 "password"=>$password
                ]
            ];

             $sql=$database->select($table,$columns,$where);

             foreach ($sql as $sql_data)
             {
              if($sql_data['user_type']==1)
              {
                setcookie('user_id',$sql_data['user_id'],time() + 86400);
                setcookie('user_type',1,time() + 86400);
                echo "ok";
                exit;
              }

              elseif($sql_data['username'] != $username && $sql_data['password'] != $password)
              {
              setcookie('error','1',time() + 86400);
              echo "Username or Password incorrect.";
              exit;
            }
             }
  }
?>
