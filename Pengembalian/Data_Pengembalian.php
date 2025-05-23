<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "smpn4tangsel";

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM tb_pengembalian WHERE no_pengembalian = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    exit();
}

if (isset($_GET['fetch_books'])) {
    $query = "SELECT * FROM tb_pengembalian";
    $result = $koneksi->query($query);

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($books);
    exit();
}

$ambildata = $koneksi->query("SELECT * FROM tb_pengembalian");
while($tampil = $ambildata->fetch_assoc()) {
    echo "
    <tr>
        <td>{$tampil['no_pengembalian']}</td>
        <td>{$tampil['tgl_kembali']}</td>
        <td>{$tampil['tgl_dikembalikan']}</td>
        <td>{$tampil['no_peminjaman']}</td>
        <td>{$tampil['nama']}</td>
        <td>{$tampil['id_buku']}</td>
        <td>{$tampil['judul_buku']}</td>
        <td>{$tampil['denda_per_hari']}</td>
        <td>{$tampil['keterlambatan']}</td>
        <td>{$tampil['total_denda']}</td>
        <td>
            <button onclick=\"window.location.href='Simpan_Pengembalian.php?id={$tampil['no_pengembalian']}'\">
                <img src='../Img/edit.png' alt='Edit' style='width:20px;' />
            </button>
            <button onclick=\"confirmDelete('{$tampil['no_pengembalian']}')\">
                <img src='../Img/delete.png' alt='Delete' style='width:20px;' />
            </button>
        </td>
    </tr>";
}
?>
