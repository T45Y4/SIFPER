<?php
include '../koneksi.php';

if (isset($_GET['noPeminjaman'])) {
    $noPeminjaman = $_GET['noPeminjaman'];

    $stmt = $conn->prepare("SELECT nama, id_buku, judul_buku FROM tb_peminjaman WHERE no_peminjaman = ?");
    $stmt->bind_param("s", $noPeminjaman);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
