<?php

class Wp_Abuseshield_AbuseIPDB
{

    protected $apikey;

    function __construct($key)
    {
        $this->apikey = $key;
    }

    protected function Request($url)
    {
        $curl = curl_init($url);
        
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => "WP AbuseShield WordPress Plugin",
            CURLOPT_SSL_VERIFYPEER => 0
        ));

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function CheckIP($IP)
    {
        $result = json_decode($this->Request("https://www.abuseipdb.com/check/".$IP."/json?key=".$this->apikey."&days=7"));

        if(count($result) > 0)
            return false;
        else
            return true;
    }

    public function ReportIP($IP, $comment="Blocked by WP AbuseShield WordPress plugin")
    {
        $this->Request("https://www.abuseipdb.com/report/json?key=".$this->apikey."&category=21&comment=".$comment."&ip=".$IP);
        return true;
    }

}
