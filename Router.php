<?php

include('Net/SSH2.php');
// include("mainInformation.php");

class Router
{
    public $ssh_connection = null;
    public $server = array("ip"=>"", "sshport"=>"22", "user"=>"root", "pw"=>"admin");
    private $ip;
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

    function __construct($host_ip){
        $this->server['ip'] = $host_ip;
        $this->ip = $host_ip;
    }

    function connectToRouter(){                     

        if($this->ssh_connection !== null)
            return $this->ssh_connection;

        $ip =  $this->server['ip'];

        $this->ssh_connection = new Net_SSH2($ip);
        

        echo "the ips is " . $ip;

        if (!$this->ssh_connection->login( "root", "admin")) {
            exit('Login Failed');            
        }

        return  $this->ssh_connection;
    
    }

    function getRouterConnection(){
        return  $this->ssh_connection;
    }

    function changeChannel($channelValue){
        $this->ssh_connection->exec("nvram set ath0_channel=".$channelValue);
        $this->ssh_connection->exec("nvram commit");
        $this->ssh_connection->exec("reboot");
    }

    function getBasicInformation(){

        $router_data = [];

        $data =  $this->ssh_connection->exec('nvram get wan_ipaddr');

        array_push($router_data, $data);

        $data =  $this->ssh_connection->exec('nvram get lan_ipaddr');
        array_push($router_data, $data);


        $data =  $this->ssh_connection->exec('nvram get wl0_ssid');
        array_push($router_data, $data);

        $data =  $this->ssh_connection->exec('nvram get ath1_channelbw');
        array_push($router_data, $data);

        return $router_data;
    }

    function getChannels(){
        return  $this->channels;
    }

    function changeSSID($newSSID){

        $this->ssh_connection->exec('nvram set wl_ssid=' .$newSSID);
        $this->ssh_connection->exec("nvram commit");
        $this->ssh_connection->exec("reboot");
    }

    function getRouterChannel(){

        $data = $this->ssh_connection->exec('nvram get ath0_channel');

        return  $data;
    }   

    function getIP(){
        return $this->ip;
    } 

    function getSurveyResult(){

        $survey_result = $this->ssh_connection->exec('site_survey');

        return  $this->survey_result;
    }    
}

?>
