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
    <div class="container mt-5">
        <h1 class="mb-4">Referensi Pembuatan Status</h1>
        <a href="tambah-status.php" class="btn btn-warning mb-3" style="background-color: #ffa726; border-color: #ffa726;">Tambah Status</a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Status</th>
                    <th>Nama Status</th>
                    <th>Keterangan</th>
                    <th>Warna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data status
                $sql = "SELECT * FROM status";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id_status'] . "</td>";
                        echo "<td>" . $row['nama_status'] . "</td>";
                        echo "<td>" . $row['keterangan'] . "</td>";
                        echo "<td><button class='btn' style='background-color: " . $row['warna'] . "; color: white;'>" . $row['warna'] . "</button></td>";
                        echo "<td>
                            <a href='edit-status.php?id=" . $row['id_status'] . "' class='btn btn-warning'>Edit</a>
                            <a href='hapus-status.php?id=" . $row['id_status'] . "' class='btn btn-danger'>Hapus</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>Tidak ada data status.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>



<?php
// Tutup koneksi
$conn->close();
?>