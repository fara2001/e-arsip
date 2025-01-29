<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$id_dokumen = $_GET['id'];

$sql = "DELETE FROM dokumen WHERE id_dokumen='$id_dokumen'";
if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
