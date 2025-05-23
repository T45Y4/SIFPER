<?php
// Start the session at the very beginning
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "smpn4tangsel";

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

// Check if the request is for JSON data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetchStatistics') {
    // Get the current year
    $currentYear = date("Y");
    $monthlyLoans = array_fill(0, 12, 0); // Initialize counts for each month

    // Query to count borrows per month for the current year
    $query = "SELECT MONTH(tgl_pinjam) as month FROM tb_peminjaman WHERE YEAR(tgl_pinjam) = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $currentYear);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $monthlyLoans[$row['month'] - 1]++; // Increment the count for the corresponding month
    }

    // Close the statement and connection
    $stmt->close();
    $koneksi->close();

    header('Content-Type: application/json');
    echo json_encode($monthlyLoans);
    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIFPER</title>
    <style>
     body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        height: 100vh;
        background-image: url("../Img/depan.jpg");
        background-size: cover;
        background-position: center;
        flex-direction: column;
      }
      .hamburger {
        cursor: pointer;
        width: 40px; 
        height: 40px; 
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 2px;
        bottom: 1px;
        left: 0px;
        z-index: 1000;
        font-size: 30px;
        transition: transform 0.3s, background-color 0.3s;
        border-radius: 5px;
        margin-top: 5px;
        margin-left: 5px;
      }
      .hamburger:hover {
        background-color: rgba(241, 189, 0, 0.7);
      }
      
      .sidebar {
        width: 160px;
        background-color: rgba(244, 244, 244, 0.9);
        padding: 20px;
        height: 100vh;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        position: fixed;
        left: -200px;
        transition: left 0.3s, opacity 0.3s;
        opacity: 0.9;
        overflow-y: auto;
      }
      .sidebar.open {
        left: 0;
        opacity: 1;
      }
      .sidebar h2 {
        text-align: center;
      }
      .sidebar h4 {
        background: linear-gradient(90deg, #003366, #f1bd00);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-size: 30px;
        margin: 10px 0;
      }
      .sidebar a {
        display: flex;
        align-items: center;
        padding: 10px;
        margin: 10px 0;
        text-decoration: none;
        color: black;
        border-radius: 6px;
        transition: background 0.3s;
        width: 100%;
      }
      .sidebar a:hover {
        background-color: rgba(241, 189, 0, 0.7);
      }
      .icon {
        width: 20px;
        margin-right: 10px;
      }
      .logout {
        display: flex;
        margin-top: 20px;
        margin-bottom: 20px;
        text-align: center;
      }
      .logout a {
        background-color: rgba(0, 0, 0, 0.9);
        color: rgb(255, 255, 255);
        padding: 10px 50px;
        border-radius: 20px;
        text-decoration: none;
        display: inline-block;
      }
      .logout a:hover {
        background-color: rgba(241, 189, 0, 1);
      }
      .content {
        flex-grow: 1;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 0px;
        margin-left: 0;
        transition: margin-left 0.3s;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: auto;
      }
      .content.shift {
        margin-left: 200px;
      }
      .header {
        display: flex;
        margin-top: 10px;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
      }
      .header img.logo {
        width: 50px;
        height: auto;
      }
      .user-info {
        display: flex;
        align-items: center;
        margin-left: auto;
      }
      .user-info img {
        width: 40px;
        height: auto;
        border-radius: 50%;
      }
      .user-info span {
        margin-left: 10px;
        font-weight: bold;
      }
      .chart-container {
        margin-top: 20px;
        position: relative;
        width: 100%;
        text-align: center;
      }
      .chart-box {
        background-color: rgb(253, 253, 253);
        padding: 15px;
        border-radius: 10px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
        height: auto;
        margin-bottom: 20px;
      }
      canvas#loanChart {
        width: 100% !important;
        height: 400px !important;
      }
      .vision-mission {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
      }
      .vision-mission h3 {
        text-align: center;
      }
      .mission-box,
      .vision-box {
        background-color: rgb(253, 253, 253);
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-sizing: border-box;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
      }
      .credit-sections {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 10px;
      }
      .credit-box-buat {
        background-color: rgb(255, 255, 255);
        padding: 10px;
        border-radius: 10px;
        width: 30%;
        box-sizing: border-box;
        text-align: center;
        margin-right: 10px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
      }
      .credit-box-buat img {
        width: 20px;
        margin-right: 5px;
        vertical-align: middle;
      }
      .name-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 5px 0;
        gap: 10px;
      }
      @media (max-width: 600px) {
        .total-box {
          width: 100%;
          margin-bottom: 10px;
        }
        .vision-mission,
        .credit-sections {
          flex-direction: column;
        }
        .mission-box,
        .vision-box,
        .credit-box {
          width: 100%;
          margin-bottom: 10px;
        }
      }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body>
    <div class="hamburger" onclick="toggleSidebar()">&#9776;</div>

   <div class="sidebar" id="sidebar">
      <h2>
        <img src="../Img/Smpn4tangsel.png" alt="Logo Sekolah" style="width: 150px; height: auto"/>
      </h2>
      <h4>SIFPER</h4>
      <a href="Beranda.php"><img src="../Img/dashboard.png" class="icon" alt="Dashboard Icon" />Beranda</a>
        <a href="../Buku/Data_Buku.html"><img src="../Img/Buku.png" class="icon" alt="Buku Icon" />Buku</a>
        <a href="../Siswa/Data_Siswa.php"><img src="../Img/siswa.png" class="icon" alt="Siswa Icon" />Siswa</a>
        <a href="../Peminjaman/Data_Peminjaman.html"><img src="../Img/pinjam.png" class="icon" alt="Peminjaman Icon" />Peminjaman</a>
        <a href="../Pengembalian/Data_Pengembalian.html"><img src="../Img/pengembalian.png" class="icon" alt="Pengembalian Icon" />Pengembalian</a>
        <a href="../Laporan/laporan.php"><img src="../Img/laporan.png" class="icon" alt="Laporan Icon" />Laporan</a>
        <div class="logout"><a href="../Index.html">Logout</a></div>
    </div>

    <div class="content" id="content">
      <div class="header">
        <h2>BERANDA</h2>
        <div class="user-info">
          <img src="../Img/profile.png" alt="User Icon" />
          <span>Ris Naia Natasya</span>
        </div>
      </div>

      <div class="chart-container">
        <div class="chart-box">
        <h3 id="chartTitle">Statistik Peminjaman</h3>
          <div id="loadingIndicator">Memuat grafik...</div>
          <canvas id="loanChart"></canvas>
        </div>
      </div>

      <div class="vision-mission">
        <div class="vision-box">
          <h3>Visi</h3>
          <p>
            Menjadi organisasi siswa yang inovatif, berwawasan global, serta
            berkarakter unggul dalam membangun lingkungan sekolah yang harmonis,
            inspiratif, dan berdaya saing tinggi.
          </p>
        </div>
        <div class="mission-box">
          <h3>Misi</h3>
          <ul>
            <li>
              <strong>Meningkatkan Jiwa Kepemimpinan</strong> - Membentuk siswa
              yang memiliki jiwa kepemimpinan yang bertanggung jawab, disiplin,
              dan berintegritas tinggi.
            </li>
            <li>
              <strong>Mendorong Kreativitas dan Inovasi</strong> - Menyediakan
              wadah bagi siswa untuk mengembangkan bakat, minat, dan inovasi di
              bidang akademik maupun non-akademik.
            </li>
            <li>
              <strong>Memperkuat Rasa Solidaritas dan Kepedulian Sosial</strong>
              - Mengadakan kegiatan sosial dan kemanusiaan yang membangun
              kepedulian terhadap sesama dan lingkungan.
            </li>
            <li>
              <strong
                >Mewujudkan Lingkungan Sekolah yang Nyaman dan Inklusif</strong
              >
              - Menciptakan atmosfer sekolah yang aman, nyaman, serta mendorong
              kolaborasi dan kebersamaan di antara siswa.
            </li>
            <li>
              <strong>Menanamkan Nilai-Nilai Moral dan Kebangsaan</strong> -
              Mengembangkan karakter siswa yang menjunjung tinggi nilai-nilai
              moral, budaya, dan kebangsaan untuk menjadi generasi yang berdaya
              saing global.
            </li>
          </ul>
        </div>
      </div>

      <div class="credit-sections">
        <div class="credit-box-buat">
          <strong>Dibuat Oleh:</strong><br />
          <div class="name-container">
            <img src="../Img/user.png" alt="User Icon" /> Dila Kartika Putri
            (221011450027)
          </div>
          <div class="name-container">
            <img src="../Img/user.png" alt="User Icon" /> Ris Naia Natasya
            (221011450074)
          </div>
          <div class="name-container">
            <img src="../Img/user.png" alt="User Icon" /> Zesi Yaqumi
            (221011450067)
          </div>
        </div>
      </div>
    </div>

    <script>
      function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        const hamburger = document.querySelector(".hamburger");

        sidebar.classList.toggle("open");
        content.classList.toggle("shift");
        hamburger.classList.toggle("open");
      }

      document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("chartTitle").textContent = "Statistik Peminjaman Tahun " + new Date().getFullYear();
        
        const loadingIndicator = document.getElementById('loadingIndicator');
        const chartCanvas = document.getElementById('loanChart');
        
        fetch('get_statistics.php')
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(monthlyLoans => {
            loadingIndicator.style.display = 'none';
            chartCanvas.style.display = 'block';
            createChart(monthlyLoans);
          })
          .catch(error => {
            console.error('Error fetching loan data:', error);
            loadingIndicator.innerHTML = '<p>Error loading chart data. Please try again later.</p>';
            createChart([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]); // Placeholder data
          });
      });


      function createChart(monthlyLoans) {
        const ctx = document.getElementById("loanChart").getContext("2d");
        new Chart(ctx, {
          type: "bar",
          data: {
            labels: [
              "Jan", "Feb", "Mar", "Apr", "May", "Jun",
              "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ],
            datasets: [{
              label: "Peminjaman per Bulan",
              data: monthlyLoans,
              borderColor: "rgba(241, 189, 0, 1)",
              backgroundColor: "rgba(241, 189, 0, 0.5)",
              borderWidth: 2,
              fill: true,
            }],
          },
          options: {
            responsive: true,
            plugins: {
              legend: { display: true },
              tooltip: {
                callbacks: {
                  title: function(tooltipItems) {
                    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
                                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                    return monthNames[tooltipItems[0].dataIndex];
                  }
                }
              },
            },
            scales: {
              x: {
                title: {
                  display: true,
                  text: "Bulan",
                  font: { weight: 'bold' }
                },
              },
              y: {
                title: {
                  display: true,
                  text: "Jumlah Peminjaman",
                  font: { weight: 'bold' }
                },
                beginAtZero: true,
                ticks: { precision: 0 }
              },
            },
          },
        });
      }
    </script>
  </body>
</html>