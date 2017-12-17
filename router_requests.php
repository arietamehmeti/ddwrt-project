<?php

	include 'Router.php';
	// include 'mainInformation.php';

function changeChannel($channelValue, $host_ip, $host_id){

		$channelValue = $_POST["change_value"];
		
		$connection = new Router($host_id, $host_ip);

		$connection->connectToRouter();

		$connection->setChannel($channelValue);

		echo "true";
}

function changeSSID($ssid_value, $host_ip, $host_id){

		$connection = new Router($host_id, $host_ip);

		$connection->connectToRouter();

		$connection->setSSID($ssid_value);

		echo "true";
}

function changeTXPower($power_value, $host_ip, $host_id){

		$connection = new Router($host_id, $host_ip);

		$connection->connectToRouter();

		$connection->setTXPower($power_value);

		echo "true";
}

if(isset($_POST['request']) && $_POST['request'] =="change_channel"){
	
		$channel_value = $_POST["change_value"];
		$host_ip = $_POST["router_ip"];
		$host_id = $_POST["router_id"];

		changeChannel($channel_value,$host_ip, $host_id);
	}

if(isset($_POST['request']) && $_POST['request'] =="change_all_channel"){
	
		$channel_value = $_POST["change_value"];
		$router_string_array = $_POST["router_array"];

		$router_array = json_decode($router_string_array, true);

		foreach ($router_array as $key => $value) {

			echo "the value is " .$value['ip'];

			changeChannel($channel_value, $value['ip'], $value['id']);
		}
}

if(isset($_POST['request']) && $_POST['request'] =="change_tx_power"){

		$txpwr_value = $_POST["change_value"];
		$host_ip = $_POST["router_ip"];
		$host_id = $_POST["router_id"];
		
		changeTXPower($txpwr_value, $host_ip, $host_id);

}

if(isset($_POST['request']) && $_POST['request'] =="change_all_tx_power"){

		$txpwr_value = $_POST["change_value"];
		$router_string_array = $_POST["router_array"];

		$router_array = json_decode($router_string_array, true);
		
		foreach ($router_array as $key => $value) {
			changeTXPower($txpwr_value, $value['ip'], $value['id']);
		}

}

if(isset($_POST['request']) && $_POST['request'] =="change_ssid"){

		$ssid_value = $_POST["change_value"];
		$host_ip = $_POST["router_ip"];
		$host_id = $_POST["router_id"];
		
		changeSSID($ssid_value, $host_ip, $host_id);

	}

if(isset($_POST['request']) && $_POST['request'] =="change_all_ssid"){

		$ssid_value = $_POST["change_value"];
		$router_string_array = $_POST["router_array"];

		$router_array = json_decode($router_string_array, true);
		
		$router_array = json_decode($router_string_array, true);

		foreach ($router_array as $key => $value) {

			changeSSID($ssid_value, $value['ip'], $value['id']);
		}
	}	


?>