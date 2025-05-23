<?php
session_start(); 
include '../koneksi.php'; 
$username = "";
$password = "";
$error = "";

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa kredensial
    $sql = $conn->query("SELECT * FROM tb_user WHERE username='$username' AND password='$password'");
    $cek = $sql->num_rows;

    if ($cek > 0) {
        // Login berhasil
        $_SESSION['username'] = $username;
        echo "<meta http-equiv='refresh' content='0;URL=\"../Dashboard/Beranda.php\"'>";
    } else {
        // Login gagal
        $error = "Username atau Password salah";
        echo "<meta http-equiv='refresh' content='2;URL=\"login.html\"'>";
    }
}
?>