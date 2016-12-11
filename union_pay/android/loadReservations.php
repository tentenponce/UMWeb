<?php
  require 'connection/mysqli_dbconnection.php';

  date_default_timezone_set('Asia/Manila');
  $dateToday = date('Y-m-d H:i:s');

  if(isset($_GET["bai_account_no"]))
  {
    $bai_account_no = $_GET["bai_account_no"];

    $bai_id = $db->get("bank_account_tbl", "bai_id", ["bai_account_no"=>$bai_account_no]);

    $query = "SELECT DISTINCT (a.reserve_code), c.company_name, a.reserve_date, a.deliver_id FROM reservation_tbl AS a LEFT JOIN product_tbl AS b ON a.prod_id=b.prod_id LEFT JOIN company_tbl AS c ON b.company_id=c.company_id WHERE bai_id= $bai_id";

    $reservation_info = $db->query($query)->fetchAll();

    $reservationGroupArray = array();
    foreach ($reservation_info as $reservation_product_data) {
      $reservationGroup = new stdClass();

      $reservationGroup->{'reserve_code'} = $reservation_product_data['reserve_code'];
      // $reservationGroup->{'product_orders'} = $db->select("product_tbl", ["prod_name"], []);
      $reservationGroup->{'company_name'} = $reservation_product_data['company_name'];
      $reservationGroup->{'reserve_date'} = $reservation_product_data['reserve_date'];
      $reservationGroup->{'date_today'} = $dateToday;
      $reservationGroup->{'deliver_id'} = $reservation_product_data['deliver_id'];

      $table = "reservation_tbl";
      $join_clause = ["[>]product_tbl" => ["prod_id" => "prod_id"]];
      $columns = ["product_tbl.prod_id",
                  "product_tbl.prod_name",
                  "product_tbl.prod_price",
                  "reservation_tbl.prod_qty"
                  ];
      $where = ["reserve_code"=>$reservation_product_data['reserve_code']];


      $productOrdersResult = $db->select($table, $join_clause, $columns, $where);

      $productOrders = array();

      foreach($productOrdersResult as $productOrderRow){

        array_push($productOrders, array( //build the array of product items
          'product_item' => $productOrderRow,
          'order_qty' => $productOrderRow['prod_qty']));
      }

      $reservationGroup->{'product_orders'} = $productOrders; //add the array of orders

      array_push($reservationGroupArray, $reservationGroup); //add it the whole group to the reservations
    }

    echo json_encode($reservationGroupArray);
  }

?>
