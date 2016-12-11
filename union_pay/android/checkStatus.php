<?php
  require 'connection/mysqli_dbconnection.php';

  if (isset($_GET['bai_account_no'])) {
    $bai_account_no = $_GET['bai_account_no'];

    $msgResponse = new stdClass();
    if ($db->has("bank_account_tbl", ["bai_account_no" => $bai_account_no])) {
      $acct_info = $db->get("bank_account_tbl", ["bai_status", "bai_lname", "bai_fname"], ["bai_account_no" => $bai_account_no]);
      if($acct_info['bai_status']=="Active"){
        $msgName = $acct_info['bai_lname'] . ", " . $acct_info['bai_fname'];
        $msgResponse->{'response_code'} = 1;
        $msgResponse->{'response_msg'} = $msgName;
      } else if($acct_info['bai_status']=="Locked"){
        $msgResponse->{'response_code'} = -2;
      }
    } else {
      $msgResponse->{'response_code'} = -1;
    }

    echo json_encode($msgResponse);
  }
?>
