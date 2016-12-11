<?php
  require 'connection/mysqli_dbconnection.php';

  if(isset($_GET["bai_account_no"])){

    $bai_account_no = $_GET["bai_account_no"];


    $msgResponse = new stdClass();
    if ($db->has("bank_account_tbl", ["bai_account_no"=>$bai_account_no])) {
      $cob_info = $db->get("bank_account_tbl", "cob", ["bai_account_no"=>$bai_account_no]);
      $msgResponse->{'response_code'} = 1;
      $msgResponse->{'response_msg'} = $cob_info;
    } else {
      $msgResponse->{'response_code'} = -1;
      $msgResponse->{'response_msg'} = "Invalid Account.";
    }

    echo json_encode($msgResponse);

  }

?>
