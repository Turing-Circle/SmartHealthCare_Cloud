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
                    <li class="nav_item"> <a class="active" href="dashboard.php"> DASHBOARD </a> </li>
					<li class="nav_item"> <a href="active.php"> ACTIVE </a> </li>
					<li class="nav_item"> <a href="history.php"> HISTORY </a> </li>
					<li class="nav_item navbar-right"> <a href="server.php?REQID=8"> LOGOUT </a> </li>
                </ul>
            </div>
        </div>
		
        <!-- Content is blank by default -->
		
		<div class="container">
			<h3> Victims Status </h3>
			<div id='forms'> </div>
			
			<table id='ajaxinput' class="table table-hover table-bordered">
				<tr>
					<th> Aadhaar </th>
					<th> Name </th>
					<th> Age </th>
					<th> BloodGroup </th>
					<th> Contact </th>
					<th> Emergency Type </th>
					<th> Ambulance ID </th>
					<th> GET </th>
					<th> Allocate </th>
					<th> Submit </th>
				</tr>
			</table>
		</div>
		<div id="mess"> </div>
        
        <!-- Include the JQuery library -->
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script>
		var victims = [];
		var arr = [];
		var vsize=0;
		
		//When the page has loaded.
        $( document ).ready(function(){
			tick();
		});
        
		function fsubmit(aadhaar){
			var reqid = 4;
			var docid = $('#10'+aadhaar).val();
		
			$.ajax({ 
				url 	: 'server.php',
				type	: 'get',
				data 	: { 'aadhaar' : aadhaar , 'doctorid' : docid, 'REQID' : reqid },
				success : function(data) {
					$('#ROW'+aadhaar).remove();		
				},
				error	: function (xhr, ajaxOptions, thrownError) {
				var errorMsg = 'Unable to connect to internet! ' + xhr.responseText;
					$('#ajaxinput').html(errorMsg);
				}
			});
		
		}
		
		
		function getdoctors(element){
			var reqid = 3;
			
			$.ajax({
				url		: 'server.php',
				type	: 'get',
				data	: {'REQID' : reqid},
				success : function(data){
					var docobj = JSON.parse(data);
					
					Object.size = function(obj) {
						var size = 0, key;
						for (key in obj) {
							if (obj.hasOwnProperty(key)) size++;
						}
						return size;
					};
					
					var docobjsize = Object.size(docobj);
					
					//var opts = " ";
					var m =0;
					for(m=0; m<docobjsize; m++)
					{	
						var ele = document.getElementById(element);
						var option = document.createElement("option");
						option.text = docobj[m].dname;
						option.value= docobj[m].aadhaar;
						ele.add(option);
				
						/*opts += "<option value='";
						opts += docobj[m].aadhaar;
						opts += "'>";
						opts += docobj[m].dname;
						opts += "</option>"; */
					}
					
						
						
				//	$('#'+element).html(opts);
					
				},
				error	: function (xhr, ajaxOptions, thrownError) {
				var errorMsg = 'Ajax request failed: ' + xhr.responseText;
					$('#mess').html(errorMsg);
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
				data	: { 'REQID' : 2 },
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
					{	arr[i] = dataobj[i].aadhaar;
					}
					
					var flag=0;
					var j;
					
					for(j=0;j<objsize;j++)
					{	
						for(i=0; i<vsize; i++)
						{
							if(arr[j]==victims[i])
							{	flag =1;
							}
						}
						
						if(flag==0)
						{
							var forms = "<form id='";
							forms+= dataobj[j].aadhaar;
							forms+= "' onsubmit ='fsubmit(";
							forms+= dataobj[j].aadhaar;
							forms+=")' ></form>";
							
							$('#forms').append(forms);
							
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
							
							
							row+="<button type='button' form='";
							row+=dataobj[j].aadhaar;
							row+="' onclick='getdoctors(10";
							row+=dataobj[j].aadhaar;
							row+=")' > get </button> </td> <td> ";
							
							
							row+="<select name='doctors' id = '10";
							row+= dataobj[j].aadhaar;
							row+="' form = '";
							row+= dataobj[j].aadhaar;
							row+="'></td><td>";
							row+="<input type='submit' value='submit' form ='";
							row+=dataobj[j].aadhaar;
							row+="'></td> </tr>";
							
							$('#ajaxinput').append(row);
							victims[vsize] = arr[j];
							vsize++;
						}
						
						flag=0;
					}
					
				},
			
				error	: function (xhr, ajaxOptions, thrownError) {
				var errorMsg = 'Unable to connect to internet! ' + xhr.responseText;
					$('#ajaxinput').html(errorMsg);
				}
			});
			setTimeout('tick()',2000);
		}
        </script>
    </body>
</html>