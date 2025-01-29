<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .table {
            margin-top: 20px;
        }
        .pagination {
            justify-content: center;
        }
        .navbar-nav .nav-link {
            color: #000 !important;
        }
    </style>
</head>
<body>
<?php
        include "navbar.php";
    ?>
    <div class="container mt-3 form-container">
        <h2>Tambah Jenis File</h2>
        <form action="proses-tambah-jenis-file.php" method="post">
            <div class="mb-3">
                <label for="nama_jenis_file" class="form-label">Nama Jenis File</label>
                <input type="text" class="form-control" id="nama_jenis_file" name="nama_jenis_file" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan" required>
            </div>
            <button type="submit" class="btn btn-warning" style="background-color: #ffa726; border-color: #ffa726;">Tambah Jenis File</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

