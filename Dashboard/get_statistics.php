<?php
header('Content-Type: application/json');
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "smpn4tangsel";

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

$currentYear = date("Y");
$monthlyLoans = array_fill(0, 12, 0);

$query = "SELECT MONTH(tgl_pinjam) as month FROM tb_peminjaman WHERE YEAR(tgl_pinjam) = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $currentYear);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $monthlyLoans[$row['month'] - 1]++;
}

$stmt->close();
$koneksi->close();

echo json_encode($monthlyLoans);
exit;
