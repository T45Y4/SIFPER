<?php
session_start(); 
include '../koneksi.php';

$editMode = false;
$id = $judul = $pengarang = $penerbit = $tahun = $kategori = $stok = "";

// Jika ada ID di URL, berarti sedang edit
if (isset($_GET['id'])) {
    $editMode = true;
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM tb_buku WHERE id_buku = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $buku = $result->fetch_assoc();
    if ($buku) {
        $judul = $buku['judul'];
        $pengarang = $buku['pengarang'];
        $penerbit = $buku['penerbit'];
        $tahun = $buku['tahun_terbit'];
        $kategori = $buku['kategori'];
        $stok = $buku['stok'];
    }
    $stmt->close();
}

// Proses ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];

    if (isset($_POST['edit'])) {
        // Update data
        $stmt = $conn->prepare("UPDATE tb_buku SET judul=?, pengarang=?, penerbit=?, tahun_terbit=?, kategori=?, stok=? WHERE id_buku=?");
        $stmt->bind_param("sssssis", $judul, $pengarang, $penerbit, $tahun, $kategori, $stok, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Tambah data baru
        $stmt = $conn->prepare("INSERT INTO tb_buku (id_buku, judul, pengarang, penerbit, tahun_terbit, kategori, stok) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $id, $judul, $pengarang, $penerbit, $tahun, $kategori, $stok);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: Data_Buku.html");
    exit();
}

$conn->close();
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?= $editMode ? "Edit Buku" : "Input Buku" ?></title>
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
        .button-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between; 
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
            margin-left: 10px;
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
        <h2><?= $editMode ? "Edit Data Buku" : "Tambah Data Buku" ?></h2>
        <form action="Simpan_Buku.php<?= $editMode ? '?id=' . $id : '' ?>" method="post">
            <div class="input-group">
                <label for="id">Id Buku:</label>
                <input type="text" id="id" name="id" value="<?= $id ?>" <?= $editMode ? 'readonly' : '' ?> required />
            </div>
            <div class="input-group">
                <label for="judul">Judul:</label>
                <input type="text" id="judul" name="judul" value="<?= $judul ?>" required />
            </div>
            <div class="input-group">
                <label for="pengarang">Pengarang:</label>
                <input type="text" id="pengarang" name="pengarang" value="<?= $pengarang ?>" required />
            </div>
            <div class="input-group">
                <label for="penerbit">Penerbit:</label>
                <input type="text" id="penerbit" name="penerbit" value="<?= $penerbit ?>" required />
            </div>
            <div class="input-group">
                <label for="tahun">Tahun:</label>
                <input type="number" id="tahun" name="tahun" value="<?= $tahun ?>" required />
            </div>
            <div class="input-group">
                <label for="kategori">Kategori:</label>
                <input type="text" id="kategori" name="kategori" value="<?= $kategori ?>" required />
            </div>
            <div class="input-group">
                <label for="stok">Stok:</label>
                <input type="number" id="stok" name="stok" value="<?= $stok ?>" required />
            </div>
            <div class="button-container">
                <button type="button" onclick="window.location.href='Data_Buku.html'">Kembali</button>
                <button type="submit" name="<?= $editMode ? 'edit' : 'submit' ?>"><?= $editMode ? 'Perbarui' : 'Simpan' ?></button>
            </div>
        </form>
    </div>
</body>
</html>