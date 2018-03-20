<?php

class Wp_Abuseshield
{
    protected $config;
    protected $ip;
    protected $gatekeeper;
    protected $abuseipdb;
    protected $cache;

    public function __construct()
    {
        $this->LoadDependences();
        $this->CreateObjects();
    }

    protected function LoadDependences()
    {
        require_once plugin_dir_path( __FILE__ ) . "class-wp-abuseshield-config.php";
        require_once plugin_dir_path( __FILE__ ) . "class-wp-abuseshield-ipobtainer.php";
        require_once plugin_dir_path( __FILE__ ) . "class-wp-abuseshield-gatekeeper.php";
        require_once plugin_dir_path( __FILE__ ) . "class-wp-abuseshield-abuseipdb.php";
    }

    protected function CreateObjects()
    {
        $this->config = new Wp_Abuseshield_Config();
        $this->ip = new Wp_Abuseshield_IPObtainer();
        $this->gatekeeper = new Wp_Abuseshield_Gatekeeper($this->ip->GetIP());
        $this->abuseipdb = new Wp_Abuseshield_AbuseIPDB($this->config->config["APIKey"]);
    }

    public function Run()
    {
        
    }

}
