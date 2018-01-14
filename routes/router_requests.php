<?php

	include 'Router.php';
	// include 'mainInformation.php';
	$change="change";
	$all="all";
	$us="_";

function change_channel($connection, $channel){

		$connection->setChannel($channel);
}

function change_channel_value($connection, $channelValue){
	return $channelValue;
}

function change_ssid($connection, $ssid_value){
		$connection->setSSID($ssid_value);
		// return $connection;
}

function change_tx_power($connection, $power_value){

		$connection->setTXPower($power_value);
}

function commitNReboot($connection){
	
	$connection->commit();
	$connection->reboot();
}

function establishConnection($connection){

		// Create and start timer firing after 2 seconds
		for($i = 0; $i<3; $i++){
			$connection_result = $connection->connectToRouter();
			if($connection_result !== false){
				echo true;
			}else{
				echo false;
			}			
		}
}

function connectToRouter($router_ip, $router_id){

		$connection = new Router($router_id, $router_ip);

		$connection->connectToRouter();

		return $connection;
}

function checkSSIDChange($connection, $ssid_value){
	if($connection->getSSID() == $ssid_value)
		return true;
	else
		return false;
}


if(isset($_POST['request']) && $_POST['request'] =="change"){
	
		$json_changes_str = $_POST["json_changes"];
		$json_changes = json_decode($json_changes_str, true);
		$functionName="";

		$router_ip = $_POST["router_ip"];
		$router_id = $_POST["router_id"];

		$connection = connectToRouter($router_ip, $router_id);

		foreach($json_changes as $key=>$value){

			$functionName= $change .$us .$key;
			$pars = array($connection, $value);

			call_user_func_array($functionName, $pars);
		}

		$connection->commit();
		$connection->reboot();

		$json_changes["id"] = $router_id;

		echo json_encode($json_changes);

	}

if(isset($_POST['request']) && $_POST['request'] =="change_all"){
	
		$channel_value = $_POST["change_value"];
		$router_string_array = $_POST["router_array"];
		$json_changes_str = $_POST["json_changes"];
		$functionName="";

		$json_changes = json_decode($json_changes_str, true);		
		$router_array = json_decode($router_string_array, true);

		$updated_router = [];

		foreach ($router_array as $router_key => $router_value) {

			$router_ip =  $router_value['ip'];
			$router_id =  $router_value['id'];

			$connection = connectToRouter($router_ip, $router_id);	

			foreach($json_changes as $key=>$value){

				$functionName = $change . $us . $key;
				$pars = array($connection, $value);

				call_user_func_array($functionName, $pars);
			}

			$connection->commit();
			$connection->reboot();

			$updated_router[$router_id] = json_encode($connection);
		}

		echo json_encode($updated_router);
}

if(isset($_POST['request']) && $_POST['request'] =="get_router_info"){

		$router_ip = $_POST["router_ip"];
		$router_id = $_POST["router_id"];

		$connection = new Router($router_id, $router_ip);

		$connection_established = $connection->connectToRouter();

		if($connection_established == 1){
			echo json_encode($connection);	
		}
}

if(isset($_POST['request']) && $_POST['request'] =="get_site_survey"){

		$router_ip = $_POST["router_ip"];
		$router_id = $_POST["router_id"];

		$connection = new Router($router_id, $router_ip);

		$connection_established = $connection->connectToRouter();

		if($connection_established == 1){
			$site_survey = $connection->getSiteSurvey();
			echo json_encode($site_survey);
		}
}

if(isset($_POST['request']) && $_POST['request'] =="connect_to_router"){

		$router = $_POST["router"];
		$router_json =$router;

		$router_ip =  $router_json['ip'];
		$router_id =  $router_json['id'];

		$connection = new Router($router_id, $router_ip);

		$connection_established = $connection->connectToRouter();

		if($connection_established == 1){
			echo true;
		}
}
?>