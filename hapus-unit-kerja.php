<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_unit = $_GET['id'];

    // Query to delete the unit kerja
    $sql = "DELETE FROM unit_kerja WHERE id_unit = '$id_unit'";
    if ($conn->query($sql) === TRUE) {
        header("Location: unit_kerja.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    header("Location: unit_kerja.php");
    exit();
}
?>

