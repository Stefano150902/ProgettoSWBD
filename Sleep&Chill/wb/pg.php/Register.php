<?php  
include 'config.php'; 
session_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #007bb5;
            padding: 40px;
            border-radius: 15px;
            color: white;
            text-align: center;
            width: 350px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        input, select {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            background-color: #005f87;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #004366;
        }
        p {
            margin-top: 15px;
        }

        
        .error-banner {
            background-color: #ff4d4d;
            color: white;
            padding: 10px;
            font-size: 1.2em;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .error-banner button {
            background: #004c8c;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        
        .password-container {
            position: relative;
            width: 90%;
            display: inline-block;
        }

        .password-container input {
            width: calc(100% - 40px);
            padding-right: 35px; 
        }

        .password-container button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #333;
        }

    </style>
</head>
<body>

    <div class="container">
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-banner" id="error-banner">
                <?php echo $_SESSION['error_message']; ?>
                <button onclick="closeErrorBanner()">OK</button>
            </div>
            <?php unset($_SESSION['error_message']); ?> 
        <?php endif; ?>

        <h2>Registrati</h2>
        <form id="registerForm" action="register_handler.php" method="POST" onsubmit="return validateForm()">
            <input type="text" name="nome" placeholder="Nome" required><br>
            <input type="text" name="cognome" placeholder="Cognome" required><br>
            <input type="number" id="eta" name="eta" placeholder="Età" required><br>
            <select name="sesso" required>
                <option value="">Seleziona il sesso</option>
                <option value="Maschio">Maschio</option>
                <option value="Femmina">Femmina</option>
                <option value="Altro">Altro</option>
            </select><br>
            <input type="email" name="email" placeholder="Email" required><br>

          
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="button" id="togglePassword">👀</button>
            </div>
            <div id="passwordError" style="color: red; font-size: 14px;"></div>

            <button type="submit">Registrati</button>
        </form>
        <p>Hai già un account? <a href="login.php" style="color: #cce7ff;">Accedi</a></p>
    </div>

    <script>
        
        function closeErrorBanner() {
            document.getElementById('error-banner').style.display = 'none';
        }

        
        document.getElementById("togglePassword").addEventListener("click", function () {
            var passwordField = document.getElementById("password");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                this.textContent = "🚫"; 
            } else {
                passwordField.type = "password";
                this.textContent = "👀"; 
            }
        });

        
        function validateForm() {
            var eta = document.getElementById('eta').value;
            var password = document.getElementById('password').value;
            var errorDiv = document.getElementById("passwordError");

            /
            errorDiv.textContent = "";

            
            if (eta <= 0 || isNaN(eta)) {
                alert("Per favore, inserisci un'età valida (numero positivo).");
                return false;
            }

            
            let letterMatch = password.match(/[A-Za-z]/g) || [];
            let numberMatch = password.match(/\d/g) || [];

            if (letterMatch.length < 7 || numberMatch.length < 2) {
                errorDiv.textContent = "La password deve contenere almeno 7 lettere e 2 numeri.";
                return false;
            }

            return true;
        }
    </script>

</body>
</html>
