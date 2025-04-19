<?php 
session_start();
include 'config.php';

header('Content-Type: application/json');


if (!isset($_SESSION['id'])) {
    echo json_encode(["error" => "Utente non autenticato"]);
    exit();
}

include 'config.php';

$user_id = $_SESSION['id'];

if (isset($_GET['id'])) {
    
    $id = intval($_GET['id']);
    $query = "SELECT id, data_sonno, durata_sonno, qualita_sonno, risvegli_notturni, note FROM monitoraggio_sonno WHERE user_id = ? AND id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Nessun dato trovato per questa data"]);
    }
} else {
   
    $query = "SELECT id, data_sonno, durata_sonno, qualita_sonno, risvegli_notturni, note FROM monitoraggio_sonno WHERE user_id = ? ORDER BY data_sonno ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

