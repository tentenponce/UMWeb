<?php
session_start();
require 'asset/connection/mysqli_dbconnection.php';

error_reporting(E_ALL | E_STRICT);
if(isset($_POST['button_add']))
{       
        $prod_name         =  $_POST['prod_name'];
        $prod_qty             =    $_POST['prod_qty'];
        $prod_order_limit         =  $_POST['prod_order_limit'];
        $prod_price         =  $_POST['prod_price'];
        $prod_status        =  $_POST['prod_status'];
        $prod_desc        =  $_POST['prod_desc'];
        $company_id         =  $_POST['company_id'];
        $prod_code      =  $_POST['prod_code'];
        $pc_id      =  $_POST['pc_id'];

        $data = $database->insert("product_tbl",[
            "prod_name"          =>  $prod_name,
            "prod_qty"             =>  $prod_qty,
            "prod_order_limit"              =>  $prod_order_limit,
            "prod_price"              =>  $prod_price,
            "prod_desc"              =>  $prod_desc,
            "prod_status"   =>  $prod_status,
            "company_id"         =>  $company_id,
            "prod_code"       =>  $prod_code,
            "pc_id"   =>  $pc_id,   
            ]);
            header("location: company_index.php?success=OK");
}
?>
