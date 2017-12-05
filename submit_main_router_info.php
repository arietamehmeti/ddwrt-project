<?php

include('mysql.php');

$main_router_table = "main_router";
$router_table = "router";


function deactivateActiveRouter(){
	global $conn;

	$sql = "UPDATE main_router set in_use= 'false' where in_use = 'true'";

	$query = mysqli_query($conn, $sql);

	if($query)
		{

			echo "<h1> 1. Rated success</h1>";				
		}
	else
		echo "Query error: " .mysqli_error($conn);
}


function insertMainRouter($main_ip, $in_use){
	global $conn;

	$sql = $sql = "INSERT INTO main_router (ip, in_use) VALUES ('$main_ip', true)";

	$query = mysqli_query($conn, $sql);

	if($query)
		{
			echo "<h1> 1. Rated success</h1>";		
		}
	else
		echo "Query error: " .mysqli_error($conn);

	$main_ip_id = (int) mysqli_insert_id($conn);	

	return $main_ip_id;

}

function insertRouter($ip, $main_router_id){
	global $conn;

	$sql = "INSERT INTO router (ip, main_router_id) VALUES ('$ip', $main_router_id)";
	$query = mysqli_query($conn, $sql);

	if($query)
		{
			echo "2.  Rated success";
		}
	else
		echo "Query error: " .mysqli_error($conn);

}


if(isset($_POST['request']) && $_POST['request'] =="submitRouterInfo"){
	
			$main_ip = $_POST['main_ip'];
			$ip_array = $_POST['router_ips'];

			deactivateActiveRouter();

			$main_router_id = insertMainRouter($main_ip, true);
			
			foreach ($ip_array as $ip) {
				insertRouter($ip, $main_router_id);
			}	

			$_SESSION['main_ip'] = $main_ip;
			$_SESSION['main_ip_id'] = $main_router_id;
	}
?>