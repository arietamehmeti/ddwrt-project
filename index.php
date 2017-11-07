<!DOCTYPE html>
<html>

	<head>
		<title> Welcome to RouteVille</title>

	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">	  
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
	  <script src="jquery-3.2.1.min.js"></script>
	</head>

	<body>

		<div class="container">
		  <h2>Please enter the number of routers available:</h2>
		  <form method="post" action="main_page.php" >
		    <div class="form-group">
		      <input type="number" class="form-control" id="routers" placeholder="Number of Routers" name="routers" min="1" required>
		    </div>
		    <button type="submit" class="btn btn-default">Enter</button>
		  </form>
		</div>
		
		<?php
			function submitRouterNum(){
				$result = document.getElementById("routers").innerHTML;	

				echo $result;

			}			
		?>

	</body>
</html>