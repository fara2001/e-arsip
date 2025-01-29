<?php

include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_jenis_file = $_POST['nama_jenis_file'];
    $keterangan = $_POST['keterangan'];

    $sql = "INSERT INTO jenis_file (nama_jenis_file, keterangan) VALUES ('$nama_jenis_file', '$keterangan')";

    if ($conn->query($sql) === TRUE) {
        header("Location: jenis_file.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
