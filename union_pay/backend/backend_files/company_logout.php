<?php
	setcookie("company_id", "", time() - 3600);
	header("Location: company_login.php");
	exit;
?>
