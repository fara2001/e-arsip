<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
}

// Mendapatkan ID status dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: status.php");
    exit;
}

$id_status = $_GET['id'];

// Query untuk mendapatkan data status berdasarkan ID
$query = "SELECT * FROM status WHERE id_status = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_status);
$stmt->execute();
$result = $stmt->get_result();
$status = $result->fetch_assoc();

if (!$status) {
    header("Location: status.php");
    exit;
}

// Jika tombol simpan ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_status = $_POST['nama_status'];
    $keterangan = $_POST['keterangan'];

    // Query untuk update data
    $update_query = "UPDATE status SET nama_status = ?, keterangan = ? WHERE id_status = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssi", $nama_status, $keterangan, $id_status);

    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Status berhasil diperbarui.";
        header("Location: status.php");
        exit;
    } else {
        $error_message = "Gagal memperbarui status.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Status</title>
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
        <h2>Edit Status</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?= $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="nama_status" class="form-label">Nama Status</label>
                <input type="text" name="nama_status" id="nama_status" class="form-control" value="<?= htmlspecialchars($status['nama_status'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control" value="<?= htmlspecialchars($status['keterangan'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="status.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
