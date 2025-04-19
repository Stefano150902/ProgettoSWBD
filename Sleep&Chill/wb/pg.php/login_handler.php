<?php  
include 'config.php'; 
session_start();

function loginUser($email, $password) {
        
    $database_name = (defined('PHPUNIT_TEST') && PHPUNIT_TEST) ? "mydatabase_test" : "mydatabase";

    
    $conn = new mysqli("localhost", "root", "", $database_name);

    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    $query = $conn->prepare("SELECT id, nome, cognome, eta, password FROM utenti WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["id"] = $user["id"];
            $_SESSION["nome"] = $user["nome"];
            $_SESSION["cognome"] = $user["cognome"];
            $_SESSION["eta"] = $user["eta"];
            return "success";
        } else {
            $_SESSION['error_message'] = "Password errata!"; 
            return "password_error";
        }
    } else {
        $_SESSION['error_message'] = "Email non trovata!";
        return "email_error";
    }
}


if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $result = loginUser($_POST["email"], $_POST["password"]);
    if ($result == "success") {
        header("Location: IniziaOra.php");
        exit();
    } else {
        header("Location: login.php");
        exit();
    }
}