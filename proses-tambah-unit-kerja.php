<?php
include "koneksi.php";

if (isset($_POST['nama_unit'])) {
    $nama_unit = $_POST['nama_unit'];
    $keterangan = $_POST['keterangan'];

    $sql = "INSERT INTO unit_kerja (nama_unit, keterangan) VALUES ('$nama_unit', '$keterangan')";
    if ($conn->query($sql) === TRUE) {
        header("Location: unit_kerja.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
