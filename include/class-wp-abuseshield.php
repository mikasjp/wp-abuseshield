<?php

class Wp_Abuseshield
{
    public $config;
    public $ip;
    protected $gatekeeper;
    public $abuseipdb;
    public $cache;
    public $loginguard;

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
            "cache",
            "loginguard"
        ];

        foreach($classList as $class)
            require_once $includePrefix.$class.".php";
    }

    protected function CreateObjects()
    {
        $this->config = new Wp_Abuseshield_Config();
        $this->ip = new Wp_Abuseshield_IPObtainer($this->config->Get("UsingCloudflare"));
        $this->gatekeeper = new Wp_Abuseshield_Gatekeeper($this->ip->GetIP(), $this->config->Get("Secret"));
        $this->abuseipdb = new Wp_Abuseshield_AbuseIPDB($this->ip->GetIP(), $this->config->Get("APIKey"));
        $this->cache = new Wp_Abuseshield_Cache($this->ip->GetIP(), $this->config->Get("CacheExpiration"));
        $this->loginguard = new Wp_Abuseshield_Loginguard($this->ip->GetIP(), $this->config->Get("BruteForceMemoryExpiration"), $this->config->Get("BruteForceMaxLoginAttempts"));
    }

    protected function ShouldPluginRun()
    {
        if($this->config->Get("LoginPageOnly"))
            if(basename($_SERVER['PHP_SELF']) == "wp-login.php")
                return true;
            else
                return false;
        else
            return true;
    }

    public function Run()
    {

        if($this->loginguard->IsUserBanned())
            die("Access denied");
        
        if(!$this->ShouldPluginRun())
            return false;

        if(!$this->gatekeeper->CheckTicket())
        {
            if($this->cache->CheckGuest())
            {
                if($this->abuseipdb->CheckGuest())
                {
                    $this->gatekeeper->IssueTicket();
                }
                else
                {
                    $this->cache->CacheGuest();
                    die("Access denied");
                }
            }
            else
            {
                die("Access denied");
            }
        }

        return true;
    }

}
