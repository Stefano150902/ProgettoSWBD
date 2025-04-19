<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $_SESSION['error_message'] = "Le password non coincidono.";
        header("Location: PasswDimenticata.php");
        exit();
    } else {
        
        $stmt = $conn->prepare("SELECT id FROM utenti WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
           
            $stmt = $conn->prepare("UPDATE utenti SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);
            $stmt->execute();

            $_SESSION['message'] = "Password reimpostata con successo.";
            $_SESSION['redirect'] = true;
        } else {
            $_SESSION['error_message'] = "Indirizzo email non trovato.";
            header("Location: PasswDimenticata.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reimposta Password</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Recupera Password</h2>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p style="color: red;"> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?> </p>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])): ?>
            <p style="color: green;"> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?> </p>
            <script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 2000);
            </script>
        <?php endif; ?>
        <form action="PasswDimenticata.php" method="POST">
            <input type="email" name="email" placeholder="Inserisci la tua email" required><br>
            <input type="password" name="new_password" placeholder="Nuova Password" required><br>
            <input type="password" name="confirm_password" placeholder="Conferma Nuova Password" required><br>
            <button type="submit">Reimposta Password</button>
        </form>
        <p><a href="login.php" style="color: #cce7ff;">Torna al login</a></p>
    </div>
</body>
</html>