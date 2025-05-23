<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "smpn4tangsel";

$koneksi = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM tb_peminjaman WHERE no_peminjaman = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    exit();
}

// Handle fetch books request (AJAX)
if (isset($_GET['fetch_books'])) {
    $query = "SELECT * FROM tb_peminjaman";
    $result = $koneksi->query($query);

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($books);
    exit();
}

// Jika tidak pakai AJAX, render langsung data buku ke HTML
$ambildata = $koneksi->query("SELECT * FROM tb_peminjaman");
while($tampil = $ambildata->fetch_assoc()) {
    echo "
    <tr>
        <td>{$tampil['no_peminjaman']}</td>
        <td>{$tampil['nis']}</td>
        <td>{$tampil['nama']}</td>
        <td>{$tampil['id_buku']}</td>
        <td>{$tampil['judul_buku']}</td>
        <td>{$tampil['tgl_pinjam']}</td>
        <td>{$tampil['tgl_kembali']}</td>
        <td>
            <button onclick=\"window.location.href='Simpan_Peminjaman.php?id={$tampil['no_peminjaman']}'\">
                <img src='../Img/edit.png' alt='Edit' style='width:20px;' />
            </button>
            <button onclick=\"confirmDelete('{$tampil['no_peminjaman']}')\">
                <img src='../Img/delete.png' alt='Delete' style='width:20px;' />
            </button>
        </td>
    </tr>";
}
?>
