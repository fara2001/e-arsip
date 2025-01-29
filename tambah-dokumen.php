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
    <div class="container mt-3 form-container">
        <h2>Tambah Dokumen</h2>
        <form action="proses-tambah-dokumen.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="no_dokumen" class="form-label">No Dokumen</label>
                <input type="text" class="form-control" id="no_dokumen" name="no_dokumen" required>
            </div>
            <div class="mb-3">
                <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
                <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" required>
            </div>
            <!-- <div class="mb-3">
                <label for="unit_kerja" class="form-label">Unit Kerja</label>
                <input type="text" class="form-control" id="unit_kerja" name="unit_kerja" required>
            </div> -->
            <?php
            // Query untuk mengambil data dari tabel users
            $query_users = "SELECT * FROM users WHERE email = '" . $_SESSION['email'] . "'";
            $result_users = $conn->query($query_users);
            $user = $result_users->fetch_assoc();
            ?>
            <div class="mb-3">
                <label for="unit_kerja" class="form-label">Unit Kerja</label>
                <select class="form-control" id="unit_kerja" name="unit_kerja" required <?php echo ($user['hak_akses'] != 1) ? '' : ''; ?>>
                    <!-- <option value="">Pilih Unit Kerja</option> -->
                    <?php
                    // Query untuk mengambil data dari tabel unit_kerja
                    $sql = "SELECT uk.id_unit, uk.nama_unit 
                            FROM unit_kerja uk 
                            INNER JOIN users u ON uk.id_unit = u.id_unit_kerja 
                            WHERE u.email = '" . $_SESSION['email'] . "'";
                    $result = $conn->query($sql);

                    // Cek apakah ada data
                    if ($result->num_rows > 0) {
                        // Loop untuk menampilkan data sebagai opsi dropdown
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($user['id_unit_kerja'] == $row['id_unit']) ? 'selected' : '';
                            echo '<option value="' . $row['id_unit'] . '" ' . $selected . '>' . $row['nama_unit'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Tidak ada data</option>';
                    }
                    ?>
                </select>
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
                            echo '<option value="' . $row['id_status'] . '">' . $row['nama_status'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Tidak ada data</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">File</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <?php
            // Sertakan koneksi database
            include 'koneksi.php';
            ?>

            <div class="mb-3">
                <label for="jenis_file" class="form-label">Jenis File</label>
                <select class="form-control" id="jenis_file" name="jenis_file" required>
                    <option value="">Pilih Jenis File</option>
                    <?php
                    // Query untuk mengambil data dari tabel jenis_file
                    $sql = "SELECT id_jenis_file, nama_jenis_file FROM jenis_file";
                    $result = $conn->query($sql);

                    // Cek apakah ada data
                    if ($result->num_rows > 0) {
                        // Loop untuk menampilkan data sebagai opsi dropdown
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id_jenis_file'] . '">' . $row['nama_jenis_file'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Tidak ada data</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <!-- <label for="nama_pengunggah_dokumen" class="form-label">Nama Pengunggah Dokumen</label> -->
                <?php
                $sql = "SELECT id_user FROM users WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $_SESSION['email']);
                $stmt->execute();
                $result = $stmt->get_result();
                $id_user = $result->fetch_assoc()['id_user'];
                ?>
                <input type="hidden" class="form-control" id="nama_pengunggah_dokumen" name="nama_pengunggah_dokumen" value="<?php echo $id_user; ?>" required>
            </div>
            <button type="submit" class="btn btn-warning" style="background-color: #ffa726; border-color: #ffa726;">Tambah Dokumen</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>