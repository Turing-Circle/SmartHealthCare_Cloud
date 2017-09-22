<?php
//Database Login Credentials
$host 			= "test-db-instance.ch1f8mbps2qd.ap-south-1.rds.amazonaws.com";
$db_user 		= "testuser";
$db_password 	= "outlookcom";
$db_name 		= "testDB";
 
//Connectivity to Database
$con = new mysqli($host, $db_user, $db_password, $db_name);

//Recieving Ambulance Credentials
$id 		= $_POST["id"];
$avail		= $_POST["avail"];
$latitude   = doubleval($_POST["latitude"]);
$longitude 	= doubleval($_POST["longitude"]);
	
$sql = "update ambulances set availability ='".$avail."', latitude =".$latitude.", longitude=".$longitude." where ambulanceid = '".$id."';";

$result   = mysqli_query($con, $sql);

$response = array();

if($result)
{	$code 	 = "Failure";
	$message = "Request not registered";
	
	array_push( $response, array("code"=>$code, "message"=>$message));
	
	echo json_encode($response);
	
}
else
{	$code 	 = "Success";
	$message = "Request Registered";
		
	array_push( $response, array("code"=>$code, "message"=>$message));
		
	echo json_encode($response);
}

?>