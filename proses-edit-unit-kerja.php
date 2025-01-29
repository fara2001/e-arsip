<?php
include "koneksi.php";

if (isset($_POST['submit'])) {
    // Mengambil data dari form
    $id_unit = $conn->real_escape_string($_POST['id_unit']);
    $nama_unit = $conn->real_escape_string($_POST['nama_unit']);
    $keterangan = $conn->real_escape_string($_POST['keterangan']);

    // Query update data
    $sql_update = "UPDATE unit_kerja SET nama_unit = '$nama_unit', keterangan = '$keterangan' WHERE id_unit = '$id_unit'";

    // Eksekusi query dan cek hasilnya
    if ($conn->query($sql_update) === TRUE) {
        header("Location: unit_kerja.php?message=success");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    // Jika file diakses langsung, arahkan ke halaman utama
    header("Location: unit_kerja.php");
    exit();
}