<?php  
include 'config.php'; 
session_start();
$conn = new mysqli("localhost", "root", "", "mydatabase");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $eta = $_POST["eta"];
    $sesso = $_POST["sesso"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    
    if (!is_numeric($eta) || $eta <= 0) {
        $_SESSION['error_message'] = "Inserisci un'età valida.";
        header("Location: Register.php");
        exit();
    }

    
    if (!preg_match('/^(?=(.*[A-Za-z]){7,})(?=(.*\d){2,}).{9,}$/', $password)) {
        $_SESSION['error_message'] = "La password deve contenere almeno 7 lettere e 2 numeri.";
        header("Location: Register.php");
        exit();
    }

    
    $password = password_hash($password, PASSWORD_DEFAULT);

    
    $checkEmailQuery = $conn->prepare("SELECT id FROM utenti WHERE email = ?");
    $checkEmailQuery->bind_param("s", $email);
    $checkEmailQuery->execute();
    $checkEmailQuery->store_result();

    if ($checkEmailQuery->num_rows > 0) {
        $_SESSION['error_message'] = "Questa email è già registrata.";
        header("Location: Register.php");
        exit();
    } else {
        
        $query = $conn->prepare("INSERT INTO utenti (nome, cognome, eta, sesso, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssssss", $nome, $cognome, $eta, $sesso, $email, $password);

        if ($query->execute()) {
            $_SESSION["id"] = $conn->insert_id;
            $_SESSION["nome"] = $nome;
            $_SESSION["cognome"] = $cognome;
            $_SESSION["eta"] = $eta;
            header("Location: IniziaOra.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Errore nella registrazione. Riprova.";
            header("Location: Register.php");
            exit();
        }
    }
}
?>
