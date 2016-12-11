<?php
require 'asset/connection/mysqli_dbconnection.php';

if(isset($_GET['bai_id']) && isset($_GET['total_amount'])){

	$bai_id = $_GET['bai_id'];
	$total_amount = $_GET['total_amount'];
	$table = "bank_account_tbl";
	$columns = "*";
	$where = ["bai_id"=>$bai_id];
	$q_ds = $database->select($table,$columns,$where);

	foreach($q_ds AS $q_ds_data){
		$cob = $q_ds_data['cob'];

		if($cob < $total_amount){
			echo "error";
			exit;
		}
	}
}
?>
