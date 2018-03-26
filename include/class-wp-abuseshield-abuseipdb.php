<?php

class Wp_Abuseshield_AbuseIPDB
{

    protected $apikey;
    protected $ip;

    function __construct($IP, $key)
    {
        $this->ip = $IP;
        $this->apikey = $key;
    }

    protected function Request($url)
    {
        $result = wp_remote_get($url);
        return wp_remote_retrieve_body($result);
    }

    public function CheckGuest()
    {
        $result = json_decode($this->Request("https://www.abuseipdb.com/check/".$this->ip."/json?key=".$this->apikey."&days=7"));

        if(count($result) > 0)
            return false;
        else
            return true;
    }

    public function ReportIP($comment="Blocked by WP AbuseShield WordPress plugin")
    {
        $ip = WP_DEBUG?"127.0.0.1":$this->ip;
        $this->Request("https://www.abuseipdb.com/report/json?key=".$this->apikey."&category=21&comment=".$comment."&ip=".$ip);
        return true;
    }

}
