<?php

class Wp_Abuseshield_IPObtainer
{
    protected $IP;
    
    public function __construct($usingCloudflare)
    {
        // If you use CloudFlare, the plugin must obtain the IP address in a different way
        $ip = filter_var($usingCloudflare?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP);
        if($ip === false)
            throw new Exception("Passed IP address is not valid.");
        else
            $this->IP = $ip;
    }

    public function GetIP()
    {
        return $this->IP;
    }

}
