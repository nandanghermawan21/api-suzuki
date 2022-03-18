<?php
if (!defined('BASEPATH')) exit('No direct script allowed');

require FCPATH . 'vendor/twilio/src/Twilio/autoload.php';

use Twilio\Rest\Client;

class Twilio_model extends CI_Model
{
    public function send_sms()
    {
        $account_sid = 'AC7dba9fbe5b88d0210afda563396c29a4';
        $auth_token = 'baf6e48abebe8c1dad95b7266eea8e32';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
        // A Twilio number you own with SMS capabilities
        $twilio_number = "+19705174825";
        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            '+6287724538083',
            array(
                'from' => $twilio_number,
                'body' => 'I sent this message in under 10 minutes!'
            )
        );
    }
}
