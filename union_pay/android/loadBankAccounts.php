<?php
  require 'connection/mysqli_dbconnection.php';

  $bank_account_info = $db->select("bank_account_tbl", "*");

  echo json_encode($bank_account_info);

?>
