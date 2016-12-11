<?php
  require 'connection/mysqli_dbconnection.php';

  date_default_timezone_set('Asia/Manila');
  $reserve_date = date('Y-m-d H:i:s');

  //product_orders -> reservation

  $json = file_get_contents('php://input');
	$product_reservation = json_decode($json);
  $product_orders = $product_reservation->{'product_orders'};
  $deliver_id = $product_reservation->{'deliver_id'};
  $deliver_address = $product_reservation->{'deliver_address'};
  $account_id = $db->get("bank_account_tbl", "bai_id", ["bai_account_no"=>$product_reservation->{'bai_account_no'}]);

  $reservationValidation = array(); //object that will holds the corresponsing error code and items
  $reservationGroup = new stdClass(); //holds the code, orders, date and date today
  $productOrders = array(); //handles the product orders if reserve or un reserve to encode
  $isNotReserve = false; //handles if the validation is true to encode the error

  //setup the orders to be inserted, default is true, all to be insert.
  $orders_validate = array();
  for ($i = 0; $i < count($product_orders); $i ++) {
    $orders_validate[$i] = true;
  }

  //build reservation code
  $seed = str_split('abcdefghijkmnopqrstuvwxyz'
                     .'ABCDEFGHJKLMNPQRSTUVWXYZ'
                     .'123456789'); // and any other characters
  shuffle($seed); // probably optional since array_is randomized; this may be redundant
  $rand = '';
  foreach (array_rand($seed, 3) as $k) $rand .= $seed[$k];

  $reserve_code = date('siHdmy') . $rand;

  //check if the product reserve for that item and havent paid it yet.
  for ($i = 0; $i < count($product_orders); $i ++) {
    $product_item = $product_orders[$i]->{'product_item'};
    $prod_id = $product_item->{'prod_id'};

    $sql = "SELECT * FROM reservation_tbl WHERE bai_id=$account_id AND prod_id=$prod_id AND DATE_ADD(reserve_date, interval 4 DAY) > '$reserve_date'";
    $reservationDatas = $db->query($sql)->fetchAll();

    if (count($reservationDatas) >= 1) {
      array_push ($productOrders, array(
        "product_item" => $product_item,
        "order_qty" => $product_orders[$i]->{'order_qty'}));

      $orders_validate[$i] = false;
      $isNotReserve = true;
    }
  }

  if ($isNotReserve) {
    $reservationGroup->{'product_orders'} = $productOrders;

    array_push($reservationValidation, array(
      "response_code" => "0",
      "reservation_group" => $reservationGroup));
  }

  //check AGAIN if the item is really available incase someone is trying to reserve
  //it at the same time.
  $productOrders = array(); //reset product orders
  $reservationGroup = new stdClass(); //holds the code, orders, date and date today
  $isNotReserve = false;
  for ($i = 0; $i < count($product_orders); $i ++) {
    $product_item = $product_orders[$i]->{'product_item'};
    $prod_id = $product_item->{'prod_id'};
    $order_qty = $product_orders[$i]->{'order_qty'};

    $sql = "SELECT (a.prod_qty -
      (SELECT COALESCE(SUM(b.prod_qty), 0) FROM reservation_tbl as b
      WHERE prod_id='$prod_id' AND DATE_ADD(reserve_date, interval 4 DAY) > '$reserve_date')) AS remaining
      FROM product_tbl AS a WHERE prod_id=$prod_id";

    $result = $db->query($sql)->fetchAll();

    foreach ($result as $row) {
      $remaining = $row['remaining'];

      if (($remaining - $order_qty) < 0) { //check if the quantity ordered can be reserve base on remaining.
        array_push ($productOrders, array(
          "product_item" => $product_item,
          "order_qty" => $product_orders[$i]->{'order_qty'}));

        $orders_validate[$i] = false;
        $isNotReserve = true;
      }
    }
  }

  if ($isNotReserve) {
    $reservationGroup->{'product_orders'} = $productOrders;

    array_push($reservationValidation, array(
      "response_code" => "1",
      "reservation_group" => $reservationGroup));
  }

  //add the available items
  $someItemReserve = false; //variable that tells if there's atleast 1 item reserved.
  $productOrders = array();
  $reservationGroup = new stdClass(); //holds the code, orders, date and date today
  for ($i = 0; $i < count($orders_validate); $i ++) {
    $product_item = $product_orders[$i]->{'product_item'};
    $prod_id = $product_item->{'prod_id'};
    $order_qty = $product_orders[$i]->{'order_qty'};

    if ($orders_validate[$i]) {
      $db->insert("reservation_tbl", array([
        "reserve_code"    =>    $reserve_code,
        "prod_id"         =>    $prod_id,
        "bai_id"          =>    $account_id,
        "reserve_date"    =>    $reserve_date,
        "prod_qty"        =>    $order_qty,
        "deliver_id"      =>    $deliver_id,
        "deliver_address"      =>    $deliver_address,
        "notify_status"      =>    "unread"
    ]));

      array_push ($productOrders, array(
        "product_item" => $product_item,
        "order_qty" => $order_qty));

      $someItemReserve = true;
    }
  }

  if ($someItemReserve) {
    $reservationGroup->{'product_orders'} = $productOrders;
    $reservationGroup->{'reserve_code'} = $reserve_code;
    $reservationGroup->{'reserve_date'} = $reserve_date;
    $reservationGroup->{'date_today'} = $reserve_date;

    array_push($reservationValidation, array(
      "response_code" => "3",
      "reservation_group" => $reservationGroup));
  }

  echo json_encode($reservationValidation);
?>
