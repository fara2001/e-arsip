<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION["email"]) && $_SESSION["hak_akses"] !== "Admin") {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Arsip</title>
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
        <h2>Manajemen Pengguna</h2>
        <a href="tambah-pengguna.php" class="btn btn-warning mb-3" style="background-color: #ffa726; border-color: #ffa726;">Tambah Pengguna</a>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto Profil</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Hak Akses</th>
                    <th>Unit Kerja</th>
                    <th>Status Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT users.*, jabatan.nama_jabatan, unit_kerja.nama_unit 
                        FROM users
                        LEFT JOIN jabatan ON users.id_jabatan = jabatan.id_jabatan
                        LEFT JOIN unit_kerja ON users.id_unit_kerja = unit_kerja.id_unit";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $no = 1;
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $no . "</td>";
                        $fotoProfilPath = "foto-profil/" . $row['foto_profil'];
                        if (empty($row['foto_profil']) || !file_exists($fotoProfilPath)) {
                            $fotoProfilPath = "foto-profil/avatar.png";
                        }
                        echo "<td><img src='" . $fotoProfilPath . "' width='100px' height='100px'></td>";
                        echo "<td>" . $row['nama_lengkap'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        switch ($row['hak_akses']) {
                            case 1:
                                echo "<td>Super admin</td>";
                                break;
                            case 2:
                                echo "<td>Admin Unit</td>";
                                break;
                            case 3:
                                echo "<td>Viewer (All)</td>";
                                break;
                            case 4:
                                echo "<td>Viewer (Per Unit)</td>";
                                break;
                            default:
                                echo "<td>-</td>";
                        }
                        echo "<td>" . $row['nama_unit'] . "</td>";
                        echo "<td>" . $row['status_pengguna'] . "</td>";
                        echo "<td>
                            <a href='edit-pengguna.php?id=" . $row['id_user'] . "' class='btn btn-warning'>Edit</a>";
                            ?>
                            <a href="hapus-pengguna.php?id=<?php echo $row['id_user'] ?>" class='btn btn-danger' onclick="return confirm('Anda yakin ingin menghapus pengguna ini?')">Hapus</a>
                            <?php
                        echo "</td>";
                        echo "</tr>";
                        $no++;
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

