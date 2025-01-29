<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jabatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
        include "navbar.php";
    ?>
    <div class="container mt-3 form-container">
        <h2>Tambah Jabatan</h2>
        <form action="proses-tambah-jabatan.php" method="post">
            <div class="mb-3">
                <label for="id_jabatan" class="form-label">ID Jabatan</label>
                <input type="text" class="form-control" id="id_jabatan" name="id_jabatan" required>
            </div>
            <div class="mb-3">
                <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" required>
            </div>
            <div class="mb-3">
                <label for="hak_akses" class="form-label">Hak Akses</label>
                <select class="form-select" id="hak_akses" name="hak_akses" required>
                    <option value="1">Super admin (bisa baca, tambah, hapus, edit, dan full access ke halaman referensi unit kerja, jabatan, dan pengguna)</option>
                    <option value="2">Admin (bisa baca, tambah, hapus, edit)</option>
                    <option value="3">Editor (bisa baca, tambah, edit)</option>
                    <option value="4">Viewer (hanya bisa di baca)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan" required>
            </div>
            <button type="submit" class="btn btn-warning" style="background-color: #ffa726; border-color: #ffa726;">Tambah Jabatan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
