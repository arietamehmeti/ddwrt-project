<?php

include('Net/SSH2.php');

class Router implements JsonSerializable
{
    public $ssh_connection = null;
    public $server = array("sshport"=>"22", "user"=>"root", "password"=>"admin");
    public $ip;
    public $id;
    public $wan= 0;
    public $ssid =0;
    public $channel = 0;
    public $tx_power=-1;
    public $channel_bw=-1;
    public $connected_users = [];
    public $channels = array(
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

    function __construct($db_id,$host_ip){
        $this->id = $db_id;        
        $this->ip = $host_ip;
    }

    function getID(){
        return $this->id;
    }

    function getSSID(){
        if($this->ssid !== 0)
        return $this->ssid;

        $this->ssid =  $this->ssh_connection->exec('nvram get ath0_ssid');

        return $this->ssid;
    }

    function getChannelValue(){
        $channel = $this->getChannel();
        $channels = $this->channels;

        for($i = 0; $i< sizeof($channels); $i++){

            if($channels[$i] == $channel){
                return $i+1;
            }
        }
    }

    public function jsonSerialize() {

        return array(
            'wan' => $this->getWANIp(),
            'ip'=> $this->getLANIp(),
            'ssid'=> $this->getSSID(),
            'channel_bandwidth'=> $this->getChannelBW("ath0"),
            'channel'=> $this->getChannel(),
            'channel_value' => $this->getChannelValue(),
            'tx_power'=>$this->getTXPower(),
            'connected_users'=> $this->getConnectedUsers(),
            'id'=>$this->getID()
        );
    }

    function connectToRouter(){                     

        try{
            if($this->ssh_connection !== null)
                return true;

            $this->ssh_connection = new Net_SSH2($this->ip);

            if(!$this->ssh_connection->login($this->server["user"],$this->server["password"])){
                // exit('Login Failed');
                // echo "it got  stuck here" .$this->ssh_connection->login($this->server["user"],$this->server["password"]);
                return false;
            }        

        }
        catch(Exception $e){
            // echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }

        return  true;
    }

    function getConnection(){
        return $this->connection;
    }


    function setConnection($onnection){
        return $this->connection = $connection;
    }


    function getRouterConnection(){
        return  $this->ssh_connection;
    }

    function setChannel($channelValue){
        $this->ssh_connection->exec("nvram set ath0_channel=".$channelValue);
    }

    function getWANIp(){

        if($this->wan !== 0)
            return $this->wan;

        $this->wan = $this->ssh_connection->exec('nvram get wan_ipaddr');
        return $this->wan;
    }

    function getChannels(){
            return  $this->channels;
    }

    function setTXPower($power){
        // The command below shows the difference in the tx power in the router ip address
        $this->ssh_connection->exec("nvram set ath0_txpwrdbm=".$power);
    }

    function getLANIp(){
        return $this->ip;
    }

    function getChannelBW($channel_name){

        if($this->channel_bw != -1)
            return $this->channel_bw;

        $this->channel_bw = $this->ssh_connection->exec('nvram get ' .$channel_name .'_channelbw');
        
        return  $this->channel_bw ;
    }    

    function getTXPower(){
        // $this->ssh_connection->exec("nvram set ath0_txpwr=".$channelValue);
        // The command below shows the difference in the tx power in the router ip address
        if($this->tx_power != -1)
            return $this->tx_power;

        $this->tx_power = $this->ssh_connection->exec("nvram get ath0_txpwrdbm");
        return  $this->tx_power;
    }

    function getConnectedUsers(){

        // if($this->connected_users !== false)
        //     return $this->connected_users;

        $connected_users_str = $this->ssh_connection->exec("cat /proc/net/arp");

        preg_match_all("/([0-9]{1,3}\.){3}[0-9]{1,3}/",$connected_users_str, $out);

        $out = $out[0]; // Takes only the values that match the IP regex, instead of the string that sorrounded them as well.

        if(is_array($out) && sizeof($out) !== 0){

            $this->connected_users = $out;
        }

        return  $this->connected_users;
    }

    function setSSID($newSSID){

        $this->ssh_connection->exec('nvram set ath0_ssid=' .$newSSID);

        $this->ssid = $newSSID;
    }

    function getChannel(){

       if($this->channel !== 0){        
            return $this->channel;
       }

        $this->channel = $this->ssh_connection->exec('nvram get ath0_channel');

        return  $this->channel;
    }

    function getIP(){
        return $this->ip;
    } 

    function reboot(){
        $this->ssh_connection->exec("reboot");
    }

    function commit(){
        $this->ssh_connection->exec("nvram commit");
    }

    function getSiteSurvey(){

        $survey_result = $this->ssh_connection->exec('site_survey');

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
        }

    return $results_array;   

    }

    function getBasicInformation(){

        $router_data = [];

        $router_data["WAN IP"] = $this->getWANIp();

        $router_data["LAN IP"] =  $this->getLANIp();

        $router_data["SSID"]  = $this->getSSID();

        $router_data["Channel Bandwidth"]  =  $this->getChannelBW("ath0"); 

        $router_data["Ath0 Channel"]  = $this->getChannel();
        
        $router_data["TX Power"]  = $this->getTXPower();

        return $router_data;
    }
}

?>
