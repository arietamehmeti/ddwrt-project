<?php 

include 'Router.php';
include 'mysql.php';
//Gets the results from the routers and dixplayes them on the screen

function getRouters($main_router_id){
    global $conn;

    // echo "them main router is" .$main_router_id;

    $sql = "SELECT ip FROM router WHERE main_router_id = '$main_router_id'";

    $query = mysqli_query($conn, $sql);

    if($query)
        {
            return $query;
        }
    else
        echo "Error" .mysqli_error($conn);

}

function selectMainRouter($ip, $main_router_id){
    global $conn;

    $sql = "SELECT id FROM router WHERE ip=$main_router_id";
    $query = mysqli_query($conn, $sql);

    if($query)
        {
            echo "2.  Rated success";
        }
    else
        echo "Query error: " .mysqli_error($conn);

}


function showRouterInformation($connection){

    // $ssh = connectToRouter($host_num);
    global $channels;

    // Gets the survey_results and formats it to JSON

     $router_data = $connection->getBasicInformation();

        // Open the table
        echo "<table class='table'>";
        echo "<tr>";
            echo "<th>WAN IP address</th>";
            echo "<th>LAN IP address</th>";
            echo "<th>wl0_ssid</th>";
            echo "<th>ath1_channelbw</th>";
			echo "<th>ath0_channel</th>";

        echo "</tr>";

        // Cycle through the array
        echo "<tr>";

        foreach ($router_data as $router_info) {
            // Output a row
  
            echo "<td>$router_info</td>";         
        }   

        $data = $connection->getRouterChannel();
        // echo "The channel is " . $data;
        // array_push($router_data, $data);

        $id = $connection->getIP();

        echo "<td> <select id='" .$id ."' onChange='changeChannel(this)'>";

        $channelSize = count($channels);

        for($i=0; $i < $channelSize; $i++){
            if($channels[$i] != $data)
                echo "<option class='channels' value= '".$channels[$i] ."'> ".($i+1) ." - " .$channels[$i] ."</option>";
            else
                echo "<option class='channels' value= '".$channels[$i] ."' selected > ".($i+1) ." - " .$channels[$i] ."</option>";
        }

        echo "</select > </td>";

        echo "</tr>";
        // Close the table
        echo "</table>";
    
}

/* Once given the host number of the router, returns the site_surve with all the routers available information
* $host_num - The host number (the last IP that defines its position on the router).
*/

function showSiteSurvey($connection){

    // $ssh = connectToRouter($host_num);

    $ssh = $connection -> getRouterConnection();

    // $survey_result = $ssh->exec('site_survey');
    $survey_results = $connection->getSurveyResult();

    $results_parsed = preg_replace("#\[ [0-9]+\]# ", "} {", $survey_result);

    $results_parsed .= "}";

    $results_parsed = substr($results_parsed, 1, strlen($results_parsed));

    $initialPos = 0;

    $results_array = [];
    $temp;

    while( strlen($results_parsed) > 0 ){


        $temp = substr($results_parsed, $initialPos, $position = strpos($results_parsed,"}") + 1);
        // echo $temp ."<br>" .strlen($results_parsed) . "<br>";

        $results_parsed = substr($results_parsed, $position, strlen($results_parsed));

        // echo "the results remaining are " .$results_parsed ."<br><br>";

        $temp = preg_replace("#\[#", ":\"", $temp); 
        $temp =preg_replace("#\]#", "\",", $temp);  

        $temp =preg_replace("# SSID#","\"SSID\"", $temp);
        $temp =preg_replace("#BSSID#","\"BSSID\"", $temp);
        $temp =preg_replace("#channel#","\"channel\"", $temp);
        $temp =preg_replace("#frequency#","\"frequency\"", $temp);
        $temp =preg_replace("#rssi#","\"rssi\"", $temp);
        $temp =preg_replace("#noise#","\"noise\"", $temp);
        $temp =preg_replace("#beacon#","\"beacon\"", $temp);
        $temp =preg_replace("#cap#","\"cap\"", $temp);
        $temp =preg_replace("#dtim#","\"dtim\"", $temp);
        $temp =preg_replace("#rate#","\"rate\"", $temp);
        $temp =preg_replace("#enc:#","\"enc\":", $temp);

        // Unfortunately, the , } code in the end is not replaced by the regular expression, so I just cut it out of the variable, and add an "}" in the end.
        $temp = substr($temp, 0, strlen($temp) - 3);

        $temp.= "}";

         $temp = json_decode($temp);    

        array_push($results_array, $temp );     

         // echo json_encode($temp) ."<br>";

    }


    // Open the table

    echo "<h3> Available routers </h3>";


    echo "<table class='table'>";
    echo "<tr>";
        echo "<th>SSID</td>";
        echo "<th>Channel</td>";
        echo "<th>Frequency</td>";
        echo "<th>RSSI</td>";
        echo "<th>Noise</td>";
        echo "<th>Beacon</td>";
        echo "<th>DTIM</td>";
        echo "<th>Rate</td>";
        echo "<th>ENC</td>";
    echo "</tr>";        

    // Cycle through the array
    foreach ($results_array as $survey_data) {

        // Output a row
        echo "<tr>";
        echo "<td>$survey_data->SSID</td>";
        echo "<td>$survey_data->channel</td>";
        echo "<td>$survey_data->frequency</td>";
        echo "<td>$survey_data->rssi</td>";
        echo "<td>$survey_data->noise</td>";
        echo "<td>$survey_data->beacon</td>";
        echo "<td>$survey_data->dtim</td>";
        echo "<td>$survey_data->rate</td>";
        echo "<td>$survey_data->enc</td>";
        echo "</tr>";
    }

    // Close the table
    echo "</table> <br>";    
}


// The code begins here !!
// ini_set("display_errors", 0); 

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
$router_array = [];

echo "The session is" .$_SESSION['main_ip_id'] ."<br>";

if(isset($_SESSION['main_ip_id']) && !empty($_SESSION['main_ip_id'])) {

    $router_row = getRouters($_SESSION['main_ip_id']);
    var_dump($router_row);

    while ($row = mysqli_fetch_assoc($router_row)) {
        array_push($router_array, $row['ip']);      
    }

}

if(isset($_SESSION['main_ip_id']) &&  !empty($_SESSION['main_ip_id'])) {

    $main_ip_id = $_SESSION["main_ip_id"];
}

echo "<div class='container'>";

$connections = [];

$router_array_length =count($router_array); 

for($i = 0; $i < $router_array_length; $i++){

	$connection = new Router($router_array[$i]);
	$connection->connectToRouter();

	array_push($connections, $connection);

    showRouterInformation($connection);

}

// showSiteSurvey(3);
//  NEEDS THE OBJECTT INSTEAD OF THE INTEGER OF THE HOST
echo "</div>";	

 ?>