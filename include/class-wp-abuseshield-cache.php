<?php

class Wp_Abuseshield_Cache
{

    protected $hashedip;
    protected $hours;

    function __construct($ip, $hours)
    {
        $this->hashedip = sha1($ip);
        $this->hours = $hours;
        $this->ClearExpiredGuests();
    }

    public function CheckGuest()
    {
        global $wpdb;
        $findGuest = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."abuseshield WHERE ip='".$this->hashedip."'");

        if($findGuest > 0)
        {
            $wpdb->update(
                $wpdb->prefix."abuseshield",
                array(
                    "expiry" => time()
                ),
                array(
                    "id" => $this->hashedip
                )
            );
            return false;
        }
        else
        {
            return true;
        }
    }

    public function CacheGuest($ip)
    {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix."abuseshield",
            array(
                "ip" => "",
                "expiry" => (time() + 3600 * $this->hours)
            )
        );
    }

    protected function ClearExpiredGuests()
    {
        global $wpdb;
        $wpdb->query("DELETE FROM ".$wpdb->prefix."abuseshield WHERE expiry<".time());
    }

}
