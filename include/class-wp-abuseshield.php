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
        $this->cache = new Wp_Abuseshield_Cache($this->ip->GetIP(), $this->config->config["CacheExpiration"]);
    }

    public function Run()
    {
        if(!$this->gatekeeper->CheckTicket($this->ip->GetIP()))
        {
            if($this->cache->CheckGuest())
            {
                if($this->abuseipdb->CheckIP($this->ip->GetIP()))
                {
                    $this->gatekeeper->IssueTicket($this->ip->GetIP());
                }
                else
                    die("Access denied");
            }
            else
            {
                die("Access denied");
            }
        }
    }

}
