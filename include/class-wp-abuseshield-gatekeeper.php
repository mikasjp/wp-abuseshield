<?php

class Wp_Abuseshield_Gatekeeper
{   
    protected $secret;

    function __construct($s)
    {
        $this->secret = $s;
    }

    // Check if the ticket is valid
    public function CheckTicket($IP)
    {
        $ticketCookie = base64_decode($_COOKIE['wp-abuseshield']);
        
        // Check if the ticket exists
        if(empty($ticketCookie))
            return false;

        $ticket = explode("#", $ticketCookie);

        // Check if the ticket is well segmented
        if(count($ticket) != 3)
            return false;
        
        // Check the ticket's signature
        $token = sha1(implode("#", array($ticket[0], $ticket[1], $this->secret())));
        if($token !== $ticket[2])
            return false;

        // Check if the ticket belongs to the right guest
        if($ticket[0] !== $IP)
            return false;

        // Check if the ticket expiration time is a number
        if(is_numeric($ticket[1]))
            // Check if the ticket is expired
            if($ticket[1] > time())
                return false;
        else
            return false;

        // It seems that the ticket is valid
        return true;
    }

}