<?php
  require 'connection/mysqli_dbconnection.php';

  date_default_timezone_set('Asia/Manila');
  $dateToday = date('Y-m-d H:i:s');

  if(isset($_GET["reserve_code"]))
  {
    $reserve_code = $_GET["reserve_code"];
    $company_id = $_GET['company_id'];

    $query = "SELECT DISTINCT (a.reserve_code), a.reserve_date, bank_account_tbl.bai_account_no, bank_account_tbl.bai_lname, bank_account_tbl.bai_fname FROM reservation_tbl AS a LEFT JOIN bank_account_tbl ON a.bai_id=bank_account_tbl.bai_id LEFT JOIN product_tbl AS c ON a.prod_id=c.prod_id WHERE reserve_code= '$reserve_code' AND deliver_id=1 AND c.company_id=$company_id";

    $reservation_info = $db->query($query)->fetchAll();

    //$reservationGroupArray = array();
    $reservationGroup = new stdClass();
    foreach ($reservation_info as $reservation_product_data) {


      $reservationGroup->{'reserve_code'} = $reservation_product_data['reserve_code'];
      $strName = $reservation_product_data['bai_lname'] . ", " . $reservation_product_data['bai_fname'];
      $reservationGroup->{'bai_account_no'} = $reservation_product_data['bai_account_no'];
      $reservationGroup->{'account_name'} = $strName;
      $reservationGroup->{'reserve_date'} = $reservation_product_data['reserve_date'];
      $reservationGroup->{'date_today'} = $dateToday;

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

    }

    echo json_encode($reservationGroup);
  }

?>
