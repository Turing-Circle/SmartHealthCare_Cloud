<?php

//Database Login Credentials
$host 			= "test-db-instance.ch1f8mbps2qd.ap-south-1.rds.amazonaws.com";
$db_user 		= "testuser";
$db_password 	= "outlookcom";
$db_name 		= "testDB";
 
//Connectivity to Database
$con = new mysqli($host, $db_user, $db_password, $db_name);

?>

