<?php
  require 'connection/mysqli_dbconnection.php';

  if(isset($_GET["company_id"]))
  {
    $company_id = $_GET["company_id"];
    // $company_id = 1;

    $table = "product_tbl";
    $join_clause = ["[>]product_category_tbl" => ["pc_id" => "pc_id"]];
    $columns = ["product_tbl.prod_id",
                "product_tbl.prod_name",
                "product_tbl.prod_desc",
                "product_tbl.prod_qty",
                "product_tbl.prod_order_limit",
                "product_tbl.prod_price",
                "product_tbl.prod_code",
                "product_tbl.prod_img",
                "product_category_tbl.pc_name",
                "product_tbl.company_id"
                ];
    $where = ["AND"=>
                ["product_tbl.company_id"=>$company_id,
                  "product_tbl.prod_status"=>"1"
                ]
              ];

    $product_info = $db->select($table, $join_clause, $columns, $where);

    foreach ($product_info as &$product) {
      $product['prod_qty'] -= $db->sum("reservation_tbl", "prod_qty", [
        "prod_id" => $product['prod_id']
      ]);
    }

    echo json_encode($product_info);
  }

?>
