<?php
	require 'asset/connection/mysqli_dbconnection.php';
	if(!isset($_COOKIE["company_id"])) {
		header ("Location: company_login.php");
		exit;
	}
	if($_COOKIE["company_id"]) {
		header ("Location: company_index.php");
		exit;
	} 
?>