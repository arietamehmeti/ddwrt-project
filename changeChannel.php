<?php

	// include 'mainInformation.php';
	include 'connectssh.php';

	$channelValue = $_REQUEST["channelValue"];
	$host_num = $_REQUEST["host_num"];
	
	$connection = new Router($host_num);

	$connection->connectToRouter();

	$connection->changeChannel($channelValue);

	echo "true";
?>