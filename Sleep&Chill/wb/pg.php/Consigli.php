<?php
include 'config.php'; 
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['id'];
$query = "SELECT * FROM monitoraggio_sonno WHERE user_id = ? ORDER BY data_sonno DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$dati_sonno = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sleep & Chill - Consigli</title>
    <link rel="stylesheet" href="../styles.css/styleCons1.css"> 
</head>

<body>
<header class="header-container">
    <div class="logo">
        <a href="IniziaOra.php">
            <img src="../../images/logo.jpg" alt="Logo Sleep & Chill">
        </a>
    </div>
    <div class="title">Consigli Personalizzati</div>
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
    <p>
        Ecco il tuo spazio dedicato a suggerimenti e strategie su misura per migliorare la qualità del sonno. 
        <br>
        Consulta regolarmente questa sezione per rimanere aggiornato sulle migliori pratiche per il tuo riposo.
    </p>
</div>


<?php if ($dati_sonno): ?>
    <div class="consigli-box">
        <h3 class="consigli-titolo"><?php echo date("d/m/Y", strtotime($dati_sonno['data_sonno'])); ?></h3>
        <div style="display: flex; align-items: center;">
            <ul style="margin-right: 10px;">
                <?php
                $consigli = [];

                
                if ($dati_sonno['qualita_sonno'] <= 3) {
                    if($dati_sonno['qualita_sonno']==1){
                        $consigli[] = 'Il tuo riposo è estremamente scarso e probabilmente influisce negativamente sulla tua energia, concentrazione e benessere generale. In questi casi, è essenziale adottare un approccio completo per migliorare il sonno su più livelli. Innanzitutto, cerca di individuare la causa principale: potrebbe trattarsi di stress, ansia, cattive abitudini serali, problemi fisici o ambientali. Per ridurre l’ansia e rilassare la mente prima di dormire, prova tecniche come la respirazione profonda, la meditazione o l’ascolto di suoni rilassanti. Se tendi a rimuginare mentre sei a letto, potresti scrivere i tuoi pensieri su un taccuino prima di dormire, per liberare la mente. Se hai già provato a migliorare la tua routine senza successo e il sonno rimane estremamente scarso, potresti considerare di consultare un medico o uno specialista del sonno per escludere eventuali disturbi come insonnia cronica, apnea notturna o altri problemi di salute.';
                    }else{
                    $consigli[] = "Per migliorare la qualità del tuo sonno, assicurati che la tua stanza sia un ambiente ottimale per il riposo...";
                    }
                }
                
                
                if ($dati_sonno['durata_sonno'] < 6) {
                    if ($dati_sonno['durata_sonno'] <=3) {
                        $consigli[] = "Se dormi meno di tre ore, è fondamentale adottare strategie per affrontare la giornata senza compromettere troppo il benessere. Esporsi alla luce naturale appena svegli aiuta a ridurre la sensazione di sonnolenza e contribuisce a regolare il ritmo circadiano. È importante evitare sonnellini troppo lunghi durante il giorno: se proprio hai bisogno di riposarti, limita il riposo a venti o trenta minuti, così da non rendere ancora più difficile il sonno della notte successiva. Un po’ di movimento leggero, come una passeggiata, può aiutare a contrastare la stanchezza senza esaurire le energie residue. Per recuperare, cerca di andare a letto prima la sera successiva, ma senza dormire eccessivamente, in modo da ripristinare gradualmente il ciclo sonno-veglia senza alterarlo troppo.";
                    }else{
                    $consigli[] = "Dormire meno di 6 ore a notte può avere un impatto negativo sulla tua concentrazione...";
                    }
                }
                
                if ($dati_sonno['durata_sonno'] >=12 && $dati_sonno['qualita_sonno'] <= 3) {
                    $consigli[] = "Se dormi più di dodici ore e ti senti comunque stanco, potresti avere un ritmo sonno-veglia alterato o una qualità del sonno non ottimale. Stabilire un orario fisso per svegliarti e coricarti aiuta il corpo a regolarsi e a evitare episodi di sonno eccessivo che possono portare a una maggiore sensazione di affaticamento. Esporsi alla luce naturale al mattino è un metodo efficace per segnalare al corpo che è il momento di iniziare la giornata, riducendo il bisogno di dormire troppo. Se, nonostante le tante ore di sonno, continui a sentirti spossato, potrebbe essere utile valutare la qualità del riposo: il sonno potrebbe essere frammentato o disturbato da problemi come l’apnea notturna. È anche importante evitare di passare troppo tempo a letto per abitudine: se il sonno eccessivo è legato a stress, ansia o depressione, mantenersi attivi con attività leggere o hobby può aiutare a migliorare la situazione. Se il bisogno di dormire per più di dodici ore è persistente e influisce sulla tua quotidianità, potrebbe essere utile consultare un medico per verificare eventuali problemi di salute o carenze nutrizionali che potrebbero essere alla base di questa condizione.";
                }

              
                if ($dati_sonno['risvegli_notturni'] >4) {
                    $consigli[] = "Se ti svegli frequentemente durante la notte, il tuo sonno potrebbe essere frammentato e di scarsa qualità, portandoti a sentirti stanco e poco riposato al mattino. In questi casi, è essenziale individuare la causa principale. Se il problema è di natura ambientale, assicurati che la tua stanza sia ben ottimizzata per il riposo: usa tende oscuranti per eliminare la luce esterna, riduci i rumori con tappi per le orecchie o un rumore bianco e mantieni una temperatura fresca e confortevole. Se i risvegli sono accompagnati da una sensazione di ansia o stress, potresti beneficiare di tecniche di rilassamento prima di dormire, come lo yoga, esercizi di respirazione o ascoltare musica rilassante. Un altro fattore da considerare è il consumo di sostanze stimolanti: evitare alcol, caffeina e nicotina nelle ore serali può ridurre l’agitazione notturna. Se il problema è legato a dolori fisici o a una posizione scomoda, valuta l’uso di un materasso e un cuscino più adatti alle tue esigenze. Anche alcune condizioni mediche, come l’apnea notturna o il reflusso gastroesofageo, possono causare risvegli multipli: se sospetti che ci sia una causa di questo tipo, potrebbe essere utile consultare un medico per una valutazione più approfondita. Se i risvegli notturni sono molto frequenti e persistono nel tempo, tenere un diario del sonno può aiutarti a capire meglio i fattori scatenanti e a individuare le soluzioni più adatte a te.";
                }
                if ($dati_sonno['risvegli_notturni'] >= 1 && $dati_sonno['risvegli_notturni'] <=3) {
                    $consigli[] = "Se ti capita di svegliarti una o più volte durante la notte, ma in modo non eccessivo, il problema potrebbe essere legato a fattori ambientali o abitudini non ottimali prima di dormire. Per migliorare la qualità del tuo riposo, assicurati innanzitutto che la stanza sia confortevole: una temperatura leggermente fresca, intorno ai 18-20°C, e un ambiente buio e silenzioso favoriscono un sonno più profondo. Se vivi in una zona rumorosa, potresti usare tappi per le orecchie o un rumore bianco per coprire i suoni disturbanti. Anche la routine serale gioca un ruolo fondamentale: evitare schermi luminosi almeno un’ora prima di dormire riduce l’esposizione alla luce blu, che può interferire con la produzione di melatonina. È inoltre utile cenare almeno due o tre ore prima di andare a letto, evitando cibi pesanti, speziati o ricchi di zuccheri, che possono disturbare il sonno. Se tendi a svegliarti per andare in bagno, prova a limitare il consumo di liquidi nelle ore serali. Anche lo stress e l’ansia possono portare a risvegli notturni: praticare tecniche di rilassamento come la respirazione profonda, la meditazione o la lettura di un libro può aiutare a calmare la mente prima di dormire. Se il problema persiste, potresti provare a prendere nota degli orari e delle cause dei tuoi risvegli per individuare schemi ricorrenti e correggere eventuali fattori disturbanti.";
                }

             
                if (!empty($consigli)) {
                    foreach ($consigli as $consiglio) {
                        echo "<li style='margin-bottom: 10px;'>$consiglio</li>";
                    }
                    echo "</ul>";
                } else {
                    echo '<li style="font-size: 20px; font-weight: bold; color:rgb(255, 255, 255); display: flex; align-items: center; justify-content: center;">';
                    echo 'Complimenti, i tuoi dati indicano un buon riposo. Continua così! ';
                    echo '</li>';
                    echo "</ul>";
                }
                
                ?>
        </div>
    </div>
<?php else: ?>
    <div class="consigli-box" style="text-align: center; font-size: 20px; font-weight: bold; color: white; padding: 20px;">
        Registra subito il tuo sonno per nuovi consigli!
    </div>
<?php endif; ?>



</body>
</html>
