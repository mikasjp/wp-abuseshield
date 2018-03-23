<?php

class WP_Abuseshield_Admin
{

    public $plugin;
    protected $messages;

    public function __construct()
    {
        $this->messages = [];
        require_once plugin_dir_path( __FILE__ ) . "../include/class-wp-abuseshield.php";
        $this->plugin = new Wp_Abuseshield();

        $this->ParseRequests();

        if(empty($this->plugin->config->config["APIKey"]))
            $this->ShowMessage("For proper operation of the plugin it is necessary to provide your API key, which you can get by registering on the <a href=\"https://www.abuseipdb.com/\" target=\"_blank\">https://www.abuseipdb.com/</a>.");

    }

    protected function VerifyCSRF()
    {
        $secret = $this->plugin->config->config["Secret"];
        $nonce = $_POST["WP_ABUSESHIELD_ADMIN_CSRF"];
        $csrf_token = sha1("");
    }

    protected function ParseRequests()
    {
        if(isset($_POST["WP_ABUSESHIELD_ADMIN_SUBMIT"]))
        {
            $this->plugin->config->config["APIKey"] = htmlspecialchars($_POST["WP_ABUSESHIELD_ADMIN_APIKEY"]);
            $this->plugin->config->SaveConfig();
            $this->ShowMessage("The configuration has been saved successfully");
        }

        if(isset($_POST["WP_ABUSESHIELD_ADMIN_RESET_SECRET"]))
        {
            $this->plugin->config->config["Secret"] = $this->plugin->config->GenerateSecret();
            $this->plugin->config->SaveConfig();
            $this->ShowMessage("The secret token has been modified successfully");
        }

        if(isset($_POST["WP_ABUSESHIELD_ADMIN_CLEAR_CACHE"]))
        {
            $this->plugin->cache->ClearCache();
            $this->ShowMessage("The cache has been cleared");
        }
    }

    protected function ShowMessage($s)
    {
        $this->messages[] = $s;
    }

    public function DisplayMessages()
    {
        $html = "";
        foreach($this->messages as $message)
        {
            $html .= "<div class=\"wp-abuseshield-admin-messagebox\">".$message."</div>\n";
        }
        return $html;
    }

}
