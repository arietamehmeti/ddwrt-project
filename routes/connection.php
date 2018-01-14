<?php 

include $base_path.'/routes/Router.php';
include $base_path.'/db/mysql.php';
include $base_path.'/db/queries.php';

/* Once given the host number of the router, returns the site_surve with all the routers available information
* $host_num - The host number (the last IP that defines its position on the router).
*/

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$channels = array(
            2412,
            2417,
            2422,
            2427,
            2432,
            2437,
            2442,
            2447,
            2452,
            2457,
            2462
        );

$main_ip = "";
$main_ip_id = "";
$router_array = [];
$main_router = "";

    $res= getMainActiveRouter();

    if($row = mysqli_fetch_assoc($res)){
        $main_router =  $row;
        $main_ip = $row['ip'];
        $main_ip_id = $row['id'];

    }else{

        if(mysqli_num_rows($result) == 0){
            header("Location: index.php");
            die();
        }
    }

    $router_row = getRouters($main_ip_id);

    while ($row = mysqli_fetch_assoc($router_row)) {
        
        $router_info = [];
        
        $router_info['id'] =  $row['id'];    
        $router_info['ip'] =  $row['ip'];

        $router_array[$row['id']] = $router_info;
    }
 ?>