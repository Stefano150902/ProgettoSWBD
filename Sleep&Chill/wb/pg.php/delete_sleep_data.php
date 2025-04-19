<?php
include 'config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Accesso negato!']);
    exit();
}

$user_id = $_SESSION['id'];
$id = $_POST['id'] ?? '';

if ($id) {
    $query = "DELETE FROM monitoraggio_sonno WHERE user_id = ? AND id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dati eliminati con successo!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'eliminazione.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Nessun ID selezionato!']);
}
?>

