<?php

class Wp_Abuseshield_Cache
{

    protected $hashedip;
    protected $hours;

    function __construct($IP, $hours)
    {
        $this->hashedip = sha1($IP);
        $this->hours = $hours;
        $this->ClearExpiredGuests();
    }

    public function CheckGuest()
    {
        global $wpdb;
        $findGuest = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."abuseshield_cache WHERE ip='".$this->hashedip."'");

        if($findGuest > 0)
        {
            $wpdb->update(
                $wpdb->prefix."abuseshield_cache",
                array(
                    "expiry" => date("Y-m-d H:i:s", (time() + 3600 * $this->hours))
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

    public function CacheGuest()
    {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix."abuseshield_cache",
            array(
                "ip" => $this->hashedip,
                "expiry" => date("Y-m-d H:i:s", (time() + 3600 * $this->hours))
            )
        );
    }

    protected function ClearExpiredGuests()
    {
        global $wpdb;
        $wpdb->query("DELETE FROM ".$wpdb->prefix."abuseshield_cache WHERE expiry<'".date("Y-m-d H:i:s", time())."'");
    }

    public function ClearCache()
    {
        global $wpdb;
        $wpdb->query("DELETE FROM ".$wpdb->prefix."abuseshield_cache");
    }

    public function CountCache()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."abuseshield_cache");
    }

}
