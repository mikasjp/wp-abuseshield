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
        $includePrefix = plugin_dir_path( __FILE__ ) . "class-wp-abuseshield-";
        
        $classList = [
            "config",
            "ipobtainer",
            "gatekeeper",
            "abuseipdb",
            "cache"
        ];

        foreach($classList as $class)
            require_once $includePrefix.$class.".php";
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
