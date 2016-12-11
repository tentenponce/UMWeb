<?php
require 'asset/connection/mysqli_dbconnection.php';
if(isset($_GET['bai_id']) && isset($_GET['confirmation_code']) && isset($_GET['reserve_code'])){
	$company_id = $_COOKIE["company_id"];
	$bai_id = $_GET['bai_id'];
	$confirmation_code = $_GET['confirmation_code'];
	$reserve_code = $_GET['reserve_code'];

	$table = "bank_account_tbl";
	$columns= "*";
	$where =["bai_id"=>$bai_id];
	$q_cc = $database->select($table,$columns,$where);
	foreach($q_cc as $q_cc_data){
		$bai_password = $q_cc_data["bai_password"];

		if($bai_password != $confirmation_code){
				echo "error";
				exit;
		}
		else
		{
			 $year_today=date("Y");
			 $date_today=date("Y-m-d");
			 $or_no=rand(101010,999999);
			 $or_gen  = "OR".$year_today.$or_no;
			 $total_amount =0;

			 $table_r = "reservation_tbl";
			 $columns_r = "*";
			 $where_r = ["reserve_code"=>$reserve_code];
			 $q_r_tbl = $database->select($table_r,$columns_r,$where_r);
			 foreach($q_r_tbl as $q_r_data){

			 $prod_id = $q_r_data["prod_id"];
			 $bai_id = $q_r_data["bai_id"];
			 $reserve_date = $q_r_data["reserve_date"];
			 $prod_qty = $q_r_data["prod_qty"];

			 $table_pt = "product_tbl";
			 $columns_pt  ="*";
			 $where_pt = ["prod_id"=>$prod_id];

			 $q_r_tbl = $database->select($table_pt,$columns_pt,$where_pt);
			 foreach($q_r_tbl as $q_r_data){
			 	$prod_price = $q_r_data["prod_price"];
			 	$prod_subtotal = $prod_price * $prod_qty;

			 	$move_to_payment_tbl = $database->insert("payment_tbl",[
			    "or_no" => $or_gen ,
			    "reserve_code" => $reserve_code ,
			    "prod_id" => $prod_id,
			    "prod_qty" => $prod_qty,
			    "bai_id" => $bai_id,
			    "reserve_date" => $reserve_date,
			    "payment_date" => $date_today,

			 	]);



				$table_bai = "bank_account_tbl";
				$columns_bai = ["cob[-]"=>$prod_subtotal];
				$where_bai = ["bai_id"=>$bai_id];
				$minus_cob_bai = $database->update($table_bai,$columns_bai,$where_bai);


			 	$table_com = "company_tbl";
				$columns_com = ["company_cob[+]"=>$prod_subtotal];
				$where_com = ["company_id"=>$company_id];
				$update_bank = $database->update($table_com,$columns_com,$where_com);

				$table_prod_qty = "product_tbl";
				$columns_prod_qty = ["prod_qty[-]"=>$prod_qty];
				$where_prod_qty = ["prod_id"=>$prod_id];
				$update_prod_qty = $database->update($table_prod_qty,$columns_prod_qty,$where_prod_qty);


			 }
		}
				$table_res_del = "reservation_tbl";
				$where_res_del = ["reserve_code"=>$reserve_code];
				$update_prod_qty = $database->delete($table_res_del,$where_res_del);
				echo $or_gen;
	}
  }
}
?>
