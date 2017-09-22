<?php
session_start();

//Database Login Credentials
$host 			= "test-db-instance.ch1f8mbps2qd.ap-south-1.rds.amazonaws.com";
$db_user 		= "testuser";
$db_password 	= "outlookcom";
$db_name 		= "testDB";
 
//Connectivity to Database
$con = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//Recieving Request ID 
$REQID = $_POST["REQID"];

if($REQID == NULL)
{	$REQID = $_GET["REQID"];
}

//Processing the code corresponding to Request ID
switch($REQID){
	
//Log-In Request Code	
case 1 :$userid 	= $_POST['user'];
		$password 	= $_POST['pass'];
		
		$sql = "select hpassword from hospital where hospitalid = '".$userid."';";
		$result = mysqli_query($con, $sql);

		if(mysqli_num_rows($result)==0)
		{	header("location: index.html?mess='Invalid ID!'");
		}	
		else
		{	
			$row=mysqli_fetch_row($result);
	
			if($password==$row[0])
			{
				$_SESSION["id"]=$userid;
				header("location: dashboard.php");
			}
			else 
			{	header("location: index.html?mess='Invalid Password!'");
			}
		}
		break;
	
//DashboardBack
case 2 :$sql = "select X.aadhaar, X.typename, X.ambulanceid, X.hospitalid, patients.pname, patients.age, patients.bloodgroup, patients.patcontact from (select victims.aadhaar, victims.ambulanceid, victims.hospitalid, emergency.typename from victims lEFT JOIN emergency ON victims.typeofemer=emergency.typeofemer) AS X LEFT JOIN patients ON patients.aadhaar = X.aadhaar where hospitalid IS NULL;";
		$result = mysqli_query($con, $sql);
		$numrows = mysqli_num_rows($result);

		$response = array();
		for($i=0; $i<$numrows ;$i++)
		{
			$row = mysqli_fetch_row($result);	
			array_push( $response , array("aadhaar"=>$row[0] , "ambulanceid"=>$row[2], "emertype"=>$row[1], "pname"=>$row[4], "age"=>$row[5],"bloodgroup"=>$row[6], "patcontact"=>$row[7]));
		}

		print_r(json_encode($response));
		break;
		

//Getdoctors		
case 3 :$sql = "select dname, aadhaar from doctors where availability='Y' AND numberofpats <5;";
		$result = mysqli_query($con, $sql);
		$numrows = mysqli_num_rows($result);
	
		$response = array();
		if($numrows>0)
		{	
			for($i=0; $i<$numrows; $i++)
			{
				$row= mysqli_fetch_row($result);
				array_push( $response , array("aadhaar"=>$row[1] , "dname"=> $row[0]));
			}
		}
		else
		{	array_push( $response , array("aadhaar"=>'0', "dname"=> '0'));
		}
		echo json_encode($response);
		break;

//SUBMITPAT		
case 4 ://Recieving Ambulance Credentials
		$aadhaar = $_GET["aadhaar"];
		$doctorid =	$_GET["doctorid"];
	
		$response = array();

		$sql 	= "select numberofpats from doctors where aadhaar = '".$doctorid."';";
		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_row($result);

		if($row[0]>=5)
		{
			$code	= "failure";
			array_push( $response, array("code"=>$code));
			echo json_encode($response);
		}
		else
		{
			$num = $row[0] + 1;
			$sql = "update doctors set numberofpats =".$num." where aadhaar = '".$doctorid."';";
			$result = mysqli_query($con, $sql);

			if($result!=0)
			{
				$sql 		= "update victims set doctorid='".$doctorid."', hospitalid='DELSHARDA001' where aadhaar='".$aadhaar."';";
				$result   	= mysqli_query($con, $sql);

				if($result==0)
				{	$code 	 = "failure";
					array_push( $response, array("code"=>$code));
					echo json_encode($response);
				}
				else
				{	$code 	 = "success";
					array_push( $response, array("code"=>$code));	
					echo json_encode($response);
				}
			}
			else
			{
				$code 	 = "failure";
				array_push( $response, array("code"=>$code));
				echo json_encode($response);
			}
		}
		break;
		
	
//ActiveBack
case 5 :$sql = "select X.aadhaar, X.typename, X.ambulanceid, X.hospitalid, patients.pname, patients.age, patients.bloodgroup, patients.patcontact, X.doctorid from (select victims.aadhaar, victims.ambulanceid, victims.hospitalid, victims.doctorid, emergency.typename from victims lEFT JOIN emergency ON victims.typeofemer=emergency.typeofemer) AS X LEFT JOIN patients ON patients.aadhaar = X.aadhaar where hospitalid IS NOT NULL;";
		$result = mysqli_query($con, $sql);

		$numrows = mysqli_num_rows($result);

		$response = array();


		for($i=0; $i<$numrows ;$i++)
		{
			$row = mysqli_fetch_row($result);	
			array_push( $response , array("aadhaar"=>$row[0] , "ambulanceid"=>$row[2], "emertype"=>$row[1], "pname"=>$row[4], "age"=>$row[5],"bloodgroup"=>$row[6], "patcontact"=>$row[7], "doctorid"=>$row[8]));
		}

		print_r(json_encode($response));
		break;
	
//Discharge		
case 6 :$aadhaar = $_GET['aadhaar'];
		$sql = "select X.aadhaar, X.typename, X.ambulanceid, patients.pname, patients.age, patients.bloodgroup, patients.patcontact, X.doctorid from (select victims.aadhaar, victims.ambulanceid, victims.hospitalid, victims.doctorid, emergency.typename from victims lEFT JOIN emergency ON victims.typeofemer=emergency.typeofemer) AS X LEFT JOIN patients ON patients.aadhaar = X.aadhaar where X.aadhaar ='".$aadhaar."';";
		$result = mysqli_query($con, $sql);
		$num= mysqli_num_rows($result);

		if($num>0)
		{	
			$row = mysqli_fetch_row($result);

			//Getting user data
			$typename 		= $row[1];
			$ambulanceid 	= $row[2];
			$pname 			= $row[3];
			$age 			= $row[4];
			$bloodgroup 	= $row[5];
			$patcontact 	= $row[6];
			$doctorid 		= $row[7];

			//Inserting user data in archive
			$sql = "insert into history values('".$aadhaar."', '".$pname."', ".$age.", '".$patcontact."', '".$bloodgroup."','".$doctorid."', '".$ambulanceid."', NULL, NULL, NULL ,'".$typename."', NULL);";
			$result = mysqli_query($con, $sql);
	
			//Deleting user panic
			$sql = "delete from victims where aadhaar = '".$aadhaar."';";
			$result = mysqli_query($con, $sql);
	
			//un-allocating doctor
			$sql = "select numberofpats from doctors where aadhaar='".$doctorid."';";
			$result = mysqli_query($con, $sql);
			$row = mysqli_fetch_row($result);
			$numberofpats = $row[0];
			$numberofpats--;
			$sql = "update doctors set numberofpats =".$numberofpats." where aadhaar ='".$doctorid."';";
			$result = mysqli_query($con, $sql);

			echo "success";
		}
		else
		{	echo "fatal error";
		}
		break;
		

//HISTORYBACK		
case 7 :$sql = "select * from history;";
		$result = mysqli_query($con, $sql);

		$numrows = mysqli_num_rows($result);
		$response = array();
		for($i=0; $i<$numrows ;$i++)
		{
			$row = mysqli_fetch_row($result);	
			array_push( $response , array("aadhaar"=>$row[0] , "pname"=>$row[1], "age"=>$row[2], "patcontact"=>$row[3], "bloodgroup"=>$row[4], "emertype"=>$row[10], "doctorid"=>$row[5], "ambulanceid"=>$row[6], "ID"=>$row[11]));	
		}

		print_r(json_encode($response));
		break;

//Logout Request		
case 8 :session_destroy();
		header("location: index.html");
		break;
		
default:session_destroy();
		header("location: index.html");
		break;
}

?>