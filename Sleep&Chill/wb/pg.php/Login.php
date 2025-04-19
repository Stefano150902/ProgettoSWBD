<?php
include 'config.php'; 
session_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        input {
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

        <h2>Accedi</h2>
        <form action="login_handler.php" method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Non hai un account? <a href="register.php" style="color: #cce7ff;">Registrati</a></p>
        <p>Password dimenticata? <a href="PasswDimenticata.php" style="color: #cce7ff;">Recupera Password</a></p>
    </div>

    <script>
       
        function closeErrorBanner() {
            document.getElementById('error-banner').style.display = 'none';
        }
    </script>

</body>
</html>
