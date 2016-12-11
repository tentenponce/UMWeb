<?php
  require 'connection/mysqli_dbconnection.php';

  if (isset($_GET['bai_account_no'])) {
    $bai_account_no = $_GET['bai_account_no'];

    $msgResponse = new stdClass();

    if ($db->has("bank_account_tbl", ["bai_account_no" => $bai_account_no])) {
      $bai_status = $db->get("bank_account_tbl", "bai_status", ["bai_account_no" => $bai_account_no]);
      if($bai_status=="Active"){
        $db->update("bank_account_tbl", ["bai_status"=>"Locked", "notify_status"=>"unread"], ["bai_account_no" => $bai_account_no]);
        $msgResponse->{'response_code'} = 1;
        $msgResponse->{'response_msg'} = "Your account has been locked, please contact Union Bank to unlock your account.";
      }
    } else {
      $msgResponse->{'response_code'} = -1;
      $msgResponse->{'response_msg'} = "Invalid Account.";
    }

    echo json_encode($msgResponse);
  }
?>
