<?php

include('Net/SSH2.php');
// include("mainInformation.php");

class Router
{
    public $host_value;
    public $ssh_connection = null;
    public $server = array("ip"=>"192.168.1", "sshport"=>"22", "user"=>"root", "pw"=>"admin");
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

        $this->host_value = $host_ip;

    }

    function connectToRouter(){                     

        if($this->ssh_connection !== null)
            return $this->ssh_connection;

        $ip =  $this->server['ip'] . "." .  $this->host_value;

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

    function getHostValue(){
        return  $this->host_value;
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

    function getRouterChannel(){

        $data = $this->ssh_connection->exec('nvram get ath0_channel');

        return  $data;
    }    

    function getSurveyResult(){

        $survey_result = $this->ssh_connection->exec('site_survey');

        return  $this->survey_result;
    }    
}

?>
