<?php
require 'asset/connection/mysqli_dbconnection.php';
if(isset($_POST['btn_add_bank_account']))
{
  $date_added_2 = date("Y-m-d",strtotime($_POST["bai_date_added"]));
  $date_expired_2 = date("Y-m-d",strtotime($_POST["bai_date_expired"]));
  $bai_account_no = $_POST["bai_account_no"];
  $database->insert("bank_account_tbl",[
    "bai_lname" => $_POST['bai_lname'] ,
    "bai_mname" => $_POST['bai_mname'] ,
    "bai_fname" => $_POST['bai_fname'],
    "bai_contactno" => $_POST['bai_contactno'],
    "bai_address" => $_POST['bai_address'],
    "bai_status" => $_POST['bai_status'],
    "bai_account_no" => $_POST['bai_account_no'],
    "bai_date_added" =>$date_added_2,
    "bai_date_expired" => $date_expired_2,
    "bai_email" => $_POST['bai_email'],
    "notify_status" => "read"
  ]);
   header("location:index.php?success_added=1&bai_account_no=$bai_account_no");
}
?>
