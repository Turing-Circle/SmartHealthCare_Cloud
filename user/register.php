<?php

require "connect.php";

$aadhaar 			= $_POST["aadhaar"];
$name				= $_POST["name"];
$age				= intval($_POST["age"]);
$patcontact			= $_POST["patcontact"];
$emercontactname	= $_POST["emercontactname"];
$emercontact 		= $_POST["emercontact"];
$bloodgroup			= $_POST["bloodgroup"];
$password			= $_POST["password"];

//Insert Query
$sql		= "insert into patients values('".$aadhaar."','".$name."',".$age.",'".$patcontact."','".$emercontactname."','".$emercontact."','".$bloodgroup."','".$password."');";

//Insert Query Execution
$result 	= mysqli_query($con,$sql);

$response 	= array();

if($result)
{	
	$code		=	"reg_success";
	$message	=	"Registration Successful!";
	
	array_push( $response , array("code"=>$code , "message"=>$message) );
	
	//Response Sent Back in JSON Array format
	echo json_encode($response);
	
}
else
{
	$code		="reg_failed";
	$message	="Registration Failed!";
	
	array_push( $response , array("code"=>$code , "message"=>$message) );
	
	//Response Sent back in JSON array format
	echo json_encode($response);
}

mysqli_close($con);
?>		