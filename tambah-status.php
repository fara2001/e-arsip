<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $nama_status = htmlspecialchars($_POST["nama_status"], ENT_QUOTES, 'UTF-8');
//     $keterangan = htmlspecialchars($_POST["keterangan"], ENT_QUOTES, 'UTF-8');

//     if (!empty($nama_status)) {
//         $query = "INSERT INTO status (nama_status, keterangan) VALUES ('$nama_status', '$keterangan')";
//         $result = mysqli_query($koneksi, $query);

//         if ($result) {
//             header("Location: status.php?success=Status berhasil ditambahkan");
//             exit;
//         } else {
//             $error_message = "Gagal menambahkan status. Silakan coba lagi.";
//         }
//     } else {
//         $error_message = "Nama status wajib diisi.";
//     }
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            font-family: 'Arial', sans-serif;
            color: #333;
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container mt-5">
        <h2>Tambah Status</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?= $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="proses-tambah-status.php" method="POST">
            <div class="mb-3">
                <label for="nama_status" class="form-label">Nama Status</label>
                <input type="text" name="nama_status" id="nama_status" class="form-control" placeholder="Masukkan nama status" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan keterangan (opsional)">
            </div>
            <div class="mb-2">
                <label for="warna" class="form-label" style="font-size: 0.9em;">Warna</label>
                <input type="color" name="warna" id="warna" class="form-control" style="width: 15%;" value="#007bff">
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Tambah</button>
            <a href="status.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>