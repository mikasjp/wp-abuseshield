<?php

class Wp_Abuseshield_Gatekeeper
{   
    protected $secret;

    public function __construct($s)
    {
        $this->secret = $s;
    }

    // Check if the ticket is valid
    public function CheckTicket($IP)
    {
        // Check if the ticket exists
        if(!isset($_COOKIE["wp-abuseshield"]))
        {
            if(WP_DEBUG) echo "Gatekeeper: cookie doesn't exist";
            return false;
        }

        $ticketCookie = base64_decode($_COOKIE["wp-abuseshield"]);
        
        $ticket = explode("#", $ticketCookie);

        // Check if the ticket is well segmented
        if(count($ticket) != 3)
        {
            if(WP_DEBUG) echo "Gatekeeper: ticket segmentation error";
            return false;
        }
        
        // Check the ticket's signature
        $token = sha1(implode("#", array($ticket[0], $ticket[1], $this->secret)));
        if($token !== $ticket[2])
        {
            if(WP_DEBUG) echo "Gatekeeper: bad signature";
            return false;
        }

        // Check if the ticket belongs to the right guest
        if($ticket[0] !== $IP)
        {
            if(WP_DEBUG) echo "Gatekeeper: bad IP";
            return false;
        }

        // Check if the ticket expiration time is a number
        if(is_numeric($ticket[1]))
        {
            // Check if the ticket is expired
            if($ticket[1] < time())
            {
                if(WP_DEBUG) echo "Gatekeeper: expired ticket";
                return false;
            }
        }
        else
        {
            if(WP_DEBUG) echo "Gatekeeper: expiration time isn't numeric";
            return false;
        }

        // It seems that the ticket is valid
        return true;
    }

    // Issue ticket for the visitor
    public function IssueTicket($IP, $hours=24)
    {
        $valid_until = time() + (3600 * $hours);
        $ticket = implode("#", array($IP, $valid_until));
        $signature = sha1(implode("#", array($ticket, $this->secret)));
        $signed_ticket = implode("#", array($ticket, $signature));
        return setcookie("wp-abuseshield", base64_encode($signed_ticket), $valid_until, "/");
    }

}