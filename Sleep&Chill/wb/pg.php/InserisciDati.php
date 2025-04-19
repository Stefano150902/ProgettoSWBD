<?php 
include 'config.php'; 
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sleep & Chill - Inserisci Dati</title>
    <link rel="stylesheet" href="../styles.css/styleInsDati.css"> 
</head>
<body>

<header class="header-container">
    <div class="logo">
        <a href="IniziaOra.php">
            <img src="../../images/logo.jpg" alt="Logo Sleep & Chill">
        </a>
    </div>
    <div class="title">Inserisci Dati</div>
    <div class="user-info">
    <span>
        <?php 
        echo $_SESSION['nome'] . " " . $_SESSION['cognome'];
        if (isset($_SESSION['eta'])) {
            echo " - " . $_SESSION['eta'] . " anni";
        } else {
            echo " - Età non disponibile";
        }
        ?>
    </span>
    <a href="logout.php" class="logout-button">Logout</a>
</div>
</header>


<div class="info-box">
    <h2>Perché monitorare il sonno?</h2>
    <p>Il sonno è fondamentale per il benessere fisico e mentale. Registrare le tue abitudini di riposo ti aiuta a:</p>
    <ul>
        <li>Individuare eventuali problemi di insonnia o disturbi del sonno.</li>
        <li>Capire l'influenza dello stile di vita sulla qualità del riposo.</li>
        <li>Ottimizzare il tuo sonno per avere più energia durante il giorno.</li>
        <li>Prevenire problemi di salute legati alla mancanza di sonno adeguato.</li>
    </ul>
    <p>Compila il modulo qui sotto per iniziare a monitorare il tuo sonno!</p>
</div>


<div class="container">
    <h2>Registra il tuo Sonno</h2>
    <form action="salva_sonno.php" method="POST">
        <label>Data del sonno:</label>
        <input type="date" name="data_sonno" required>

        <label>Ora di addormentamento:</label>
        <input type="time" name="ora_sonno" required>

        <label>Ora di risveglio:</label>
        <input type="time" name="ora_risveglio" required>

        <label>Qualità del sonno:</label>
            <div class="emoticons">
                <input type="radio" id="very_bad" name="qualita_sonno" value="1" required>
                <label for="very_bad">😞</label>

                <input type="radio" id="bad" name="qualita_sonno" value="2">
                <label for="bad">🙁</label>

                <input type="radio" id="neutral" name="qualita_sonno" value="3">
                <label for="neutral">😐</label>

                <input type="radio" id="good" name="qualita_sonno" value="4">
                <label for="good">🙂</label>

                <input type="radio" id="very_good" name="qualita_sonno" value="5">
                <label for="very_good">😁</label>
            </div>

        <label>Risvegli notturni:</label>
        <input type="number" name="risvegli_notturni" min="0" required>

        <label>Note personali:</label>
        <textarea name="note" rows="3" placeholder="Scrivi eventuali dettagli..."></textarea>

        <button type="submit">Salva Dati</button>
    </form>
</div>

<script>
document.querySelector("form").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch("salva_sonno.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            if (confirm(data.message)) {
                formData.append("sovrascrivi", "1"); 
                fetch("salva_sonno.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(finalData => alert(finalData.message))
                .catch(error => console.error("Errore:", error));
            }
        } else {
            alert(data.message);
            if (data.success) location.reload();
        }
    })
    .catch(error => console.error("Errore:", error));
});
</script>

</body>
</html>
