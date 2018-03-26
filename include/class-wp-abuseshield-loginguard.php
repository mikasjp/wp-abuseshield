<?php

class Wp_Abuseshield_Loginguard
{

    protected $hashedip;
    protected $hours;
    protected $maxFailedAttempts;

    public function __construct($IP, $hours=24, $maxFailedAttempts=3)
    {
        $this->hashedip = sha1($IP);
        $this->hours = $hours;
        $this->maxFailedAttempts = $maxFailedAttempts;
        $this->ClearExpiredEntries();
    }

    public function RegisterFailedLoginAttempt()
    {
        global $wpdb;
        
        $t = date("Y-m-d H:i:s", (3600 * $this->hours + time()));

        $q = "SELECT COUNT(*) FROM ".$wpdb->prefix."abuseshield_login WHERE ip='".$this->hashedip."';";
        $findGuest = $wpdb->get_var($q);

        if($findGuest>0)
        {
            $q = "UPDATE ".$wpdb->prefix."abuseshield_login SET attempts=attempts+1,expiry='".$t."' WHERE ip='".$this->hashedip."';";
        }
        else
        {
            $q = "INSERT INTO ".$wpdb->prefix."abuseshield_login(ip, expiry, attempts) VALUES('".$this->hashedip."', '".$t."', 1);";
        }

        $wpdb->query($q);

        return $this->IsUserBanned();

    }

    public function IsUserBanned()
    {
        global $wpdb;
        $q = "SELECT COUNT(*) FROM ".$wpdb->prefix."abuseshield_login WHERE ip='".$this->hashedip."' AND attempts>=".$this->maxFailedAttempts;
        $findGuest = $wpdb->get_var($q);
        return $findGuest > 0;
    }

    protected function ClearExpiredEntries()
    {
        global $wpdb;
        return $wpdb->query("DELETE FROM ".$wpdb->prefix."abuseshield_login WHERE expiry<'".date("Y-m-d H:i:s", time())."'");
    }

}
