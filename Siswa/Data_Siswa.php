<?php
// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "smpn4tangsel";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data siswa
$siswa = [];
$sql = "SELECT * FROM tb_siswa ORDER BY kelas ASC, nis ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $siswa[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>SIFPER</title>
  <style>
    body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-image: url("../Img/depan.jpg");
            background-size: cover;
            background-position: center;
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
        .hamburger {
            cursor: pointer;
            padding: 10px;
            position: absolute;
            top: 3px;
            left: 0px;
            z-index: 1000;
            font-size: 30px;
            transition: transform 0.3s;
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
            margin: 5px 0;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f1bd00;
            text-align: center;
        }
        .pagination-control {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 20px;
            gap: 10px;
            font-size: 14px;
        }
        .pagination-control select {
            padding: 3px 6px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .pagination-control button {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #f1bd00;
        }
        .pagination-control span {
            min-width: 100px;
            text-align: center;
        }
        .halaman {
            flex: -1;
            margin-right: 10px;
        }
  </style>
</head>
<body>
  <div class="hamburger" onclick="toggleSidebar()">&#9776;</div>
  <div class="sidebar" id="sidebar">
    <h2><img src="../Img/Smpn4tangsel.png" alt="Logo Sekolah" style="width: 150px;"></h2>
    <h4>SIFPER</h4>
    <a href="../Dashboard/Beranda.php"><img src="../Img/dashboard.png" class="icon">Beranda</a>
    <a href="../Buku/Data_Buku.html"><img src="../Img/Buku.png" class="icon">Buku</a>
    <a href="Data_Siswa.php"><img src="../Img/siswa.png" class="icon">Siswa</a>
    <a href="../Peminjaman/Data_Peminjaman.html"><img src="../Img/pinjam.png" class="icon" alt="Peminjaman Icon" />Peminjaman</a>
    <a href="../Pengembalian/Data_Pengembalian.html"><img src="../Img/pengembalian.png" class="icon">Pengembalian</a>
    <a href="../Laporan/laporan.php"><img src="../Img/laporan.png" class="icon">Laporan</a>
    <div class="logout"><a href="../Index.html">Logout</a></div>
  </div>

  <div class="content" id="content">
    <div class="header">
      <h2>Data Siswa</h2>
      <div class="user-info">
        <img src="../Img/profile.png" alt="User Icon" />
        <span>Ris Naia Natasya</span>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>NIS</th>
          <th>NAMA</th>
          <th>Kelas</th>
        </tr>
      </thead>
      <tbody id="studentTableBody">
        <?php if (!empty($siswa)): ?>
          <?php foreach ($siswa as $row): ?>
            <tr>
              <td><?= $row['nis'] ?></td>
              <td><?= $row['nama'] ?></td>
              <td><?= $row['kelas'] ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="3" style="text-align:center;">Tidak ada data</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="pagination-control">
      <label class="halaman">Data Perhalaman</label>
      <select id="rowsPerPage" onchange="changeRowsPerPage()">
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      <span id="pageInfo">1–10</span>
      <button onclick="previousPage()">&lt;</button>
      <button onclick="nextPage()">&gt;</button>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      const content = document.getElementById("content");
      sidebar.classList.toggle("open");
      content.classList.toggle("shift");
    }

    let currentPage = 1;
    let rowsPerPage = 5;

    function updatePage() {
      const rows = document.querySelectorAll("#studentTableBody tr");
      const totalRows = rows.length;
      const start = (currentPage - 1) * rowsPerPage;
      const end = start + rowsPerPage;

      rows.forEach((row, index) => {
        row.style.display = index >= start && index < end ? "" : "none";
      });

      const totalDisplayed = Math.min(end, totalRows);
      document.getElementById("pageInfo").innerText = `${start + 1}–${totalDisplayed} of ${totalRows}`;
    }

    function changeRowsPerPage() {
      rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);
      currentPage = 1;
      updatePage();
    }

    function previousPage() {
      if (currentPage > 1) {
        currentPage--;
        updatePage();
      }
    }

    function nextPage() {
      const rows = document.querySelectorAll("#studentTableBody tr");
      if (currentPage * rowsPerPage < rows.length) {
        currentPage++;
        updatePage();
      }
    }

    window.onload = updatePage;
  </script>
</body>
</html>
