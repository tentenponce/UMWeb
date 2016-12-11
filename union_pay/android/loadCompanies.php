<?php
  require 'connection/mysqli_dbconnection.php';

  $company_info = $db->select("company_tbl", "*");

  echo json_encode($company_info);

?>
