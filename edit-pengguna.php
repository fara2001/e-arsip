<?php
session_start();
include "koneksi.php";

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Pastikan ada parameter ID di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID pengguna tidak ditemukan.");
}

$id_user = $_GET['id'];

// Ambil data pengguna berdasarkan ID
$sql = "SELECT * FROM users WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Pengguna tidak ditemukan.");
}

$user = $result->fetch_assoc();

// Ambil daftar jabatan dan unit kerja untuk dropdown
$jabatan_query = $conn->query("SELECT * FROM jabatan");
$unit_query = $conn->query("SELECT * FROM unit_kerja");

// Proses update data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $id_jabatan = $_POST['id_jabatan'];
    $id_unit_kerja = $_POST['id_unit_kerja'];
    $hak_akses = $_POST['hak_akses'];
    $status_pengguna = $_POST['status_pengguna'];
    $password = trim($_POST['password']);

    // Validasi sederhana
    if (empty($nama_lengkap) || empty($email)) {
        $error = "Nama dan email tidak boleh kosong.";
    } else {
        // Update data ke database
        if (!empty($password)) {
            $hashed_password = $password;
            $update_sql = "UPDATE users SET nama_lengkap=?, email=?, id_jabatan=?, id_unit_kerja=?, hak_akses=?, status_pengguna=?, password=? WHERE id_user=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssiiissi", $nama_lengkap, $email, $id_jabatan, $id_unit_kerja, $hak_akses, $status_pengguna, $hashed_password, $id_user);
        } else {
            $update_sql = "UPDATE users SET nama_lengkap=?, email=?, id_jabatan=?, id_unit_kerja=?, hak_akses=?, status_pengguna=? WHERE id_user=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssiiisi", $nama_lengkap, $email, $id_jabatan, $id_unit_kerja, $hak_akses, $status_pengguna, $id_user);
        }

        if ($stmt->execute()) {
            header("Location: pengguna.php?pesan=sukses");
            exit();
        } else {
            $error = "Gagal memperbarui data: " . $conn->error;
        }
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
    <div class="container mt-4">
        <h2>Edit Pengguna</h2>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
            </div>

            <div class="mb-3">
                <label for="id_unit_kerja" class="form-label">Unit Kerja</label>
                <select class="form-control" id="id_unit_kerja" name="id_unit_kerja" required>
                    <option value="">Pilih Unit Kerja</option>
                    <?php while ($unit = $unit_query->fetch_assoc()) { ?>
                        <option value="<?= $unit['id_unit'] ?>" <?= $unit['id_unit'] == $user['id_unit_kerja'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($unit['nama_unit']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="hak_akses" class="form-label">Hak Akses</label>
                <select class="form-control" id="hak_akses" name="hak_akses" required>
                    <option value="1" <?= $user['hak_akses'] == 1 ? 'selected' : '' ?>>Super admin</option>
                    <option value="2" <?= $user['hak_akses'] == 2 ? 'selected' : '' ?>>Admin Unit</option>
                    <option value="3" <?= $user['hak_akses'] == 3 ? 'selected' : '' ?>>Viewer (All)</option>
                    <option value="4" <?= $user['hak_akses'] == 4 ? 'selected' : '' ?>>Viewer (Per Unit)</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="status_pengguna" class="form-label">Status</label>
                <select class="form-control" id="status_pengguna" name="status_pengguna" required>
                    <option value="Aktif" <?= $user['status_pengguna'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Nonaktif" <?= $user['status_pengguna'] == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success" onclick="return confirm('Anda yakin ingin menyimpan perubahan?')">Simpan Perubahan</button>
            <a href="pengguna.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</html>
<?php
$conn->close();
?>

