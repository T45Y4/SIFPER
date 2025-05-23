<?php
session_start(); 
include '../koneksi.php'; 

// Fetch data dari tb_siswa dan tb_buku untuk auto-fill
$siswaData = [];
$bukuData = [];

$resultSiswa = $conn->query("SELECT nis, nama FROM tb_siswa");
while ($row = $resultSiswa->fetch_assoc()) {
    $siswaData[$row['nama']] = $row['nis']; // Store NIS with name as key
}

$resultBuku = $conn->query("SELECT id_buku, judul FROM tb_buku");
while ($row = $resultBuku->fetch_assoc()) {
    $bukuData[$row['id_buku']] = $row['judul'];
}

// Initialize variables
$noPeminjaman = "";
$nis = $nama = $idBuku = $judul = $tglPinjam = $tglKembali = "";
if (!isset($_GET['id'])) {
    $result = $conn->query("SELECT MAX(RIGHT(no_peminjaman, 3)) AS max_kode FROM tb_peminjaman");
    $row = $result->fetch_assoc();
    $lastCode = (int)$row['max_kode'];
    $nextCode = $lastCode + 1;
    $noPeminjaman = 'PB' . str_pad($nextCode, 3, '0', STR_PAD_LEFT);
}

// Check for edit mode
if (isset($_GET['id'])) {
    $noPeminjaman = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM tb_peminjaman WHERE no_peminjaman = ?");
    $stmt->bind_param("s", $noPeminjaman);
    $stmt->execute();
    $result = $stmt->get_result();
    $peminjaman = $result->fetch_assoc();
    if ($peminjaman) {
        $nis = $peminjaman['nis'];
        $nama = $peminjaman['nama'];
        $idBuku = $peminjaman['id_buku'];
        $judul = $peminjaman['judul_buku'];
        $tglPinjam = $peminjaman['tgl_pinjam'];
        $tglKembali = $peminjaman['tgl_kembali'];
    }
    $stmt->close();
}

// Handle form submission
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $noPeminjaman = $_POST['noPeminjaman']; // Tambahkan ini!
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $idBuku = $_POST['idBuku'];
    $judul = $_POST['judul'];
    $tglPinjam = $_POST['tglPinjam'];
    $tglKembali = date('Y-m-d', strtotime($tglPinjam . ' + 7 days'));


    // Insert or update data
    if (isset($_GET['id'])) {
        $stmt = $conn->prepare("UPDATE tb_peminjaman SET nis=?, nama=?, id_buku=?, judul_buku=?, tgl_pinjam=?, tgl_kembali=? WHERE no_peminjaman=?");
        $stmt->bind_param("sssssss", $nis, $nama, $idBuku, $judul, $tglPinjam, $tglKembali, $noPeminjaman);
    } else {
        $stmt = $conn->prepare("INSERT INTO tb_peminjaman (no_peminjaman, nis, nama, id_buku, judul_buku, tgl_pinjam, tgl_kembali) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $noPeminjaman, $nis, $nama, $idBuku, $judul, $tglPinjam, $tglKembali);
    }
    $stmt->execute();
    $stmt->close();

    // Redirect after successful insert or update
    header("Location: Data_Peminjaman.html");
    exit();
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($_GET['id']) ? "Edit Peminjaman" : "Input Peminjaman" ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("../Img/depan.jpg");
            background-size: cover;
            background-position: center;
            filter: blur(8px); 
            z-index: 1; 
        }
        .logo-container {
            width: 50%; 
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2; 
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9); 
            padding: 20px;
            font-weight: bold;
            text-align: left;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
            width: 700px; /* Adjust width */
            height: auto;
            position: relative; 
            z-index: 2; 
            margin-left: auto; /* Align to the right */
        }
        h2 {
            font-size: 36px;
            margin-bottom: 20px;
            text-align: center;
        }
        .input-group {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        label {
            width: 120px; 
            margin-right: 10px; 
        }
        input[type="text"],
        input[type="number"] {
            flex: 1; 
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"] {
            flex: 1; 
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
}
        .button-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between; /* Align buttons to the right */
            align-items: center;
        }
        button {
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            width: 20%;
            margin-left: 10px; /* Space between buttons */
            margin-top: 30px;
            margin-bottom: 10px;
        }
        button:hover {
            background-color: rgb(241, 189, 0);
        }
    </style>
</head>
<body>
<div class="logo-container">
        <img src="../Img/Smpn4tangsel.png" alt="Logo Sekolah" style="width: 300px; height: auto;" />
    </div>
    <div class="container">
        <h2><?= isset($_GET['id']) ? "Edit Peminjaman" : "Input Peminjaman" ?></h2>
        <form action="Simpan_Peminjaman.php<?= isset($_GET['id']) ? '?id=' . $noPeminjaman : '' ?>" method="post">
            <div class="input-group">
                <label for="noPeminjaman">No. Peminjaman:</label>
                <input type="text" id="noPeminjaman" name="noPeminjaman" value="<?= $noPeminjaman ?>" required readonly />
            </div>
            <div class="input-group">
                <label for="nis">NIS:</label>
                <input type="text" id="nis" name="nis" value="<?= $nis ?>" required oninput="updateName()" />
            </div>
            <div class="input-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" value="<?= $nama ?>" required oninput="updateNIS()" />
            </div>
            <div class="input-group">
                <label for="idBuku">Id Buku:</label>
                <input type="text" id="idBuku" name="idBuku" value="<?= $idBuku ?>" required oninput="updateBookTitle()" />
            </div>
            <div class="input-group">
                <label for="judul">Judul Buku:</label>
                <input type="text" id="judul" name="judul" value="<?= $judul ?>" required readonly />
            </div>
            <div class="input-group">
                <label for="tglPinjam">Tanggal Peminjaman:</label>
                <input type="date" id="tglPinjam" name="tglPinjam" value="<?= $tglPinjam ?>" required />
            </div>
            <div class="button-container">
                <button type="button" onclick="window.location.href='Data_Peminjaman.html'">Kembali</button>
                <button type="submit" name="<?= isset($_GET['id']) ? 'edit' : 'submit' ?>"><?= isset($_GET['id']) ? 'Perbarui' : 'Simpan' ?></button>
            </div>
        </form>
    </div>

    <script>
        const siswaData = <?= json_encode($siswaData) ?>;
        const bukuData = <?= json_encode($bukuData) ?>;

        function updateNIS() {
            const namaInput = document.getElementById("nama").value;
            const nisInput = document.getElementById("nis");
            if (siswaData[namaInput]) {
                nisInput.value = siswaData[namaInput];
            } else {
                nisInput.value = "";
            }
        }

        function updateBookTitle() {
            const idBukuInput = document.getElementById("idBuku").value;
            const judulInput = document.getElementById("judul");
            if (bukuData[idBukuInput]) {
                judulInput.value = bukuData[idBukuInput];
            } else {
                judulInput.value = "";
            }
        }
    </script>
</body>
</html>