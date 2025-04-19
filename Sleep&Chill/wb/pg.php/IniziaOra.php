<?php
include 'config.php'; 
session_start(); 


$loggedIn = isset($_SESSION['nome']) && isset($_SESSION['cognome']);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sleep & Chill</title>
    <link rel="stylesheet" href="../styles.css/styleIniziaOra.css">
</head>

<body>

<header class="header-container" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background:rgb(62, 95, 196); color: white;">
   
    <div class="logo" style="flex-shrink: 0;">
        <img src="../../images/logo.jpg" alt="Logo Sleep & Chill" style="width: 130px; height: 80px; border-radius: 25%; object-fit: cover;">
    </div>
    <div style="font-size: 1.6em; font-weight: bold; color: white; flex-grow: 1; text-align: center; font-family: 'Times New Roman', Times, serif;">
        Benvenuto!
    </div>

    
    <div class="auth-container" style="display: flex; align-items: center; gap: 15px;">
        <?php if ($loggedIn): ?>
            <span class="user-info" style="font-size: 1.3em; font-weight: bold;"><?php echo $_SESSION['nome'] . " " . $_SESSION['cognome']. " - ". $_SESSION['eta']. " anni "; ?></span>
            <a href="logout.php" class="logout-button">Logout</a>
        <?php else: ?>
            <div class="auth-links" style="display: flex; gap: 15px;">
                <a href="login.php" style="color: white; text-decoration: none; font-size: 1em; padding: 8px 12px; border-radius: 5px; transition: background 0.3s;">Login ☾</a>
                <a href="register.php" style="color: white; text-decoration: none; font-size: 1em; padding: 8px 12px; border-radius: 5px; transition: background 0.3s;">Registrati ☁</a>
            </div>
        <?php endif; ?>
    </div>
    <div class="popup-overlay" id="popup">
        <div class="popup">
            <h3>Benvenuto su Sleep & Chill!</h3>
            <p>Questa app ti aiuterà a monitorare e migliorare la qualità del tuo sonno. <br>
               Potrai inserire dati sulle tue abitudini di riposo, ottenere statistiche dettagliate, 
               ricevere suggerimenti personalizzati e accedere all'assistenza per migliorare il tuo benessere.</p>
               <br>
            <button onclick="closePopup()">Inizia Ora!</button>
        </div>
    </div>
    
    <script>
       if(sessionStorage.getItem('popupClosed') !== 'true') {
            document.getElementById('popup').style.display = 'flex';
        } else {
            document.getElementById('popup').style.display = 'none';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
            sessionStorage.setItem('popupClosed', 'true'); 
        }
    </script>
</header>

<main>
    <section class="grid-container">
        <div class="card">
            <a href="InserisciDati.php">
                <img src="../../images/dati.jpg">
            </a>
            <p>INSERISCI DATI<br> <br> Registra gli orari di sonno e la durata per un monitoraggio accurato.</p>
        </div>
        <div class="card">
            <a href="Statistiche.php">
                <img src="../../images/statistiche1.jpg">
            </a>
            <p>STATISTICHE<br><br> Visualizza report dettagliati sulla qualità del tuo riposo.</p>
        </div>
        <div class="card">
            <a href="Consigli.php">
                <img src="../../images/consigli.jpg">
            </a>
            <p>CONSIGLI<br> <br>Ricevi consigli personalizzati per migliorare il tuo sonno.</p>
        </div>
        <div class="card">
            <a href="Assistenza.php">
                <img src="../../images/assistenza.jpg">
            </a>
            <p>ASSISTENZA<br><br> Hai bisogno di aiuto? Contatta il nostro supporto.</p>
        </div>
    </section>
</main>

</body>
</html>