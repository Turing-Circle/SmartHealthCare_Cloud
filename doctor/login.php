<?php

//Database Login Credentials
$host 			= "test-db-instance.ch1f8mbps2qd.ap-south-1.rds.amazonaws.com";
$db_user 		= "testuser";
$db_password 	= "outlookcom";
$db_name 		= "testDB";
 
//Connectivity to Database
$con = new mysqli($host, $db_user, $db_password, $db_name);


$user = $_POST["aadhaar"];
$pass = $_POST["password"];

//SQL query for authentication
$sql = "select dpassword from doctors where aadhaar='".$user."';";
$result   = mysqli_query($con, $sql);

$response = array();

//Unknown user Scenario
if( mysqli_num_rows($result)==0)
{	$code 	 = "Unknown";
	$message = "Invalid User";
	
	array_push( $response, array("code"=>$code, "message"=>$message));
	
	echo json_encode($response);
	
}
//Known user scenario
else
{	$row = mysqli_fetch_row($result);
	//Successful user scenario
	if($row[0]==$pass)
	{	
		$code 	= "Success";
		$message = "Login Successful";
		
		array_push( $response, array("code"=>$code, "message"=>$message));
		
		echo json_encode($response);
	}
	//Wrong Password Scenario
	else
	{	$code	= "Failure";
		$message = " Invalid Password";
		array_push( $response, array("code"=>$code, "message"=>$message));
		
		echo json_encode($response);
	}
}

?>