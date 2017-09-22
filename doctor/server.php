<?php

//Database Login Credentials
$host 			= "test-db-instance.ch1f8mbps2qd.ap-south-1.rds.amazonaws.com";
$db_user 		= "testuser";
$db_password 	= "outlookcom";
$db_name 		= "testDB";
 
//Connectivity to Database
$con = new mysqli($host, $db_user, $db_password, $db_name);

$docid = $_POST["docid"];

$sql = "select * from (select victims.aadhaar, victims.typeofemer, victims.doctorid, patients.age, patients.bloodgroup, patients.pname from victims LEFT JOIN patients ON victims.aadhaar = patients.aadhaar) AS T where T.doctorid = '".$docid."';";

$result = mysqli_query($con, $sql);

$response = array();

$num = mysqli_num_rows($result);

$aadhaar='0';

if($num==0)
{	
	array_push( $response, array("aadhaar"=>$aadhaar, "typeofemer"=>'0', "age"=>'0', "bloodgroup"=>'0', "name"=>'0'));
	echo json_encode($response);
}
else
{	for($i=0; $i<$num; $i++)
	{
		$row = mysqli_fetch_row($result);
		$aadhaar = $row[0];
		$typeofemer = $row[1];
		$age = $row[3];
		$bg = $row[4];
		$name = $row[5];
		
		array_push( $response, array("aadhaar"=>$aadhaar, "typeofemer"=>$typeofemer, "age"=>$age, "bloodgroup"=>$bg, "name"=>$name));
		
	}
	print_r(json_encode($response));
}

?>