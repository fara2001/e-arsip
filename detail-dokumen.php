<?php
session_start();
include "koneksi.php";
require_once 'libs/phpqrcode/qrlib.php'; // Library QR Code

// Pastikan pengguna sudah login
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Ambil ID dokumen dari parameter URL
$id_dokumen = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mendapatkan data dokumen
$sql = "SELECT dokumen.*, unit_kerja.nama_unit, jenis_file.nama_jenis_file, status.nama_status, users.nama_lengkap AS pengunggah
        FROM dokumen
        LEFT JOIN unit_kerja ON dokumen.unit_kerja = unit_kerja.id_unit
        LEFT JOIN jenis_file ON dokumen.jenis_file = jenis_file.id_jenis_file
        LEFT JOIN status ON dokumen.status = status.id_status
        LEFT JOIN users ON dokumen.nama_pengunggah_dokumen = users.id_user
        WHERE dokumen.id_dokumen = $id_dokumen";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $dokumen = $result->fetch_assoc();

    // URL untuk dokumen
    $url_berkas = "http://192.168.100.67/e-arsip/" . htmlspecialchars($dokumen['file']);

    // Generate QR Code
    $qr_file_path = "qrcodes/qr_$id_dokumen.png";
    if (!file_exists($qr_file_path)) {
        QRcode::png($url_berkas, $qr_file_path, QR_ECLEVEL_L, 10);
    }
} else {
    echo "<p>Dokumen tidak ditemukan.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Dokumen</title>
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
            margin-top: 20px;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }

        .btn-share {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-share:hover {
            background-color: #218838;
        }

        .download-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .download-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="container">
        <h1>Detail Dokumen</h1>
        <table class="table table-bordered mt-3">
            <tr>
                <th>No. Dokumen</th>
                <td><?= htmlspecialchars($dokumen['no_dokumen']) ?></td>
            </tr>
            <tr>
                <th>Nama Dokumen</th>
                <td><?= htmlspecialchars($dokumen['nama_dokumen']) ?></td>
            </tr>
            <tr>
                <th>Unit Kerja</th>
                <td><?= htmlspecialchars($dokumen['nama_unit']) ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= htmlspecialchars($dokumen['nama_status']) ?></td>
            </tr>
            <tr>
                <th>Jenis File</th>
                <td><?= htmlspecialchars($dokumen['nama_jenis_file']) ?></td>
            </tr>
            <tr>
                <th>Pengunggah</th>
                <td><?= htmlspecialchars($dokumen['pengunggah']) ?></td>
            </tr>
            <tr>
                <th>Tanggal Upload</th>
                <td><?= htmlspecialchars($dokumen['tanggal_upload_dokumen']) ?></td>
            </tr>
        </table>
        <a target="_blank" class="download-link" href="<?= htmlspecialchars($dokumen['file']) ?>">Unduh Dokumen</a>
        <button class="btn btn-share mt-3" data-bs-toggle="modal" data-bs-target="#shareModal">Bagikan</button>
    </div>

    <!-- Modal Bagikan -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Bagikan Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h6>Scan QR Code:</h6>
                    <img src="<?= $qr_file_path ?>" alt="QR Code" class="img-fluid mb-3">
                    <p>Atau salin URL berikut:</p>
                    <input type="text" class="form-control" value="<?= $url_berkas ?>" readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>
