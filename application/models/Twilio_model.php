<?php
if (!defined('BASEPATH')) exit('No direct script allowed');

require FCPATH . 'vendor/twilio/src/Twilio/autoload.php';

use Twilio\Rest\Client;

class Twilio_model extends CI_Model
{
    public function send_sms($phoneNumber, $message)
    {
        $account_sid = 'AC9b0368d17c612bee5582cf47737fcbaa';
        $auth_token = '4911bc12751270405c89afe3f7d591f5';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
        // A Twilio number you own with SMS capabilities
        $twilio_number = "+12545565431";
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
