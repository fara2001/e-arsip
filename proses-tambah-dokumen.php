<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_dokumen = $_POST['no_dokumen'];
    $nama_dokumen = $_POST['nama_dokumen'];
    $unit_kerja = $_POST['unit_kerja'];
    $status = $_POST['status'];
    $file = $_FILES['file']['name'];
    $jenis_file = $_POST['jenis_file'];
    $nama_pengunggah_dokumen = $_POST['nama_pengunggah_dokumen'];
    // Upload file
    $target_dir = "uploads/";
    $target_file = $target_dir . date('Y-m-d-H-i-s') . '-' . rand(1000, 9999) . '-' . basename($_FILES["file"]["name"]);
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

    $sql = "INSERT INTO dokumen (no_dokumen, nama_dokumen, unit_kerja, status, file, jenis_file, nama_pengunggah_dokumen) VALUES ('$no_dokumen', '$nama_dokumen', '$unit_kerja', '$status', '$target_file', '$jenis_file', '$nama_pengunggah_dokumen')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
