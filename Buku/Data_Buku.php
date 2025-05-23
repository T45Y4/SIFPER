<?php
// Langsung buat koneksi database tanpa include
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
    $query = "DELETE FROM tb_buku WHERE id_buku = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    exit();
}

// Handle fetch books request (AJAX)
if (isset($_GET['fetch_books'])) {
    $query = "SELECT * FROM tb_buku";
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
$ambildata = $koneksi->query("SELECT * FROM tb_buku");
while($tampil = $ambildata->fetch_assoc()) {
    echo "
    <tr>
        <td>{$tampil['id_buku']}</td>
        <td>{$tampil['judul']}</td>
        <td>{$tampil['pengarang']}</td>
        <td>{$tampil['penerbit']}</td>
        <td>{$tampil['tahun_terbit']}</td>
        <td>{$tampil['kategori']}</td>
        <td>{$tampil['stok']}</td>
        <td>
            <button onclick=\"window.location.href='edit_buku.php?id={$tampil['id_buku']}'\">
                <img src='../Img/edit.png' alt='Edit' style='width:20px;' />
            </button>
            <button onclick=\"confirmDelete('{$tampil['id_buku']}')\">
                <img src='../Img/delete.png' alt='Delete' style='width:20px;' />
            </button>
        </td>
    </tr>";
}
?>
