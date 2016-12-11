<?php
	require 'asset/connection/mysqli_dbconnection.php';
	if(!isset($_COOKIE["user_id"])) {
		header ("Location: login.php");
		exit;
	}
	if($_COOKIE["user_type"] == 1) {
		header ("Location: index.php");
		exit;
	} 
?>