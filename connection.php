<?php 

include 'Router.php';
include 'mysql.php';
//Gets the results from the routers and dixplayes them on the screen

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

function changeChannelTable(){

        global $channels;
        global $router_array;
    
        echo "<table class='table'>";
            echo "<caption>Channel Change</caption>";            
            echo "<tr>";
                echo "<th>Router</th>";
                echo "<th>Channel/Frequency</th>";          
            echo "</tr>";

            echo "<tr>";

                createRouterOptions("channel");

                echo "<td> <select id='channel_select'>";

                    $array = $channels;
                    $i=0;
                    foreach($array as &$value){
                            echo "<option class='channel_option' value= '". $i ."'> ".($i+1) ." - " .$value ."</option>";
                            $i++;
                    }

                echo "</select > </td>";

            echo "</tr>";

        echo "</table>";

        echo "<button onclick='changeChannel()' class='btn'>Submit Changes</button>";

        echo "<br> <br>";
}


function createSSIDTable(){

        global $channels;
        global $router_array;

        
        echo "<table class='table'>";
        echo "<caption>SSID Change</caption>";
            echo "<tr>";
                echo "<th>Router</th>";
                echo "<th>SSID</th>";        
            echo "</tr>";

            echo "<tr>";

                createRouterOptions("ssid");

                echo "<td>";
                    echo "<input id='input_ssid' max='32' type='text'></input>";
                echo "</td>";

            echo "</tr>";

        echo "</table>";

        echo "<button onclick='changeSSID()' class='btn'>Submit Changes</button>";

        echo "<br> <br>";
}

function createRouterOptions($idName){

    global $router_array;

        echo "<td> <select id='router_select_".$idName ."''>";

            $array = $router_array;
            
            echo "<option class='".$idName ."' value='-1' selected> All </option>";
            
            $i = 0;
            
            foreach($array as $key=>$value){   
                    echo "<option class='".$idName ."' value= '".$key ."'> ".($i+1) ." - " .$value ."</option>";
                    $i++;
            }

        echo "</select > </td>";

}

function getOptionsFor($array){

    $result = "";
        $result .= "<td> <select>";

            $i = 0;
            
            foreach($array as $value){   
                    $result .= "<option value= '".$i ."'> ".($i+1) ." - " .$value ."</option>";
                    $i++;
            }

        $result .= "</select > </td>";

        return $result; 

}
function createTXPWRTable(){

        global $channels;
        global $router_array;

        echo "<table class='table'>";

        echo "<caption>TXPWR Change</caption>";
            echo "<tr>";
                echo "<th>Router</th>";
                echo "<th>TX Power</th>";        
            echo "</tr>";

            echo "<tr>";

                createRouterOptions("tx_power");

                echo "<td>";
                    echo "<input id='input_txpwr' max='32' type='text'></input>";
                echo "</td>";

            echo "<tr>";                

        echo "</table>";

        echo "<button onclick='changeTXPower()' class='btn'>Submit Changes</button>";

        echo "<br> <br>";
}

function createSelectOptions($array){
    echo "<td> <select>";

        for($i=0; $i < $channelSize; $i++){
                echo "<option class='channels' value= '".$array[$i] ."'> ".($i+1) ." - " .$array[$i] ."</option>";
        }

    echo "</select > </td>";
        
}

function showRouterInformation($connection){

    global $channels;

    $id = $connection->getID();

    $router_data = $connection->getBasicInformation();

        echo "<table class='table' id='table_$id'>";

            echo "<caption>Router" . $connection->getSSID() ."</caption>";

            echo "<tr>";

                foreach($router_data as $key=>$value){                                
                        echo "<th>$key</th>";
                }

            echo "<th>Connected Users</th>";

            echo "</tr>";

            echo "<tr>";
            
                foreach ($router_data as $router_info) {        
                        echo "<td>$router_info</td>";         
                }

                $connected_users_arr = $connection->getConnectedUsers();           

                // preg_match_all("/([0-9]{1,3}\.){3}[0-9]{1,3}/",$connected_users, $out);

                // $out = $out[0];

                // if(is_array($out) && sizeof($out) !== 0){

                //     $connected_users = getOptionsFor($out);
                //     echo $connected_users;
                // }

                echo getOptionsFor($connected_users_arr);

            echo "</tr>";

        echo "</table>";
    
}

/* Once given the host number of the router, returns the site_surve with all the routers available information
* $host_num - The host number (the last IP that defines its position on the router).
*/

function showSiteSurvey($connection){

    $id = $connection -> getId();

    $survey_result = $connection->getSurveyResult();

    // echo "The resultts is " .$survey_result;

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

        $temp =preg_replace("#\bSSID#","\"SSID\"", $temp);
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

    echo "<caption>Site Survey</caption>";

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
        echo "<td>" .@$survey_data->SSID ."</td>";
        echo "<td>" .@$survey_data->channel."</td>";
        echo "<td>" .@$survey_data->frequency."</td>";
        echo "<td>" .@$survey_data->rssi."</td>";
        echo "<td>" .@$survey_data->noise."</td>";
        echo "<td>" .@$survey_data->beacon."</td>";
        echo "<td>" .@$survey_data->dtim."</td>";
        echo "<td>" .@$survey_data->rate."</td>";
        echo "<td>" .@$survey_data->enc."</td>";
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
$connections = array();

if(isset($_SESSION['main_ip_id']) && !empty($_SESSION['main_ip_id'])) {

    $main_ip = $_SESSION['main_ip'];

    $main_ip_id = $_SESSION["main_ip_id"];
}else{

    $res = mysqli_query($conn, "SELECT id,ip from main_router WHERE in_use = true");

    if($row = mysqli_fetch_row($res)){
        $main_ip = $row['ip'];
        $main_ip_id = $row['id'];
    }else{
            echo  mysqli_error($conn);
    }

}
    $router_row = getRouters($main_ip_id);

    while ($row = mysqli_fetch_assoc($router_row)) {
        $router_array[$row['id']] = $row['ip'];
    }

changeChannelTable();
createSSIDTable();
createTXPWRTable();

$router_array_length =count($router_array); 


if($router_array_length != 0){

     foreach ($router_array as $key=>$router_ip) {

        $connection = new Router($key,$router_ip);
        
        $connection->connectToRouter();

        if($connection !== false){
            $connections[$key]= $connection;
            showRouterInformation($connection);
        }        
    }   
}else{
    echo "<h4>There are no routers connected, or registered. </h4>";
}


// showSiteSurvey($connections[12]);

// echo "The connected devices are" .$connections[12]->getConnectedDevices();

//  NEEDS THE OBJECTT INSTEAD OF THE INTEGER OF THE HOST

 ?>