<?php

class Wp_Abuseshield_IPObtainer
{
    protected $IP;
    
    function __construct()
    {
        // If you use CloudFlare, the plugin must obtain the IP address in a different way
        $this->IP = isset($_SERVER["HTTP_CF_CONNECTING_IP"])?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER["REMOTE_ADDR"];
    }

    public function GetIP()
    {
        return $this->IP;
    }

}
