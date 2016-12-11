<?php
  include ('sms.php');
  require 'asset/connection/mysqli_dbconnection.php';

  $contact = $database->get("bank_account_tbl", "bai_contactno", ["bai_id" => $_GET['bai_id']]);

  $result = itexmo($contact, "Your reservation is ready to pick up.", "STEVE087359_DGST2");

  if ($result == 0) {
    echo "sent";
  } else {
    echo "error num: " . $result;
  }

  // header ("Location: view_reservation.php");
?>
