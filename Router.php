<?php

include('Net/SSH2.php');
// include("mainInformation.php");

class Router implements JsonSerializable
{
    public $ssh_connection = null;
    public $server = array("ip"=>"", "sshport"=>"22", "user"=>"root", "pw"=>"admin");
    public $ip;
    public $id;
    public $ssid =0;
    public $channel = 0;
    public $tx_power=-1;
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
        $this->server['ip'] = $host_ip;
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

    public function jsonSerialize() {
        return array(
            'ip'=>$this->ip,
            'id'=>$this->id,
            'ssid'=>$this->ssid,
            'channel'=>$this->channel,
            'txpwr'=>$this->tx_power
        );
    }

    function connectToRouter(){                     

        try{

            if($this->ssh_connection !== null)
                return $this->ssh_connection;

            $ip =  $this->server['ip'];

            $this->ssh_connection = new Net_SSH2($ip);

            if (!$this->ssh_connection->login( "root", "admin")) {
                // exit('Login Failed');
                return false;         
            }

            return  $this->ssh_connection;            

        }
        catch(Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

    }

    function getRouterConnection(){
        return  $this->ssh_connection;
    }

    function setChannel($channelValue){
        $this->ssh_connection->exec("nvram set ath0_channel=".$channelValue);
        $this->ssh_connection->exec("nvram commit");
        $this->ssh_connection->exec("reboot");
    }

    function getWANIp(){
        return $this->ssh_connection->exec('nvram get wan_ipaddr');
    }

    function getChannels(){
            return  $this->channels;
    }

    function setTXPower($power){
        // The command below shows the difference in the tx power in the router ip address
        $this->ssh_connection->exec("nvram set ath0_txpwrdbm=".$power);
        $this->ssh_connection->exec("nvram commit");
        $this->ssh_connection->exec("reboot");
    }

    function getLANIp(){
        return $this->ip;
    }

    function getChannelBW($channel_name){
        return $this->ssh_connection->exec('nvram get ' .$channel_name .'_channelbw');
    }    

    function getTXPower(){
        // $this->ssh_connection->exec("nvram set ath0_txpwr=".$channelValue);
        // The command below shows the difference in the tx power in the router ip address
        if($this->tx_power != -1)
            return $this->tx_power;

        $this->tx_power = $this->ssh_connection->exec("nvram get ath0_txpwrdbm");
        return  $this->tx_power;
    }

    function getConnectedDevices(){
        return  $this->ssh_connection->exec("cat /proc/net/arp");
    }  

    function setSSID($newSSID){

        $this->ssh_connection->exec('nvram set ath0_ssid=' .$newSSID);
        $this->ssh_connection->exec("nvram commit");
        $this->ssh_connection->exec("reboot");

        $this->ssid = $newSSID;
    }

    function getChannel(){

       if($this->channel !== 0){        
            return $this->channel;
       }

        $this->channel = $this->ssh_connection->exec('nvram get ath0_channel');

        return  $this->channel;
    }

    function commit(){
        $this->ssh_connection->exec("nvram commit");
    }

    function reboot(){
         $this->ssh_connection->exec("reboot");
    }

    function getIP(){
        return $this->ip;
    } 

    function getSurveyResult(){

        $survey_result = $this->ssh_connection->exec('site_survey');

        return $survey_result;
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
