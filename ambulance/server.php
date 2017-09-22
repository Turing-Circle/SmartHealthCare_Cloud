<?php

//Database Login Credentials
$host 			= "test-db-instance.ch1f8mbps2qd.ap-south-1.rds.amazonaws.com";
$db_user 		= "testuser";
$db_password 	= "outlookcom";
$db_name 		= "testDB";
 
//Connectivity to Database
$con = new mysqli($host, $db_user, $db_password, $db_name);

$ambuid = $_POST["id"];

//SQL query to fetch victim data to ambulance
$sql = "select X.aadhaar, X.typename, X.ambulanceid, patients.pname, patients.age, patients.bloodgroup, patients.patcontact, X.latitude, X.longitude from (select victims.aadhaar, victims.ambulanceid, victims.latitude, victims.longitude, emergency.typename from victims lEFT JOIN emergency ON victims.typeofemer=emergency.typeofemer) AS X LEFT JOIN patients ON patients.aadhaar = X.aadhaar where ambulanceid ='".$ambuid."';";
$result = mysqli_query($con, $sql);

$response = array();

$num = mysqli_num_rows($result);
//Default value for user
$user = '0';

//Scenario in which no victim had been assigned to ambulance
if($num == 0)
{	
	array_push( $response, array("user"=>$user, "latitude"=>'0', "longitude"=>'0'));
	echo json_encode($response);
}
//Scenario in which victim has been assigned to user
else
{		$row = mysqli_fetch_row($result);

		$aadhaar = $row[0];
		$typename = $row[1];
		$pname = $row[3];
		$age = $row[4];
		$bloodgroup = $row[5];
		$patcontact = $row[6];
		$lat= $row[7];
		$long= $row[8];
		
		array_push( $response, array("aadhaar"=>$aadhaar, "typename"=>$typename, "pname"=>$pname, "age"=>$age,"bloodgroup"=>$bloodgroup, "patcontact"=>$patcontact, "latitude"=>$lat, "longitude"=>$long));		
		echo json_encode($response);
}
?>