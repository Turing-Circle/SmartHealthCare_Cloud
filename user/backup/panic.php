<?php

require "connect.php";

function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
} 


$aadhaar 		= $_POST["aadhaar"];
$latitude 		= doubleval($_POST["latitude"]);
$longitude 		= doubleval($_POST["longitude"]);
$emergency 		= $_POST["emergency"];

$sql = "insert into victims values('".$aadhaar."','".$emergency."', NULL, NULL, NULL, ".$latitude.",".$longitude.");";

$result = mysqli_query($con, $sql);

$ambsql = " select * from ambulances where availability = 'Y';";
$result = mysqli_query($con, $ambsql);

$num = mysqli_num_rows($result);

$response = array();

if($num==0)
{	array_push( $response, array("code"=>"unavail"));
	echo json_encode($response);
}
else{

	$min=0.0;

	for($i=0; $i<$num; $i++)
	{
		$row= mysqli_fetch_row($result);
		
		$amblat = $row[2];
		$amblon = $row[3];
	
		$dist = distance($latitude, $longitude, $amblat, $amblon, "K");
	
		if($i==0)
		{	$min = $dist;
			$allocatedamb = $row[0];
		}
		else
		{
			if($dist<$min)
			{
				$min = $dist;			
				$allocatedamb = $row[0];
				
			}
		}
	}

	$sql_patalloc = "update ambulances set availability = 'N', pataadhaar ='".$aadhaar."' where ambulanceid = '".$allocatedamb."';";
	mysqli_query($con, $sql_patalloc);

	$sql_amballoc = "update victims set ambulanceid = '".$allocatedamb."' where aadhaar = '".$aadhaar."';";
	mysqli_query($con, $sql_amballoc);

	array_push( $response, array("code"=>'success', "ambulanceid"=> $allocatedamb));

	echo json_encode($response);
}
mysqli_close($con);
?>		
