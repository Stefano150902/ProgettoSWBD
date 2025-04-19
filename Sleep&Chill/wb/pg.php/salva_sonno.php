<?php  
include 'config.php'; 
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "mydatabase");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION['id'];
    $data_sonno = $_POST["data_sonno"];
    $ora_sonno = $_POST["ora_sonno"];
    $ora_risveglio = $_POST["ora_risveglio"];
    $qualita_sonno = $_POST["qualita_sonno"];
    $risvegli_notturni = $_POST["risvegli_notturni"];
    $note = isset($_POST["note"]) ? $_POST["note"] : "";
    $sovrascrivi = isset($_POST["sovrascrivi"]) ? $_POST["sovrascrivi"] : '0'; // Flag di sovrascrittura

    $query = $conn->prepare("SELECT id FROM monitoraggio_sonno WHERE user_id = ? AND data_sonno = ?");
    $query->bind_param("is", $id, $data_sonno);
    $query->execute();
    $result = $query->get_result();
    $exists = $result->num_rows > 0;
    $query->close();

    if ($exists && $sovrascrivi == '0') {
        echo json_encode(["success" => false, "exists" => true, "message" => "Dati già presenti per questa data. Vuoi sovrascriverli?"]);
        exit();
    }

   
    $ora_sonno_obj = new DateTime($ora_sonno);
    $ora_risveglio_obj = new DateTime($ora_risveglio);
    if ($ora_risveglio_obj < $ora_sonno_obj) {
        $ora_risveglio_obj->modify('+1 day'); 
    }
    $durata_sonno = $ora_sonno_obj->diff($ora_risveglio_obj);
    $durata_sonno = $durata_sonno->h + ($durata_sonno->i / 60);

    
    if ($exists && $sovrascrivi == '1') {
        $query = $conn->prepare("UPDATE monitoraggio_sonno 
                         SET ora_sonno = ?, ora_risveglio = ?, durata_sonno = ?, 
                             qualita_sonno = ?, risvegli_notturni = ?, note = ? 
                         WHERE user_id = ? AND data_sonno = ?");
$query->bind_param("ssdissis", $ora_sonno, $ora_risveglio, $durata_sonno, 
                   $qualita_sonno, $risvegli_notturni, $note, $id, $data_sonno);

    } else {
       
        $query = $conn->prepare("INSERT INTO monitoraggio_sonno (user_id, data_sonno, ora_sonno, ora_risveglio, durata_sonno, qualita_sonno, risvegli_notturni, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("isssdiss", $id, $data_sonno, $ora_sonno, $ora_risveglio, $durata_sonno, $qualita_sonno, $risvegli_notturni, $note);
    }

    if ($query->execute()) {
        echo json_encode(["success" => true, "message" => $exists ? "Dati aggiornati con successo!" : "Dati salvati con successo!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Errore nel salvataggio dei dati."]);
    }

    $query->close();
    $conn->close();
}
?>