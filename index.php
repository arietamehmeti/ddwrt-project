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

    <div class="container">

      <form class="form-signin" onsubmit="submitRouterInfo()">
        <h2 class="form-signin-heading text-center heading">Router Registration</h2>
        <label for="main_ip" class="sr-only">Main Router IP</label>
        <input type="text"  placeholder="Main Router IP" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" class="form-control" id="main_ip" max="15" name="main_router" min="1" required>     
         	
     	<div id="ip_addresses">				          	

      	</div>      

  		<div class="singin-buttons text-center">
        	<button class="btn btn-secondary " onclick="addIPField()">Add another IP</button>   
        	<input class="btn btn-primary" type="submit" name="submit" value="Register Routers">  		

  		</div>
     
      </form>

    </div>

    <style type="text/css">
    	
body {
		  padding-top: 40px;
		  padding-bottom: 40px;
		  background-color: #eee;
		}

.text-center {
  text-align: center;
}

		.form-signin {
		  max-width: 330px;
		  padding: 15px;
		  margin: 0 auto;
		}

		.form-signin .form-signin-heading {
		  margin-bottom: 20px;
		}

		.form-signin .form-control {
		  position: relative;
		  margin-bottom: 10px;
		  margin-top: 10px;
		  box-sizing: border-box;
		  height: auto;
		  padding: 10px;
		  font-size: 16px;
		}
		.form-signin .form-control:focus {
		  z-index: 2;
		}
		.form-signin input[type="text"] {
		  margin-bottom: 10px;
		  margin-top: 10px;
		  border-bottom-right-radius: 0;
		  border-bottom-left-radius: 0;
		}  	
    </style>
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

				var label_em = document.createElement("label");
				label_em.setAttribute("class", "sr-only control-label");				

				var ip_input_em = document.createElement("input");
				ip_input_em.setAttribute("type", "text");
				ip_input_em.setAttribute("pattern", ip_pattern);
				ip_input_em.setAttribute("placeholder", "IP address");
				ip_input_em.setAttribute("class", "form-control ip_address");
				ip_input_em.setAttribute("name", "router");
				ip_input_em.setAttribute("mas", 15);

				ip_input_em.required = true;

				form_div.appendChild(label_em);
				form_div.appendChild(ip_input_em);

				var ip_address_div = document.getElementById("ip_addresses");

				ip_address_div.appendChild(form_div);
				
			}								

		</script>

		<?php
		 include("resources.php");
		?>	

	</body>
</html>

