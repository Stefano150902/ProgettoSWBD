<?php 
$host = "localhost";
$username = "root";
$password = "";

if (defined('TESTING') && TESTING === true) {
    $dbname = "mydatabase_test";
} else {
    $dbname = "mydatabase";
}


$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}


$conn->set_charset("utf8");
?>
