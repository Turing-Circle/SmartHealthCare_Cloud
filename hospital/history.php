<?php

session_start();

if(empty($_SESSION["id"]))
{
	header("location: index.html");
}

?>
<html>  
  <head>
        <meta charset="UTF-8">
        <title>SHARDA PMS</title>
		
		 <!-- Bootstrap -->
		<link 	rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
		<link 	rel="stylesheet" type="text/css" href="css/dashstyle.css">
		
  </head>
    <body>
        <div class="banner">
            <!--logo for the website-->
            <div class="header">
                <p id="logo">SHARDA <span id="hospital">HOSPITAL</span> </p>
                <p id="moto"> Patient Management System (Powered by MEDIC) </p>
            </div>
            
            <!--navigation bar-->
            <div class="nav_container">
                <ul class="nav_bar">
                    <li class="nav_item"> <a href="dashboard.php"> DASHBOARD </a> </li>
					<li class="nav_item"> <a href="active.php"> ACTIVE </a> </li>
					<li class="nav_item"> <a class="active" href="history.php"> HISTORY </a> </li>
					<li class="nav_item navbar-right"> <a href="server.php?REQID=8"> LOGOUT </a> </li>
                </ul>
            </div>
        </div>
		
        <!-- Content is blank by default -->
	
		<div class="container">
			<h3> Past Victims </h3>
			
			<table id='ajaxinput' class="table table-hover table-bordered">
				<tr>
					<th> ID </th>
					<th> Aadhaar </th>
					<th> Name </th>
					<th> Age </th>
					<th> BloodGroup </th>
					<th> Contact </th>
					<th> Emergency Type </th>
					<th> Ambulance ID </th>
					<th> Doctor ID </th>
				</tr>
			</table>
		</div>
		<div id="mess"> </div>
        <!-- Include the JQuery library -->
		
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script>
		var patients = [];
		var parr = [];
		var psize=0;
		
		//When the page has loaded.
        $( document ).ready(function(){
			tick();
		});		
		
		function tick(){
			
			//Perform Ajax request.
			$.ajax({
				url		: 'server.php',
				type	: 'get',
				data	: {'REQID' : 7},
				success	: function(data){
					var dataobj = JSON.parse(data);
					
					Object.size = function(obj) {
						var size = 0, key;
						for (key in obj) {
							if (obj.hasOwnProperty(key)) size++;
						}
						return size;
					};
					
					var objsize = Object.size(dataobj);
					
					//to temporarily store array of new ID recieved 
					var i;
					for(i = 0; i<objsize; i++)
					{	parr[i] = dataobj[i].ID;
					}
					
					var flag=0;
					var j;
					
					for(j=0;j<objsize;j++)
					{	
						for(i=0; i<psize; i++)
						{
							if(parr[j]==patients[i])
							{	flag =1;
							}
						}
						
						if(flag==0)
						{	
							var row = "<tr id='ROW";
							row+= dataobj[j].aadhaar; 
							row+="'><td>";
							row+= dataobj[j].ID;
							row+="</td> <td>";
							row+= dataobj[j].aadhaar;
							row+="</td> <td>";
							row+= dataobj[j].pname;
							row+="</td> <td>";
							row+= dataobj[j].age;
							row+="</td> <td>";
							row+= dataobj[j].bloodgroup;
							row+="</td> <td>";
							row+= dataobj[j].patcontact;
							row+="</td> <td>";
							row+= dataobj[j].emertype;
							row+="</td> <td>";
							row+=dataobj[j].ambulanceid;
							row+="</td><td>";
							row+=dataobj[j].doctorid;
							row+="</td></tr>";
							
							$('#ajaxinput').append(row);
							patients[psize] = parr[j];
							psize++;
						}
						
						flag=0;
					}
					
				},
			
				error	: function (xhr, ajaxOptions, thrownError) {
				var errorMsg = 'Ajax request failed: ' + xhr.responseText;
					$('#ajaxinput').html(errorMsg);
				}
			});
			setTimeout('tick()',2000);
		}
		</script>
    </body>
</html>