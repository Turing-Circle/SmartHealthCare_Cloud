<?php

require "connect.php";

//Input of credentials of user from android application
$aadhaar 	= 	$_POST["aadhaar"];
$password 	= 	$_POST["password"];

//Authentication of credentials
$sql= "select pname,aadhaar from patients where aadhaar ='".$aadhaar."' AND patpassword = '".$password."';";

//Query Execution and result set recieved
$result = mysqli_query($con,$sql);

//Response array declaration
$response=array();
 

if(mysqli_num_rows($result)>0)
{	
	$row = mysqli_fetch_row($result);
	
	//Storage of User's Name
	$pname = $row[0];
	$aadhaar = $row[1];
	$code = "login success";
	
	array_push( $response , array("code"=>$code,"pname"=>$pname, "aadhaar"=>$aadhaar) );
	
	//Response is sent back
	print_r(json_encode($response));
}
else
{	
	$code 		= "login failed";
	$message 	= "Username or Password Incorrect";
	
	array_push( $response , array("code"=>$code,"message"=>$message) );
	
	//Response is sent back
	echo json_encode($response);
}
	
mysqli_close($con);

?>