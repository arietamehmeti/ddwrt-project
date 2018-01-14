<!DOCTYPE html>

<html>
	<head>
		<title>DDWRT Router Management</title>

		<?php

			if (session_status() == PHP_SESSION_NONE) {
		    session_start();
			}

			$base_path = __DIR__;

			$url = $_SERVER['REQUEST_URI']; //returns the current URL
			$parts = explode('/',$url);
			$base_path_url = $_SERVER['SERVER_NAME'];
			for ($i = 0; $i < count($parts) - 1; $i++) {
			 $base_path_url .= $parts[$i] . "/";
			}

			$base_path_url = "http://".$base_path_url;
			$_SESSION["base_path"] = $base_path;
			$_SESSION["base_path_url"] = $base_path_url;

			require "includes/header_resources.php";	
		 ?>

    	<link rel="stylesheet" href="css/index.css">

	</head>

	<body>

    <div class="container">

	<?php  
		require("includes/bootstrap_resources.php");
		require "includes/navigation_bar.php";
	?>

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

	require("db/mysql.php");

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

				header("Location: ".$base_path_url."routes/main_page.php");				
			}

		}
		else
		{
			echo mysqli_error($conn);
		}

	}
	else{
		header("Location: ".$base_path_url."routes/main_page.php");
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
				});

				$.ajax({
					url: "db/queries.php",
				    type: "POST",
				    async: true,
				    data: {request: 'submitRouterInfo', main_ip: main_ip, router_ips: router_ips},
				    	    success: function (response) { 		
				    	    	window.location.href = base_path_url "routes/main_page.php";
				    },
				    error: function(XMLHttpRequest, textStatus, errorThrown) { 
				       alert("Request failed, please try again");
				    } 					    
				});

			}	

			$("#router_form").on("submit", function(event){
				event.preventDefault();
				submitRouterInfo();
			});

			var base_path_url = <?php echo json_encode($base_path_url); ?>;
		</script>

	</body>
</html>

