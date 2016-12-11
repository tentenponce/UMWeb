<?php
session_start();
require 'asset/connection/mysqli_dbconnection.php';

error_reporting(E_ALL | E_STRICT);
if(isset($_POST['button_add']))
{       
        $company_name         =  $_POST['company_name'];
        $pt_id             =    $_POST['pt_id'];
        $company_desc         =  $_POST['company_desc'];
        $company_address         =  $_POST['company_address'];
        $company_cob        =  $_POST['company_cob'];
        $company_status        =  $_POST['company_status'];
        $company_username      =  $_POST['company_username'];
        $company_password      =  $_POST['company_password'];
        $company_lname      =  $_POST['company_lname'];
        $company_fname      =  $_POST['company_fname'];
        $company_mname      =  $_POST['company_mname'];
        $contact_no      =  $_POST['contact_no'];
        $position      =  $_POST['position'];
        $tin_number      =  $_POST['tin_number'];

        $data = $database->insert("company_tbl",[
            "company_name"          =>  $company_name,
            "pt_id"             =>  $pt_id,
            "company_desc"              =>  $company_desc,
            "company_address"              =>  $company_address,
            "company_cob"              =>  $company_cob,
            "company_status"   =>  $company_status,
            "company_username"         =>  $company_username,
            "company_password"       =>  $company_password,
            "company_lname"   =>  $company_lname,
            "company_fname"          =>  $company_fname,
            "company_mname"             =>  $company_mname,
            "contact_no"              =>  $contact_no,
            "position"              =>  $position,
            "tin_number"              =>  $tin_number,
               
            ]);
            header("location: partner_company.php?success=OK");
}
?>
