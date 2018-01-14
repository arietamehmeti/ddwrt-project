<?php

include('mysql.php');

$main_router_table = "main_router";
$router_table = "router";


function deactivateActiveRouter(){
	global $conn;

	$sql = "UPDATE main_router set in_use=false where in_use =true";

	$query = mysqli_query($conn, $sql);

	if($query)
		{

			echo "<h1> deactivate Roouters : 1. Rated success</h1>";				
		}
	else{
		echo "Query error: " .mysqli_error($conn);
	}
}


function insertMainRouter($main_ip, $in_use){
	global $conn;

	$sql = "INSERT INTO main_router (ip, in_use) VALUES ('$main_ip', $in_use)";

	$query = mysqli_query($conn, $sql);

	if($query)
		{
			echo "<h1> insert mmain1. Rated success</h1>";		
		}
	else
		echo "insert main: Query error: " .mysqli_error($conn);

	$main_ip_id = (int) mysqli_insert_id($conn);	

	return $main_ip_id;

}

function deleteRouter($id, $router_type){
	
	global $conn;

	$sql = "DELETE FROM $router_type WHERE id = $id";

	$query = mysqli_query($conn, $sql);

	if($query)
		{
			echo "<h1> Delete: 1. Rated success</h1>";		
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
			echo "insert router: Rated success";
		}
	else
		echo "Query error: " .mysqli_error($conn);

}

function updateMainRouter($id, $ip, $in_use){
	global $conn;

	$sql = "UPDATE main_router SET ip='$ip', in_use=$in_use WHERE id=$id";

	$query = mysqli_query($conn, $sql);

	if($query)
		{
			echo "<h1>update main: Rated success</h1>";		
		}
	else
		echo "Query error: " .mysqli_error($conn);

	$main_ip_id = (int) mysqli_insert_id($conn);	

	return $main_ip_id;

}

function updateRouter($id, $ip, $main_router_id){
	
	global $conn;

	$sql = "UPDATE router SET ip='$ip', main_router_id=$main_router_id WHERE id=$id";

	$query = mysqli_query($conn, $sql);

	if($query)
		{
			echo "update router : Rated success";
		}
	else
		echo "Query error: " .mysqli_error($conn);

}

function getRouters($main_router_id){
    global $conn;

    // echo "them main router is" .$main_router_id;

    $sql = "SELECT id,ip FROM router WHERE main_router_id = '$main_router_id'";

    $query = mysqli_query($conn, $sql);

    if($query)
        {
            return $query;
        }
    else
        echo "Error" .mysqli_error($conn);

}

function getAllRouters(){

		global $conn;
		$table_str = "table";
		$results_str = "router";

		$array_results = [];

		$query = "SELECT * FROM router";

		if($res = mysqli_query($conn, $query)){

			$table_info = mysqli_fetch_fields($res);

			$res_array = mysqli_fetch_all($res,MYSQLI_ASSOC);

			
			$array_results[$table_str] =  $table_info;
			$array_results[$results_str] = $res_array;

			return $array_results;	
		}
		else{
			echo  mysqli_error($conn);
		}
	}

function getMainRouters(){

		global $conn;
		$table_str = "table";
		$results_str = "routers";

		$array_results = [];

		$query = "SELECT * from main_router ORDER BY id ASC";

		if($res =  mysqli_query($conn, $query)){

				$table_info = mysqli_fetch_fields($res);
				$res_array = mysqli_fetch_all($res, MYSQLI_ASSOC);
				
				$array_results[$table_str] =  $table_info;
				$array_results[$results_str] = $res_array;

			return $array_results;
		}
		else{

			echo  mysqli_error($conn);
		}
	}

function getMainActiveRouter(){

		global $conn;
		$table_str = "table";
		$results_str = "routers";

		$array_results = [];

		$query = "SELECT id,ip from main_router WHERE in_use=true";

		if($res =  mysqli_query($conn, $query)){			
			return $res;
		}
		else{

			return  mysqli_error($conn);
		}
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

if(isset($_POST['request']) && $_POST['request'] =="deleteRouter"){
	
		$router_id_array = $_POST['router_id_array'];
		$router_type = $_POST['router_type'];
	
		foreach($router_id_array as $id){
			deleteRouter($id, $router_type);
		}
	}	

if(isset($_POST['request']) && $_POST['request'] =="insert_router"){
	
		$ip = $_POST['ip'];
		$main_router_id = $_POST['main_router_id'];

		insertRouter($ip,$main_router_id);
	}

if(isset($_POST['request']) && $_POST['request'] =="insert_main_router"){
	
		$ip = $_POST['ip'];
		$in_use = $_POST['in_use'];

		if($in_use == 1)
			deactivateActiveRouter();

		insertMainRouter($ip,$in_use);
	}

if(isset($_POST['request']) && $_POST['request'] =="update_router"){
	
		$ip = $_POST['ip'];
		$id = $_POST['id'];
		$main_router_id = $_POST['main_router_id'];
	
		updateRouter($id,$ip,$main_router_id);
	}

if(isset($_POST['request']) && $_POST['request'] =="update_main_router"){
	
		$ip = $_POST['ip'];
		$id = $_POST['id'];
		$in_use = $_POST['in_use'];	
		
		if($in_use == 1)
			deactivateActiveRouter();

		updateMainRouter($id,$ip,$in_use);
	}	

?>