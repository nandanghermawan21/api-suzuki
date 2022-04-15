<?php
if (!defined('BASEPATH')) exit('No direct script allowed');

class Notification_model extends CI_Model
{
    public function send_basic_notification(String $appId, Array $title,  Array $message, Array $receiver, String $appUrl)
    {
        // Set up and execute the curl process
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
            "app_id" => $appId,
            "include_player_ids" => $receiver,
            "contents" => $message,
            "headings" => $title,
            "app_url" => $appUrl,
            "ios_sound" => "my_notification_sound.wav",
            "android_channel_id" => "6d52b765-dc9f-4735-a38b-77b784dbe90b"
        ));

        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
            "Accept: application/json",
            "Authorization: Basic NGVhYmMxNmEtODM3Zi00MDM3LWI5ZjYtNDQ3ZTNiMDExMWVi",
        ));

        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);

        return json_decode($buffer);
    }
}
