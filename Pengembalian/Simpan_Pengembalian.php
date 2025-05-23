<?php
session_start(); 
include '../koneksi.php'; 

// Initialize variables
$noPengembalian = "";
$noPeminjaman = "";
$nama = $idBuku = $judul = $tglKembali = $tglDikembalikan = "";
$dendahari = 500; 
$totalDenda = 0; 

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $noPengembalian = $_POST['noPengembalian']; // ambil dari input
    $noPeminjaman = $_POST['noPeminjaman'];
    $tglKembali = $_POST['tglkembali'];
    $tglDikembalikan = $_POST['tgldikembalikan'];
    $nama = $_POST['nama'];
    $idBuku = $_POST['idBuku'];
    $judul = $_POST['judul'];

    // Hitung keterlambatan dan denda
    $date1 = new DateTime($tglKembali);
    $date2 = new DateTime($tglDikembalikan);
    $diff = $date2->diff($date1);
    $jumlahKeterlambatan = max(0, $diff->days);
    $totalDenda = $jumlahKeterlambatan * $dendahari;

    // Simpan ke database (insert/update)
    $stmt = $conn->prepare("INSERT INTO tb_pengembalian 
        (no_pengembalian, tgl_kembali, tgl_dikembalikan, no_peminjaman, nama, id_buku, judul_buku, denda_per_hari, keterlambatan, total_denda) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
        tgl_kembali=?, tgl_dikembalikan=?, no_peminjaman=?, nama=?, id_buku=?, judul_buku=?, denda_per_hari=?, keterlambatan=?, total_denda=?");

    $stmt->bind_param("sssssssiiissssssiii", 
        $noPengembalian, $tglKembali, $tglDikembalikan, $noPeminjaman, $nama, $idBuku, $judul, 
        $dendahari, $jumlahKeterlambatan, $totalDenda,
        $tglKembali, $tglDikembalikan, $noPeminjaman, $nama, $idBuku, $judul, 
        $dendahari, $jumlahKeterlambatan, $totalDenda
    );

    $stmt->execute();
    $stmt->close();

    header("Location: Data_Pengembalian.html");
    exit();
}

// Generate No. Pengembalian (hanya untuk input baru)
if (!isset($_GET['id']) && $_SERVER["REQUEST_METHOD"] != "POST") {
    $result = $conn->query("SELECT MAX(RIGHT(no_pengembalian, 3)) AS max_kode FROM tb_pengembalian");
    $row = $result->fetch_assoc();
    $lastCode = (int)$row['max_kode'];
    $nextCode = $lastCode + 1;
    $noPengembalian = 'KBP' . str_pad($nextCode, 3, '0', STR_PAD_LEFT);
}

// Edit mode
if (isset($_GET['id'])) {
    $noPengembalian = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM tb_pengembalian WHERE no_pengembalian = ?");
    $stmt->bind_param("s", $noPengembalian);
    $stmt->execute();
    $result = $stmt->get_result();
    $pengembalian = $result->fetch_assoc();
    if ($pengembalian) {
        $noPeminjaman = $pengembalian['no_peminjaman'];
        $tglKembali = $pengembalian['tgl_kembali'];
        $tglDikembalikan = $pengembalian['tgl_dikembalikan'];
        $nama = $pengembalian['nama'];
        $idBuku = $pengembalian['id_buku'];
        $judul = $pengembalian['judul_buku'];
        $dendahari = $pengembalian['denda_per_hari'];
        $jumlahKeterlambatan = $pengembalian['keterlambatan'];
        $totalDenda = $pengembalian['total_denda'];
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($_GET['id']) ? "Edit Pengembalian" : "Input Pengembalian" ?></title>
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
            width: 700px;
            height: auto;
            position: relative;
            z-index: 2;
            margin-left: auto;
            max-height: 100vh;
            overflow-y: auto;
            scrollbar-width: thin;
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
    <h2><?= isset($_GET['id']) ? "Edit Pengembalian" : "Input Pengembalian" ?></h2>
    <form action="" method="post">
        <div class="input-group">
            <label for="noPengembalian">No. Pengembalian:</label>
            <input type="text" id="noPengembalian" name="noPengembalian" value="<?= $noPengembalian ?>" required readonly />
        </div>
        <div class="input-group">
            <label for="tglkembali">Tanggal Buku Harus Dikembalikan:</label>
            <input type="date" id="tglkembali" name="tglkembali" value="<?= $tglKembali ?? '' ?>" required />
        </div>
        <div class="input-group">
            <label for="tgldikembalikan">Tanggal Dikembalikan:</label>
            <input type="date" id="tgldikembalikan" name="tgldikembalikan" value="<?= isset($tglDikembalikan) ? $tglDikembalikan : '' ?>" required />
        </div>
        <div class="input-group">
            <label for="noPeminjaman">No. Peminjaman:</label>
            <input type="text" id="noPeminjaman" name="noPeminjaman" value="<?= $noPeminjaman ?>" required oninput="fetchPeminjamanData()" />
        </div>
        <div class="input-group">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?= $nama ?>" required readonly />
        </div>
        <div class="input-group">
            <label for="idBuku">Id. Buku:</label>
            <input type="text" id="idBuku" name="idBuku" value="<?= $idBuku ?>" required readonly />
        </div>
        <div class="input-group">
            <label for="judul">Judul Buku:</label>
            <input type="text" id="judul" name="judul" value="<?= $judul ?>" required readonly />
        </div>
        <div class="input-group">
            <label for="dendahari">Denda Per Hari (Rp.):</label>
            <input type="number" id="dendahari" name="dendahari" value="<?= $dendahari ?>" required readonly />
        </div>
        <div class="input-group">
            <label for="jumlahKeterlambatan">Jumlah Keterlambatan (Hari):</label>
            <input type="number" id="jumlahKeterlambatan" name="jumlahKeterlambatan" value="<?= $jumlahKeterlambatan ?? 0 ?>" required readonly />
        </div>
        <div class="input-group">
            <label for="totalDenda">Total Denda (Rp.):</label>
            <input type="number" id="totalDenda" name="totalDenda" value="<?= $totalDenda ?>" required readonly />
        </div>
        <div class="button-container">
            <button type="button" onclick="window.location.href='Data_Pengembalian.html'">Kembali</button>
            <button type="submit" name="submit">Simpan</button>
        </div>
    </form>
</div>
<script>
    function fetchPeminjamanData() {
        const noPeminjaman = document.getElementById("noPeminjaman").value;

        fetch('get_peminjaman_data.php?noPeminjaman=' + encodeURIComponent(noPeminjaman))
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById("nama").value = data.nama || "";
                    document.getElementById("idBuku").value = data.id_buku || "";
                    document.getElementById("judul").value = data.judul_buku || "";
                } else {
                    document.getElementById("nama").value = "";
                    document.getElementById("idBuku").value = "";
                    document.getElementById("judul").value = "";
                }
            })
            .catch(error => console.error('Gagal mengambil data:', error));
    }

    document.getElementById("tgldikembalikan").addEventListener("change", function() {
        const tglKembali = new Date(document.getElementById("tglkembali").value);
        const tglDikembalikan = new Date(this.value);
        const dendaPerHari = 500;

        if (tglDikembalikan > tglKembali) {
            const differenceInTime = tglDikembalikan - tglKembali;
            const differenceInDays = Math.ceil(differenceInTime / (1000 * 3600 * 24));
            document.getElementById("jumlahKeterlambatan").value = differenceInDays;
            document.getElementById("totalDenda").value = differenceInDays * dendaPerHari;
        } else {
            document.getElementById("jumlahKeterlambatan").value = 0;
            document.getElementById("totalDenda").value = 0;
        }
    });
</script>
</body>
</html>