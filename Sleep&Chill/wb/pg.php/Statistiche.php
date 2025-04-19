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
  <title>Sleep & Chill - Statistiche</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="../styles.css/styleStats1.css"> 
</head>
<body>
  <header class="header-container">
    <div class="logo">
      <a href="IniziaOra.php">
        <img src="../../images/logo.jpg" alt="Logo Sleep & Chill">
      </a>
    </div>
    <div class="title">Le Tue Statistiche</div>
    <div class="user-info">
      <span>
        <?php 
          echo $_SESSION['nome'] . " " . $_SESSION['cognome'];
          echo isset($_SESSION['eta']) ? " - " . $_SESSION['eta'] . " anni" : " - Età non disponibile";
        ?>
      </span>
      <a href="logout.php" class="logout-button">Logout</a>
    </div>
  </header>

 
  <div class="info-box">
    <p>
      Questa sezione offre una panoramica dettagliata sulle abitudini del sonno dell’utente, fornendo grafici interattivi e analisi personalizzate basate sui dati registrati.
    </p>
  </div>

  
  <div class="container">
    
    <div class="title-container">
  <h2>📊 Andamento Ore di Sonno</h2>
  <h2 class="title-torta">
    <img src="../../images/simbolo_torta.png"  class="simbolo_torta">
    Distribuzione Qualità del Sonno
  </h2>
</div>


    
    <div class="chart-container">
      <canvas id="barChart"></canvas>
      <canvas id="pieChart"></canvas>
    </div>
  </div>

  <script>
    fetch('get_sleep_data.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          alert(data.error);
          return;
        }

        
        const labels = data.map(entry => entry.data_sonno);
        const sleepHours = data.map(entry => entry.durata_sonno);

        
        const qualityCounts = [0, 0, 0, 0, 0];
        data.forEach(entry => {
          if (entry.qualita_sonno >= 1 && entry.qualita_sonno <= 5) {
            qualityCounts[entry.qualita_sonno - 1]++;
          }
        });
        const qualityLabels = ['😞 Pessimo', '🙁 Scarso', '😐 Medio', '🙂 Buono', '😁 Eccellente'];

        
        new Chart(document.getElementById('barChart'), {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Ore di Sonno',
              data: sleepHours,
              backgroundColor: 'rgba(0, 123, 255, 0.7)',
              borderColor: 'rgba(0, 123, 255, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                ticks: { color: 'white' }
              },
              x: {
                ticks: { color: 'white' }
              }
            },
            plugins: {
              legend: {
                position: 'top',
                labels: {
                  boxWidth: 20,
                  padding: 10,
                  color: 'white'
                }
              }
            }
          }
        });

        
        new Chart(document.getElementById('pieChart'), {
          type: 'pie',
          data: {
            labels: qualityLabels,
            datasets: [{
              data: qualityCounts,
              backgroundColor: [
                'rgba(0, 123, 255, 0.8)',
                'rgba(30, 144, 255, 0.8)',
                'rgba(65, 105, 225, 0.8)',
                'rgba(100, 149, 237, 0.8)',
                'rgba(70, 130, 180, 0.8)'
              ]
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
                labels: {
                  boxWidth: 20,
                  padding: 10,
                  color: 'white'
                }
              }
            }
          }
        });
      })
      .catch(error => console.error('Errore nel recupero dati:', error));
  </script>


<div class="historical-data">
      <h2>📅 Storico Dati</h2>
      <label for="sleepData">Seleziona una data:</label>
      <select id="sleepData">
        <?php
          $user_id = $_SESSION['id'];
          $query = "SELECT * FROM monitoraggio_sonno WHERE user_id = ? ORDER BY data_sonno DESC";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['data_sonno'] . "</option>";
          }
        ?>
      </select>
      <button id="deleteData">Elimina Giornata</button>
      <div id="selectedData"></div>
    </div>
  </div>

  <script>
    document.getElementById('sleepData').addEventListener('change', function() {
      let selectedId = this.value;
      fetch(`get_sleep_data.php?id=${selectedId}`)
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            document.getElementById('selectedData').innerHTML = `<p>${data.error}</p>`;
            return;
          }
          document.getElementById('selectedData').innerHTML = `
            <p><strong>Data:</strong> ${data.data_sonno}</p>
            <p><strong>Ore di Sonno:</strong> ${data.durata_sonno} ore</p>
            <p><strong>Qualità del Sonno:</strong> ${data.qualita_sonno}</p>
            <p><strong>Risvegli Notturni:</strong> ${data.risvegli_notturni}</p>
            <p><strong>Note:</strong> ${data.note}</p>
          `;
        });
    });
    
    document.getElementById('deleteData').addEventListener('click', function() {
    let selectedId = document.getElementById('sleepData').value;
    if (!selectedId) {
        alert("Seleziona una data prima di eliminare!");
        return;
    }

    if (confirm('Sei sicuro di voler eliminare questa giornata?')) {
        fetch('delete_sleep_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${selectedId}`
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Errore:', error));
    }
});

  </script>

</body>
</html>
