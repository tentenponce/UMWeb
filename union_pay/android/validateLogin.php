<?php
  require 'connection/mysqli_dbconnection.php';

  if (isset($_GET['company_username']) && isset($_GET['company_password'])) {
    $company_username = $_GET['company_username'];
    $company_password = $_GET['company_password'];

    if ($db->has("company_tbl", ["AND" => ["company_username" => $company_username, "company_password" => $company_password]])) {
      $companyData = $db->get("company_tbl", "*", ["AND" => ["company_username" => $company_username, "company_password" => $company_password]]);
      // $msgResponse->{'response_code'} = 1;
      // $msgResponse->{'response_msg'} = "Success.";
      echo json_encode($companyData);
    }
    else{
      echo json_encode("asdasd");
    }

  }
?>
