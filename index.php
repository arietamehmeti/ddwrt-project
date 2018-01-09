<!DOCTYPE html>

<html>

	<head>
		<title> Welcome to RouteVille</title>

	    <!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <link rel="stylesheet" href="css/index.css">
	    <!-- Bootstrap CSS -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

	</head>

	<body>

    <div class="container">
    	<?php include("resources.php") ?>

      <form class="form-signin" id="router_form">
        <h2 class="form-signin-heading text-center heading my-5">Router Registration</h2>
        <label for="main_ip" class="sr-only">Main Router IP</label>
        <input type="text"  placeholder="Main Router IP" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" class="form-control mb-4" id="main_ip" max="15" required>    
         	
     	<div id="ip_addresses">			          	

      	</div>
      	<!-- <button type="button" id="remove_button" class="btn btn-outline-danger">X</button>     -->
      </form>

  		<div class="singin-buttons text-center">
        	<button class="btn btn-secondary mr-1" onclick="addIPField()">Add another IP</button>   
        	<input class="btn btn-primary ml-1" type="submit" form="router_form" name="submit" value="Register Routers">  		
  		</div>  	
    </div>

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

			function addIPField(){

				var ip_input_em = $("#main_ip").clone();
				var remove_button = $("#remove_button").clone();

				$(remove_button).removeProp("id");

				$(ip_input_em).val("");
				$(ip_input_em).attr("class","form-control router_ip");
				$(ip_input_em).attr("placeholder", "Router IP");

				var ip_address_div = $("#ip_addresses");

				$(ip_address_div).append(ip_input_em);
			}

			 function submitRouterInfo(){	

				var main_ip = $("#main_ip").val();
				var router_ips =[];

				$('.router_ip').each(function(key, obj) {
				    router_ips.push($(this).val());
				    alert($(this).val());
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

			$("#router_form").on("submit", function(event){
				event.preventDefault();
				submitRouterInfo();
			});

		</script>

		<?php
		 include("resources.php");
		?>	

	</body>
</html>

