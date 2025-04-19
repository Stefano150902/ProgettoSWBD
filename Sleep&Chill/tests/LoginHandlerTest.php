<?php
use PHPUnit\Framework\TestCase;

define('PHPUNIT_TEST', true);  
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../wb/pg.php');
require_once 'login_handler.php';

class LoginHandlerTest extends TestCase {

    private $conn;
    public function setUp(): void {
        $this->conn = new mysqli("localhost", "root", "", "mydatabase_test");
    
        if ($this->conn->connect_error) {
            die("Connessione fallita: " . $this->conn->connect_error);
        }
    
        $this->conn->query("DELETE FROM utenti");
        
        // Aggiungi un utente di test
        $email = "admin@example.com";
        $password = "password123"; 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $nome = "Admin";
        $cognome = "User";
        $eta = 30;
        $stmt = $this->conn->prepare("INSERT INTO utenti (email, password, nome, cognome, eta) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $email, $hashed_password, $nome, $cognome, $eta);
        $stmt->execute();
        $stmt->close();
    }
    
    public function tearDown(): void {
        $this->conn->close();
    }

    // Test per login con credenziali corrette
    public function testLoginSuccess() {
        
        $email = "admin@example.com";
        $password = "password123"; 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $nome = "Admin";
        $cognome = "User";
        $eta = 30;

        // Aggiungi un utente al database di test
        $stmt = $this->conn->prepare("REPLACE INTO utenti (email, password, nome, cognome, eta) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $email, $hashed_password, $nome, $cognome, $eta);
        $stmt->execute();
        $stmt->close();

        // Simula il login usando la classe login_handler
        session_start();
        $result = loginUser($email, $password);

        $this->assertEquals("success", $result);
        $this->assertTrue(isset($_SESSION["id"]));
        $this->assertEquals("Admin", $_SESSION["nome"]);
        $this->assertEquals("User", $_SESSION["cognome"]);
        $this->assertEquals(30, $_SESSION["eta"]);
    }

    // Test per login con password errata
    public function testLoginFailurePassword() {
        session_start();
        $result = loginUser("admin@example.com", "wrongpassword");

        $this->assertEquals("password_error", $result);
        $this->assertEquals("Password errata!", $_SESSION['error_message']);
    }

    // Test per login con email non trovata
    public function testLoginFailureEmail() {
        session_start();
        $result = loginUser("nonexistent@example.com", "password123");

        $this->assertEquals("email_error", $result);
        $this->assertEquals("Email non trovata!", $_SESSION['error_message']);
    }
}