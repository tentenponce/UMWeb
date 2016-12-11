<?php
  require 'connection/mysqli_dbconnection.php';

  date_default_timezone_set('Asia/Manila');
  $dateToday = date('Y-m-d H:i:s');

  if(isset($_GET["bai_account_no"]))
  {
    $bai_account_no = $_GET["bai_account_no"];

    $bai_id = $db->get("bank_account_tbl", "bai_id", ["bai_account_no"=>$bai_account_no]);

    $query = "SELECT DISTINCT (a.reserve_code), a.or_no, c.company_name, a.reserve_date FROM payment_tbl AS a LEFT JOIN product_tbl AS b ON a.prod_id=b.prod_id LEFT JOIN company_tbl AS c ON b.company_id=c.company_id WHERE bai_id= $bai_id";

    $payment_info = $db->query($query);

    $paymentGroupArray = array();
    foreach ($payment_info as $payment_product_data) {
      $paymentGroup = new stdClass();
      $paymentGroup->{'or_no'} = $payment_product_data['or_no'];
      $paymentGroup->{'reserve_code'} = $payment_product_data['reserve_code'];

      // $paymentGroup->{'product_orders'} = $db->select("product_tbl", ["prod_name"], []);
      $paymentGroup->{'company_name'} = $payment_product_data['company_name'];
      $paymentGroup->{'reserve_date'} = $payment_product_data['reserve_date'];
      $paymentGroup->{'date_today'} = $dateToday;

      $table = "payment_tbl";
      $join_clause = ["[>]product_tbl" => ["prod_id" => "prod_id"]];
      $columns = ["product_tbl.prod_id",
                  "product_tbl.prod_name",
                  "product_tbl.prod_price",
                  "payment_tbl.prod_qty"
                  ];
      $where = ["reserve_code"=>$payment_product_data['reserve_code']];


      $productOrdersResult = $db->select($table, $join_clause, $columns, $where);

      $productOrders = array();

      foreach($productOrdersResult as $productOrderRow){

        array_push($productOrders, array( //build the array of product items
          'product_item' => $productOrderRow,
          'order_qty' => $productOrderRow['prod_qty']));
      }

      $paymentGroup->{'product_orders'} = $productOrders; //add the array of orders

      array_push($paymentGroupArray, $paymentGroup); //add it the whole group to the payments
    }

    echo json_encode($paymentGroupArray);
  }

?>
