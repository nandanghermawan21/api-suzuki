<?php
if (!defined('BASEPATH')) exit('No direct script allowed');

require FCPATH . 'vendor/twilio/src/Twilio/autoload.php';

use Twilio\Rest\Client;

class Twilio_model extends CI_Model
{
    public function send_sms($phoneNumber, $message)
    {
        $account_sid = 'AC70669979e16403da4b37922a2326b0e1';
        $auth_token = '09a971d63ad41cf7885997c3a870936c';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
        // A Twilio number you own with SMS capabilities
        $twilio_number = "+19036239729";
        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $phoneNumber,
            array(
                'from' => $twilio_number,
                'body' => $message,
            )
        );
    }
}
