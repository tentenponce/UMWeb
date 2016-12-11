<?php


  require 'asset/connection/mysqli_dbconnection.php';
  if(isset($_POST['update']))
  {
    $newpass = $_POST['newpass'];
    $confirm_pass = $_POST['confirm_pass'];
    $database->update("company_tbl",["newpass" => $newpass,"confirm_pass" => $confirm_pass]);
    header("location:company_index.php?success=OK");
  }

?>
