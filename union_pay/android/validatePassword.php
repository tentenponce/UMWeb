<?php
  require 'connection/mysqli_dbconnection.php';

  if (isset($_GET['bai_account_no']) && isset($_GET['bai_password'])) {
    $account_no = $_GET['bai_account_no'];
    $password = $_GET['bai_password'];

    $msgResponse = new stdClass();
    if ($db->has("bank_account_tbl", ["AND" => ["bai_account_no" => $account_no, "bai_password" => $password]])) {
      $bai_status = $db->get("bank_account_tbl","bai_status", [
        "AND"=>[
          "bai_account_no"=>$account_no,
          "bai_password"=>$password
        ]
      ]);
      if($bai_status=="Active"){
        $msgResponse->{'response_code'} = 1;
        $msgResponse->{'response_msg'} = "Password matched.";
      } else if($bai_status=="Locked"){
        $msgResponse->{'response_code'} = -2;
        $msgResponse->{'response_msg'} = "Your account is locked. Please proceed to the nearest branch of Unionbank.";
      }
    } else {
      $msgResponse->{'response_code'} = -1;
      $msgResponse->{'response_msg'} = "Password did not matched.";
    }

    echo json_encode($msgResponse);
  }
?>
