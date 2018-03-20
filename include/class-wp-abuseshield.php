<?php

class Wp_Abuseshield
{
    protected $config;
    protected $ip;
    protected $gatekeeper;
    protected $abuseipdb;
    protected $cache;

    function __construct()
    {
        $this->LoadDependences();
        $this->CreateObjects();
    }

    protected function LoadDependences()
    {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . "include/class-wp-abuseshield-config.php";
        require_once plugin_dir_path( dirname( __FILE__ ) ) . "include/class-wp-abuseshield-ipobtainer.php";
        require_once plugin_dir_path( dirname( __FILE__ ) ) . "include/class-wp-abuseshield-gatekeeper.php";
        require_once plugin_dir_path( dirname( __FILE__ ) ) . "include/class-wp-abuseshield-abuseipdb.php";
    }

    protected function CreateObjects()
    {
        $this->config = new Wp_Abuseshield_Config();
        $this->ip = new Wp_Abuseshield_IPObtainer();
        $this->gatekeeper = new Wp_Abuseshield_Gatekeeper($ip->IP);
        $this->abuseipdb = new Wp_Abuseshield_AbuseIPDB($config->config["APIKey"]);
    }

    public function Run()
    {
        
    }

}
