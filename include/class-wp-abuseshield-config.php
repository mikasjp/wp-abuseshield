<?php

class Wp_Abuseshield_Config
{
    protected $config_file;
    protected $config;
    protected $default = [
        "APIKey" => "",
        "DVC" => "",
        "CacheExpiration" => 24,
        "BruteForceMemoryExpiration" => 24,
        "BruteForceMaxLoginAttempts" => 3,
        "LoginPageOnly" => false,
        "UsingCloudflare" => false,
        "BruteForceProtection" => true
    ];

    public function __construct()
    {
        $this->config_file = plugin_dir_path( __FILE__ ) . "../wp-abuseshield-config.php";
        
        if(!file_exists($this->config_file))
        {
            $this->config = $this->default;
            $this->SaveConfig();
        }
        else
            $this->LoadConfig();
    }

    protected function LoadConfig()
    {
        $config_file_contents = file($this->config_file);
        $this->config = json_decode(base64_decode($config_file_contents[1]), true);
    }

    protected function SaveConfig()
    {
        $config_string = "<?php /*\n" . base64_encode(json_encode($this->config));
        return file_put_contents($this->config_file, $config_string);
    }

    public function ResetSecret()
    {
        $secret = sha1(time()."#".rand(0, 1000000000));
        $this->Set("Secret", $secret);
    }

    public function Get($name)
    {
        if(isset($this->config[$name]))
        {
            $this->Set($name, $this->default[$name]);
            $this->SaveConfig();
        }
        
        return $this->config[$name];
    }

    public function Set($name, $value)
    {
        $this->config[$name] = $value;
        $this->SaveConfig();
    }

}
