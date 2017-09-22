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
                <p id="moto"> Patient Management System </p>
            </div>
            
            <!--navigation bar-->
            <div class="nav_container">
                <ul class="nav_bar">
                    <li class="nav_item"> <a href="dashboard.php"> DASHBOARD </a> </li>
					<li class="nav_item"> <a class="active" href="active.php"> ACTIVE </a> </li>
					<li class="nav_item"> <a href="history.php"> HISTORY </a> </li>
					<li class="nav_item navbar-right"> <a href="server.php?REQID=8"> LOGOUT </a> </li>
                </ul>
            </div>
        </div>
		
        <!-- Content is blank by default -->
		
		<div class="container">
			<h3> Active Victims </h3>
			
			<table id='ajaxinput' class="table table-hover table-bordered">
				<tr>
					<th> Aadhaar </th>
					<th> Name </th>
					<th> Age </th>
					<th> BloodGroup </th>
					<th> Contact </th>
					<th> Emergency Type </th>
					<th> Ambulance ID </th>
					<th> Doctor ID </th>
					<th> Discharge </th>
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
		
		function discharge(aadhaar){
		
			var docid = $('#10'+aadhaar).val();
		
			$.ajax({ 
				url 	: 'server.php',
				type	: 'get',
				data 	: {'aadhaar' : aadhaar, 'REQID': 6},
				success : function(data) {
					$('#ROW'+aadhaar).remove();		
				},
				
				error	: function (xhr, ajaxOptions, thrownError) {
				var errorMsg = 'Unable to connect to internet! ' + xhr.responseText;
					$('#ajaxinput').html(errorMsg);
				}
			});
		
		}
		
		
		function tick(){
			
			//Perform Ajax request.
			$.ajax({
				url		: 'server.php',
				type	: 'get',
				data	: { 'REQID' : 5},
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
					
					//to temporarily store array of new aadhaars recieved 
					
					var i;
					for(i = 0; i<objsize; i++)
					{	parr[i] = dataobj[i].aadhaar;
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
							if(dataobj[j].ambulanceid == null)
							{
								row+="NOT ALLOCATED";
							}
							else
							{	
								row+=dataobj[j].ambulanceid;
							}
							row+="</td><td>";
							row+=dataobj[j].doctorid;
							row+="</td><td>";
							row+=" <button type='button' onclick='discharge(";
							row+= dataobj[j].aadhaar;
							row+=")' > Discharge </button> </td></tr>"
						
							
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