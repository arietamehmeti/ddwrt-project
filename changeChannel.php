<?php

	// include 'mainInformation.php';

if(isset($_POST['request']) && $_POST['request'] =="changeChannel"){
	
	include 'Router.php';

		$channelValue = $_POST["channel_value"];
		$host_num = $_POST["router_ip"];
		
		$connection = new Router($host_num);

		$connection->connectToRouter();

		$connection->changeChannel($channelValue);

		echo "true";

	}	
?>