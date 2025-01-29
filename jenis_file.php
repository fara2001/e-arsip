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
    <div class="container mt-3">
        <h2>Jenis File</h2>
        <a href="form-tambah-jenis-file.php" class="btn btn-success" style="background-color: #00b300; border-color: #00b300;">Tambah Jenis File</a>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Jenis File</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data jenis file
                $sql = "SELECT * FROM jenis_file";
                $result = $conn->query($sql);
                $nomor = 1;
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $nomor . "</td>";
                        echo "<td>" . $row['nama_jenis_file'] . "</td>";
                        echo "<td>" . $row['keterangan'] . "</td>";
                        echo "<td>
                            <a href='form-edit-jenis-file.php?id=" . $row['id_jenis_file'] . "' class='btn btn-warning' style='background-color: #ffa726; border-color: #ffa726;'>Edit</a>
                            <a href='jenis_file.php?aksi=hapus&id=" . $row['id_jenis_file'] . "' class='btn btn-danger' style='background-color: #ff7043; border-color: #ff7043;'>Hapus</a>
                        </td>";
                        echo "</tr>";
                        $nomor++;
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada data.</td></tr>";
                }
                ?>
            </tbody>
        </table>
       
    </div>
    <?php
    if (isset($_GET['aksi'])) {
        if ($_GET['aksi'] == 'hapus') {
            $id_jenis_file = $_GET['id'];
            $sql = "DELETE FROM jenis_file WHERE id_jenis_file = '$id_jenis_file'";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Data berhasil dihapus');</script>";
                echo "<script>window.location.href='jenis_file.php';</script>";
            } else {
                echo "<script>alert('Gagal menghapus data');</script>";
                echo "<script>window.location.href='jenis_file.php';</script>";
            }
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
