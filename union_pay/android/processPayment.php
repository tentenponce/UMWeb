<?php
  require 'connection/mysqli_dbconnection.php';

  date_default_timezone_set('Asia/Manila');
  $dateToday = date('Y-m-d H:i:s');

  if (isset($_GET['reserve_code'])) {
    $msgResponse = new stdClass(); //response for android

    $reserve_code = $_GET['reserve_code'];
    $company_id = $_GET['company_id'];

    //validate if the reservation is on the company
    $productData = $db->has("reservation_tbl",
      ["[>]product_tbl" => ["prod_id" => "prod_id"]],
      [ "AND" => [
        "product_tbl.company_id" => $company_id,
        "reservation_tbl.reserve_code" => $reserve_code
        ]
      ]);

    if (!$productData) {
      $msgResponse->{'response_code'} = -1;
      $msgResponse->{'response_msg'} = "Cannot find the reservation.";

      echo json_encode($msgResponse);
      return;
    }


    //check COB if account if the customer can buy the product(s)
    $sql = "SELECT SUM(b.prod_price * a.prod_qty) AS total_price, a.bai_id FROM reservation_tbl AS a LEFT JOIN product_tbl AS b ON a.prod_id=b.prod_id WHERE a.reserve_code = '" . $reserve_code . "'";

    $totalPrice = $db->query($sql)->fetch()[0]; //get the total price
    $baiId = $db->query($sql)->fetch()[1]; //get the total price

    $accountCob = $db->get("bank_account_tbl", "cob", ["bai_id" => $baiId]);

    if ($totalPrice > $accountCob) {
      $msgResponse->{'response_code'} = -2;
      $msgResponse->{'response_msg'} = "Insufficient balance.";

      echo json_encode($msgResponse);
      return;
    }

    $reserveDatas = $db->select("reservation_tbl", [
      "reserve_code", "prod_id", "bai_id", "reserve_date", "prod_qty"
    ], [
      "reserve_code" => $reserve_code
    ]); //get the reservation by reserve code

    foreach ($reserveDatas as $reserveData) {
      $orderPrice = $db->get("product_tbl", "prod_price", ["prod_id" => $reserveData['prod_id']]) * $reserveData['prod_qty']; //get the order price to add to company and deduct on user

      //deduct from user
      $db->update("bank_account_tbl", [
        "cob[-]" => $orderPrice
      ], [
        "bai_id" => $reserveData['bai_id']
      ]);

      //add to company
      $db->update("company_tbl", [
        "company_cob[+]" => $orderPrice
      ], [
        "company_id" => $db->get("product_tbl", "company_id", ["prod_id" => $reserveData['prod_id']]) // get the company id by the product id
      ]);

      //deduct product quantity
      $db->update("product_tbl", [
        "prod_qty[-]" => $reserveData['prod_qty']
      ], [
        "prod_id" => $reserveData['prod_id']
      ]);

      //build OR Number
      $year_today=date("Y");
      $date_today=date("Y-m-d");
      $or_no=rand(101010,999999);
      $or_gen  = "OR".$year_today.$or_no;

      $db->insert("payment_tbl", [
        "or_no" => $or_gen,
        "reserve_code" => $reserveData['reserve_code'],
        "prod_id" => $reserveData['prod_id'],
        "prod_qty" => $reserveData['prod_qty'],
        "bai_id" => $reserveData['bai_id'],
        "reserve_date" => $reserveData['reserve_date'],
        "payment_date" => $dateToday
      ]);
    }

    //lastly, delete the whole reservation
    $db->delete("reservation_tbl", ["reserve_code" => $reserve_code]);

    $msgResponse->{'response_code'} = 1;
    $msgResponse->{'response_msg'} = "Successfully Paid";

    echo json_encode($msgResponse);
  }
?>
