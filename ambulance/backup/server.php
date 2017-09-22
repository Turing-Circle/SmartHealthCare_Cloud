<?php

//Database Login Credentials
$host 			= "test-db-instance.ch1f8mbps2qd.ap-south-1.rds.amazonaws.com";
$db_user 		= "testuser";
$db_password 	= "outlookcom";
$db_name 		= "testDB";
 
//Connectivity to Database
$con = new mysqli($host, $db_user, $db_password, $db_name);

$ambuid = 'AMBSHARDA002'; //$_POST["id"];

$sql = " select * from victims where ambulanceid = '".$ambuid."';";

$result = mysqli_query($con, $sql);

$response = array();

$num = mysqli_num_rows($result);

$user = '0';

if($num == 0)
{	
	array_push( $response, array("user"=>$user, "latitude"=>'0', "longitude"=>'0'));
	echo json_encode($response);
}
else
{		$row = mysqli_fetch_row($result);
		$user = $row[0];
		$lat = $row[5];
		$long = $row[6];
		
		array_push( $response, array("user"=>$user, "latitude"=>$lat, "longitude"=>$long));
		
		echo json_encode($response);
}

?>