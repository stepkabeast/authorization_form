<?php
class IPManager
{
    public static function getPublicIP()
    {
        $response = file_get_contents('https://api.ipify.org?format=json');
        return json_decode($response, true);
    }
}
?>
