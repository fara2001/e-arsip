<?php
session_start();
include "koneksi.php";

// Cek apakah sesi email ada
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit(); // Tambahkan exit untuk memastikan tidak ada baris lain yang dieksekusi
}

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari tabel jabatan
$sql = "SELECT * FROM jabatan";
$result = $conn->query($sql);

// Cek apakah query berhasil dijalankan
if (!$result) {
    die("Query error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referensi Jabatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
    include "navbar.php";
?>
    <div class="container mt-3">
        <h2>Referensi Jabatan</h2>
        <a href="tambah-jabatan.php" class="btn btn-warning mb-3" style="background-color: #ffa726; border-color: #ffa726;">Tambah Jabatan</a>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Jabatan</th>
                    <th>Nama Jabatan</th>
                    <th>Hak Akses</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $nomor . "</td>";
                        echo "<td>" . $row['id_jabatan'] . "</td>";
                        echo "<td>" . $row['nama_jabatan'] . "</td>";
                        echo "<td>" . $row['hak_akses'] . "</td>";
                        echo "<td>" . $row['keterangan'] . "</td>";
                        echo "<td>
                            <a href='edit-jabatan.php?id=" . $row['id_jabatan'] . "' class='btn btn-warning' style='background-color: #ffa726; border-color: #ffa726;'>Edit</a>
                            <a href='hapus-jabatan.php?id=" . $row['id_jabatan'] . "' class='btn btn-danger' style='background-color: #ff7043; border-color: #ff7043;'>Hapus</a>
                        </td>";
                        echo "</tr>";
                        $nomor++;
                    }
                } else {
                    echo "<tr><td colspan='6'>Tidak ada data jabatan</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
