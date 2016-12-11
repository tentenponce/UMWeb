<?php

require 'medoo/medoo.php';
$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'uhac_cal',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8'
]);

?>
