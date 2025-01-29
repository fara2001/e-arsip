<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
}
$id_unit = $_GET['id'];
$sql = "SELECT * FROM unit_kerja WHERE id_unit = '$id_unit'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $unit = $result->fetch_assoc();
} else {
    header("Location: unit_kerja.php");
}
if (isset($_POST['nama_unit'])) {
    $nama_unit = $_POST['nama_unit'];
    $keterangan = $_POST['keterangan'];
    $sql_update = "UPDATE unit_kerja SET nama_unit = '$nama_unit', keterangan = '$keterangan' WHERE id_unit = '$id_unit'";
    if ($conn->query($sql_update) === TRUE) {
        header("Location: unit_kerja.php");
        exit();
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
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
        <h2>Edit Unit Kerja</h2>
        <form action="proses-edit-unit-kerja.php" method="POST">
            <input type="hidden" name="id_unit" value="<?php echo $unit['id_unit']; ?>">
            <div class="mb-3">
                <label for="nama_unit" class="form-label">Nama Unit</label>
                <input type="text" class="form-control" id="nama_unit" name="nama_unit" value="<?php echo $unit['nama_unit']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?php echo $unit['keterangan']; ?>" required>
            </div>
            <button type="submit" name="submit" onclick="return confirm('Apakah Anda yakin ingin mengedit unit kerja?')" class="btn btn-warning" style="background-color: #ffa726; border-color: #ffa726;">Edit Unit Kerja</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
