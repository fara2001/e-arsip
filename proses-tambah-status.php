<?php
session_start();
include "koneksi.php";

// Cek apakah pengguna sudah login
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

// Cek apakah request berasal dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_status = htmlspecialchars($_POST["nama_status"], ENT_QUOTES, 'UTF-8');
    $keterangan = htmlspecialchars($_POST["keterangan"], ENT_QUOTES, 'UTF-8');
    $warna = htmlspecialchars($_POST["warna"], ENT_QUOTES, 'UTF-8');

    // Validasi input
    if (!empty($nama_status)) {
        // Query untuk menambahkan status ke database
        $query = "INSERT INTO status (nama_status, keterangan, warna) VALUES ('$nama_status', '$keterangan', '$warna')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect ke halaman status.php dengan pesan sukses
            header("Location: status.php?success=Status berhasil ditambahkan");
            exit;
        } else {
            // Redirect ke halaman tambah-status.php dengan pesan error
            header("Location: tambah-status.php?error=Gagal menambahkan status. Silakan coba lagi.");
            exit;
        }
    } else {
        // Redirect ke halaman tambah-status.php dengan pesan error validasi
        header("Location: tambah-status.php?error=Nama status wajib diisi.");
        exit;
    }
} else {
    // Redirect ke halaman tambah-status.php jika akses langsung tanpa POST
    header("Location: tambah-status.php");
    exit;
}
