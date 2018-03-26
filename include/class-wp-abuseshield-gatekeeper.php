<?php

class Wp_Abuseshield_Gatekeeper
{   
    protected $secret;
    protected $ip;

    public function __construct($IP, $s)
    {
        $this->ip = $IP;
        $this->secret = $s;
    }

    // Check if the ticket is valid
    public function CheckTicket()
    {
        // Check if the ticket exists
        if(!isset($_COOKIE["wp-abuseshield"]))
        {
            return false;
        }

        $ticketCookie = base64_decode($_COOKIE["wp-abuseshield"]);
        
        $ticket = explode("#", $ticketCookie);

        // Check if the ticket is well segmented
        if(count($ticket) != 3)
        {
            return false;
        }
        
        // Check the ticket's signature
        $token = sha1(implode("#", array($ticket[0], $ticket[1], $this->secret)));
        if($token !== $ticket[2])
        {
            return false;
        }

        // Check if the ticket belongs to the right guest
        if($ticket[0] !== $this->ip)
        {
            return false;
        }

        // Check if the ticket expiration time is a number
        if(is_numeric($ticket[1]))
        {
            // Check if the ticket is expired
            if($ticket[1] < time())
            {
                return false;
            }
        }
        else
        {
            return false;
        }

        // It seems that the ticket is valid
        return true;
    }

    // Issue ticket for the visitor
    public function IssueTicket($hours=24)
    {
        $valid_until = time() + (3600 * $hours);
        $ticket = implode("#", array($this->ip, $valid_until));
        $signature = sha1(implode("#", array($ticket, $this->secret)));
        $signed_ticket = implode("#", array($ticket, $signature));
        return setcookie("wp-abuseshield", base64_encode($signed_ticket), $valid_until, "/", $_SERVER['SERVER_NAME'], false, true);
    }

}