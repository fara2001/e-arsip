<?php
session_start();

require_once 'koneksi.php';

if (!isset($_SESSION["email"]) && $_SESSION["hak_akses"] !== "Admin") {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id_user = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil dihapus');</script>";
        echo "<script>window.location.href='pengguna.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
        echo "<script>window.location.href='pengguna.php';</script>";
    }
}

$conn->close();
