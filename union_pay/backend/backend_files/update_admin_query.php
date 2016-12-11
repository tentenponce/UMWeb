<?php


  require 'asset/connection/mysqli_dbconnection.php';

  $database -> update("admin_tbl",["password"=>$_POST['newpass'],"contact"=>$_POST['bai_contactno'],"address"=>$_POST['address']],["user_id"=>$_POST['account_id']]);
  header("location:update_admin.php?success_updated=1")

?>
