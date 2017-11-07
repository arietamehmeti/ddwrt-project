<!DOCTYPE html>
<html>
<head>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	

	<title></title>
</head>
<body>
<!-- 
<select onChange="changeChannel(this)">

  <option value="2412">1- 2412 </option>
  <option value="2417">2 - 2417</option>
  <option value="2422">3 - 2422</option>
  <option value="2427" >4 - 2427</option>
  <option value="2432" >5 - 2432</option>
  <option value="2437" >6 - 2437</option>
  <option value="2442" >7 - 2442</option>
  <option value="2447" >8 - 2447</option>
  <option value="2452" >9 - 2452</option>
  <option value="2457" >10 - 2457</option>
  <option value="2462" >11 - 2462</option>

</select> -->

		<!-- <table id="survey_results"></table> -->

<script type="text/javascript">

	    function changeChannel(variable){
	        alert(variable.id);
	       
	        var xhttp = new XMLHttpRequest();

	        if(this.readyState == 4 && this.status == 200){
	        	alert("value has been changed");
	        }

	        xhttp.open("GET", "changeChannel.php?channelValue=" + variable.value + "&host_num=" + variable.id , true);
	        xhttp.send();
	    }
	
</script>

	<?php
		include("connection.php")
		?>

</body>
</html>