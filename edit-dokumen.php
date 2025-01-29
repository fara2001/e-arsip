<?php
session_start();
include "koneksi.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Mendapatkan ID dokumen dari parameter URL
if (isset($_GET['id'])) {
    $id_dokumen = $_GET['id'];
    
    // Ambil data dokumen dari database
    $sql = "SELECT * FROM dokumen WHERE id_dokumen = '$id_dokumen'";
    $result = $conn->query($sql);
    
    // Jika dokumen ditemukan
    if ($result->num_rows > 0) {
        $dokumen = $result->fetch_assoc();
    } else {
        // Jika dokumen tidak ditemukan
        echo "Dokumen tidak ditemukan.";
        exit();
    }
} else {
    echo "ID dokumen tidak valid.";
    exit();
}

// Proses pembaruan data dokumen
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_dokumen = $_POST['no_dokumen'];
    $nama_dokumen = $_POST['nama_dokumen'];
    $status = $_POST['status'];
    $unit_kerja = $_POST['unit_kerja'];
    $jenis_file = $_POST['jenis_file'];
    $file_lama = $_POST['file_lama']; // Menyimpan nama file lama

    // Jika ada file baru, proses upload
    if ($_FILES['file']['name'] != '') {
        // Upload file baru
        $target_dir = "uploads/";
        $file_name = date('Y-m-d-H-i-s') . '-' . rand(1000, 9999) . '-' . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        $upload_ok = 1;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file sudah ada
        if (file_exists($target_file)) {
            echo "File sudah ada.";
            $upload_ok = 0;
        }

        // Cek format file
        if ($file_type != "pdf" && $file_type != "docx" && $file_type != "xlsx") {
            echo "Hanya file PDF, DOCX, dan XLSX yang diperbolehkan.";
            $upload_ok = 0;
        }

        // Cek jika upload sukses
        if ($upload_ok == 0) {
            echo "File tidak dapat diupload.";
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                // Hapus file lama jika ada
                if ($file_lama != '') {
                    unlink($target_dir . $file_lama);
                }
            } else {
                echo "Terjadi kesalahan saat mengupload file.";
                exit();
            }
        }
    } else {
        // Jika tidak ada file baru, gunakan file lama
        $file_name = $file_lama;
    }

    $nama_file_terbaru_final = $target_dir . $file_name;

    // Update data dokumen
    $sql_update = "UPDATE dokumen SET
                    no_dokumen = '$no_dokumen',
                    nama_dokumen = '$nama_dokumen',
                    status = '$status',
                    unit_kerja = '$unit_kerja',
                    jenis_file = '$jenis_file',
                    file = '$nama_file_terbaru_final'
                    WHERE id_dokumen = '$id_dokumen'";

    if ($conn->query($sql_update) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokumen</title>
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
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
</head>
<body>
    <?php include "navbar.php"; ?>
    <div class="container mt-3">
        <h2 class="text-center">Edit Dokumen</h2>
        <form action="edit-dokumen.php?id=<?php echo $id_dokumen; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="file_lama" value="<?php echo $dokumen['file']; ?>">
            <div class="mb-3">
                <label for="no_dokumen" class="form-label">No Dokumen</label>
                <input type="text" class="form-control" id="no_dokumen" name="no_dokumen" value="<?php echo $dokumen['no_dokumen']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
                <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" value="<?php echo $dokumen['nama_dokumen']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="">Pilih Status</option>
                    <?php
                    // Query untuk mengambil data dari tabel status
                    $sql = "SELECT id_status, nama_status FROM status";
                    $result = $conn->query($sql);

                    // Cek apakah ada data
                    if ($result->num_rows > 0) {
                        // Loop untuk menampilkan data sebagai opsi dropdown
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($dokumen['status'] == $row['id_status']) ? 'selected' : '';
                            echo '<option value="' . $row['id_status'] . '" ' . $selected . '>' . $row['nama_status'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Tidak ada data</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="unit_kerja" class="form-label">Unit Kerja</label>
                <select class="form-select" id="unit_kerja" name="unit_kerja" required>
                    <?php
                    // Menampilkan unit kerja
                    $unit_kerja_query = "SELECT * FROM unit_kerja";
                    $unit_kerja_result = $conn->query($unit_kerja_query);
                    while ($unit = $unit_kerja_result->fetch_assoc()) {
                        $selected = ($dokumen['unit_kerja'] == $unit['id_unit']) ? 'selected' : '';
                        echo "<option value='" . $unit['id_unit'] . "' $selected>" . $unit['nama_unit'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jenis_file" class="form-label">Jenis File</label>
                <select class="form-select" id="jenis_file" name="jenis_file" required>
                    <?php
                    // Menampilkan jenis file
                    $jenis_file_query = "SELECT * FROM jenis_file";
                    $jenis_file_result = $conn->query($jenis_file_query);
                    while ($jenis = $jenis_file_result->fetch_assoc()) {
                        $selected = ($dokumen['jenis_file'] == $jenis['id_jenis_file']) ? 'selected' : '';
                        echo "<option value='" . $jenis['id_jenis_file'] . "' $selected>" . $jenis['nama_jenis_file'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">File</label>
                <input type="file" class="form-control" id="file" name="file">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti file.</small>
                <p>File Lama: <a href="<?php echo $dokumen['file']; ?>" target="_blank"><?php echo $dokumen['file']; ?></a></p>
            </div>
            <div class="d-flex justify-content-between">
                <button name="submit" onclick="" type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-danger">Batal</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
