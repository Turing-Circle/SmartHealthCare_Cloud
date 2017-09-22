<?php

//Database Login Credentials
$host 			= "test-db-instance.ch1f8mbps2qd.ap-south-1.rds.amazonaws.com";
$db_user 		= "testuser";
$db_password 	= "outlookcom";
$db_name 		= "testDB";
 
//Connectivity to Database
$con = new mysqli($host, $db_user, $db_password, $db_name);

//Recieving Ambulance Credentials
$user = $_POST["id"];
$pass =	$_POST["pass"];

//Query for authentication
$sql = "select apassword from ambulances where ambulanceid='".$user."';";
$result   = mysqli_query($con, $sql);

$response = array();

//Unknown user scenario
if( mysqli_num_rows($result) == 0)
{	$code 	 = "Unknown";
	$message = "Invalid User";
	
	array_push( $response, array("code"=>$code, "message"=>$message));
	
	echo json_encode($response);
	
}
else
{	$row = mysqli_fetch_row($result);
	//Successful Login Scenario
	if($row[0] == $pass)
	{	
		$code 	 = "Success";
		$message = "Login Successful";
		
		array_push( $response, array("code"=>$code, "message"=>$message));
		
		echo json_encode($response);
	}
	//Invalid Password Scenario
	else
	{
		$code	= "Failure";
		$message = "Invalid Password";
		array_push( $response, array("code"=>$code, "message"=>$message));
		
		echo json_encode($response);
	}
}
?>