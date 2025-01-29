<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["email"]) && $_SESSION["hak_akses"] !== "Admin") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $id_unit_kerja = $_POST['id_unit_kerja'];
    $foto_profil = $_FILES['foto_profil']['name'];
    $hak_akses = $_POST['hak_akses'];
    $status_pengguna = "Aktif"; // Default status

    // Upload photo
    if ($foto_profil) {
        $target_dir = "foto-profil/";
        $target_file = $target_dir . basename($foto_profil);
        move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file);
    }

    $sql = "INSERT INTO users (email, password, nama_lengkap, tanggal_lahir, alamat, id_unit_kerja, foto_profil, hak_akses, status_pengguna) 
            VALUES ('$email', '$password', '$nama_lengkap', '$tanggal_lahir', '$alamat', '$id_unit_kerja', '$foto_profil', '$hak_akses', '$status_pengguna')";

    if ($conn->query($sql) === TRUE) {
        header("Location: pengguna.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
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
    <?php include "navbar.php"; ?>
    <div class="container mt-3">
        <h2>Tambah Pengguna</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" required>
            </div>
            <div class="mb-3">
                <label for="id_unit_kerja" class="form-label">Unit Kerja</label>
                <select class="form-control" id="id_unit_kerja" name="id_unit_kerja" required>
                    <option value="">Pilih Unit Kerja</option>
                    <?php
                    $sql = "SELECT id_unit, nama_unit FROM unit_kerja";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id_unit'] . '">' . $row['nama_unit'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="foto_profil" class="form-label">Foto Profil</label>
                <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*" onchange="previewImage()">
                <img id="foto_preview" src="#" alt="Preview Foto" style="display: none; margin-top: 10px; width: 150px; height: 150px; object-fit: cover; border-radius: 50%;" />
            </div>

            <div class="mb-3">
                <label for="hak_akses" class="form-label">Hak Akses</label>
                <select class="form-control" id="hak_akses" name="hak_akses" required>
                    <option value="1">Super admin</option>
                    <option value="2">Admin Unit</option>
                    <option value="3">Viewer (All)</option>
                    <option value="4">Viewer (Per Unit)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
function previewImage() {
    const file = document.getElementById('foto_profil').files[0];
    const preview = document.getElementById('foto_preview');

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block'; // Menampilkan gambar setelah di-load
        };

        reader.readAsDataURL(file);
    }
}
</script>

</html>