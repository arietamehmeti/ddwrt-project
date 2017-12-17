<!DOCTYPE html>

<html>

	<head>
		<title> Welcome to RouteVille</title>

	    <!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	    <!-- Bootstrap CSS -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

	</head>

	<body>

	<?php

	require('mysql.php');

	if (session_status() == PHP_SESSION_NONE) {
    session_start();
	}

	// define variables and set to empty values
	  // require('header.php');
	$result = true;
	unset($_SESSION['main_ip']);
	unset($_SESSION['main_ip_id']);

	if(!isset($_SESSION['main_ip']) || empty($_SESSION['main_ip'])){

		$res = mysqli_query($conn, "SELECT id,ip from main_router WHERE in_use = true");

		if($res){

			if (mysqli_num_rows($res) == 1){

				$row = mysqli_fetch_assoc($res);	

				$_SESSION['main_ip'] = $row['ip'];
				$_SESSION['main_ip_id'] = $row['id'];

				header("Location: main_page.php");
				
			}

		}
		else
		{
			echo mysqli_error($conn);
		}

	}
	else{
		header("Location: main_page.php");
	}

	?>

	<div class="container">

		<div class="signupbox mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			    <div class="panel-heading">
			      <div class="panel-title"></div>
			    </div>
			    <div class="panel-body">
			    	
			      <form class="form-horizontal" method="post" action="">

		          <div class="form-group">
		               	<label for="main_ip" class="col-md-3 control-label">Main Router IP Address</label>
		                <div class="col-md-9">
					                  <input type="text" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" class="form-control" id="main_ip" placeholder="Default Gateway IP Address" max="15" name="routers" min="1" required>
					                    <!-- <span class="error">*<?php echo $gateway_ip_error;?></span> -->
					                </div>
					          </div>

					          <div id="ip_addresses">						          	

					          </div>

					          <!-- <span class="error">* required field.</span> -->
					          <button class="btn btn-secondary" onclick="addIPField()">Add another IP</button>   
					         <input class="btn btn-primary" type="button" onclick="submitRouterInfo()" name="submit" value="register">				          
					      </form>

					  </div>
			</div>	

	</div>

		<script>

			 function submitRouterInfo(){	

				var main_ip = document.getElementById("main_ip").value;
				var router_ips =[];
				
				$(".ip_address").each(function() {
				    // alert($(this).val());
				    router_ips.push ($(this).val());
				});

				$.ajax({
					url: "queries.php",
				    type: "POST",
				    async: true,
				    data: {request: 'submitRouterInfo', main_ip: main_ip, router_ips: router_ips},
				    	    success: function (response) { 		
				    	    	alert(response);
				    	    	window.location.href = "main_page.php";
				    }
				});

			}	

			function addIPField(){

				var ip_pattern = "^([0-9]{1,3}\.){3}[0-9]{1,3}$";

				var form_div = document.createElement("div");
				form_div.setAttribute("class", "form-group");


				var label_em = document.createElement("label");
				label_em.setAttribute("class", "col-md-3 control-label");
				label_em.innerHTML = "Please write the static IP address of the router";


				var ip_input_em = document.createElement("input");
				ip_input_em.setAttribute("type", "text");
				ip_input_em.setAttribute("pattern", ip_pattern);
				ip_input_em.setAttribute("class", "form-control ip_address");
				ip_input_em.setAttribute("name", "router");

				ip_input_em.required = true;


				var sizing_form_div = document.createElement("div");
				sizing_form_div.setAttribute("class", "col-md-9")

				sizing_form_div.appendChild(ip_input_em);

				form_div.appendChild(label_em);
				form_div.appendChild(sizing_form_div);

				var ip_address_div = document.getElementById("ip_addresses");

				ip_address_div.appendChild(form_div);
				
			}								

		</script>

		<?php
		 include("resources.php");
		?>	

	</body>
</html>

