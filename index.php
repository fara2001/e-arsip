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
        <h2 class="text-center">E-Arsip</h2>
        <form action="" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" id="keyword" name="keyword" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>" placeholder="Cari berdasarkan No Dokumen, Nama Dokumen, Unit Kerja, Status, atau Jenis File">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
            <?php
            $user_query = "SELECT * FROM users WHERE email = '".$_SESSION['email']."'";
            $user_result = $conn->query($user_query);
            $user = $user_result->fetch_assoc();
            $unit_kerja_locked = ($user['hak_akses'] != 1 || $user['hak_akses'] != 3);
            ?>

            <div class="mt-3">
                <div class="d-flex flex-column flex-md-row justify-content-between">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <!-- Dropdown Filter Unit Kerja -->
                        <select class="form-select" name="filter_unit_kerja" onchange="this.form.submit()" <?php echo $unit_kerja_locked ? '' : ''; ?>>
                            <option value="">Pilih Unit Kerja</option>
                            <?php
                            // Menampilkan filter berdasarkan unit kerja
                            if ($user['hak_akses'] == 1 || $user['hak_akses'] == 3) {
                                $unit_kerja_query = "SELECT * FROM unit_kerja";
                            } else {
                                $unit_kerja_query = "SELECT * FROM unit_kerja WHERE id_unit = " . $user['id_unit_kerja'];
                            }
                            $unit_kerja_result = $conn->query($unit_kerja_query);
                            while ($unit = $unit_kerja_result->fetch_assoc()) {
                                $selected = (isset($_GET['filter_unit_kerja']) && $_GET['filter_unit_kerja'] == $unit['id_unit']) ? 'selected' : '';
                                echo "<option value='" . $unit['id_unit'] . "' $selected>" . $unit['nama_unit'] . "</option>";
                            }
                            ?>
                        </select>
<?php
// var_dump($user);
?>
                        <!-- Dropdown Filter Jenis File -->
                        <select class="form-select" name="filter_jenis_file" onchange="this.form.submit()">
                            <option value="">Pilih Jenis File</option>
                            <?php
                            // Menampilkan filter berdasarkan jenis file
                            $jenis_file_query = "SELECT * FROM jenis_file";
                            if ($user['hak_akses'] != 1 && $user['hak_akses'] != 3) {
                                $jenis_file_query .= " WHERE id_jenis_file IN (SELECT id_jenis_file FROM dokumen WHERE unit_kerja = '" . $user['id_unit_kerja'] . "')";
                            }
                            $jenis_file_result = $conn->query($jenis_file_query);
                            while ($jenis = $jenis_file_result->fetch_assoc()) {
                                $selected = (isset($_GET['filter_jenis_file']) && $_GET['filter_jenis_file'] == $jenis['id_jenis_file']) ? 'selected' : '';
                                echo "<option value='" . $jenis['id_jenis_file'] . "' $selected>" . $jenis['nama_jenis_file'] . "</option>";
                            }
                            ?>
                        </select>

                        <!-- Dropdown Filter Status -->
                        <select class="form-select" name="filter_status" onchange="this.form.submit()">
                            <option value="">Pilih Status</option>
                            <?php
                            // Menampilkan filter berdasarkan status
                            $status_query = "SELECT * FROM status";
                            if ($user['hak_akses'] != 1 && $user['hak_akses'] != 3) {
                                $status_query .= " WHERE id_status IN (SELECT id_status FROM dokumen WHERE unit_kerja = '" . $user['id_unit_kerja'] . "')";
                            }
                            $status_result = $conn->query($status_query);
                            while ($status = $status_result->fetch_assoc()) {
                                $selected = (isset($_GET['filter_status']) && $_GET['filter_status'] == $status['id_status']) ? 'selected' : '';
                                echo "<option value='" . $status['id_status'] . "' $selected>" . $status['nama_status'] . "</option>";
                            }
                            ?>
                        </select>

                        <!-- Dropdown untuk Mengurutkan berdasarkan kolom -->
                        <select class="form-select" name="sort_by" onchange="this.form.submit()">
                            <option value="">Urutkan berdasarkan</option>
                            <option value="no_dokumen" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'no_dokumen') ? 'selected' : ''; ?>>No Dokumen</option>
                            <option value="nama_dokumen" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'nama_dokumen') ? 'selected' : ''; ?>>Nama Dokumen</option>
                            <option value="status" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'status') ? 'selected' : ''; ?>>Status</option>
                        </select>

                        <!-- Tombol Ascending dan Descending -->
                        <div class="btn-group">
                            <button type="submit" name="sort_order" value="ASC" class="btn btn-outline-primary">Ascending</button>
                            <button type="submit" name="sort_order" value="DESC" class="btn btn-outline-danger">Descending</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2 mt-md-0" onclick="window.location.href='index.php'">Reset</button>
                </div>
            </div>
        </form>
        <?php
        // Mulai query utama
        $sql = "SELECT d.id_dokumen, d.no_dokumen, d.nama_dokumen, d.unit_kerja, d.status, d.file, d.jenis_file, 
       u.nama_unit, j.nama_jenis_file, s.nama_status, s.warna FROM dokumen d
LEFT JOIN unit_kerja u ON d.unit_kerja = u.id_unit
LEFT JOIN jenis_file j ON d.jenis_file = j.id_jenis_file
LEFT JOIN status s ON d.status = s.id_status";

        // Membuat array untuk menyimpan kondisi WHERE
        $whereClauses = [];

        // Penerapan filter unit kerja jika bukan super admin
        if ($user['hak_akses'] != 1 && $user['hak_akses'] != 3) {
            $whereClauses[] = "d.unit_kerja = '" . $user['id_unit_kerja'] . "'";
        }

        // Penerapan filter unit kerja
        if (isset($_GET['filter_unit_kerja']) && $_GET['filter_unit_kerja'] != '') {
            $filter_unit_kerja = $_GET['filter_unit_kerja'];
            $whereClauses[] = "d.unit_kerja = '$filter_unit_kerja'";
        }

        // Penerapan filter jenis file
        if (isset($_GET['filter_jenis_file']) && $_GET['filter_jenis_file'] != '') {
            $filter_jenis_file = $_GET['filter_jenis_file'];
            $whereClauses[] = "d.jenis_file = '$filter_jenis_file'";
        }

        // Penerapan filter status
        if (isset($_GET['filter_status']) && $_GET['filter_status'] != '') {
            $filter_status = $_GET['filter_status'];
            $whereClauses[] = "d.status = '$filter_status'";
        }

        // Pencarian berdasarkan kata kunci
        if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
            $keyword = $_GET['keyword'];
            $whereClauses[] = "(d.no_dokumen LIKE '%$keyword%' OR d.nama_dokumen LIKE '%$keyword%' OR u.nama_unit LIKE '%$keyword%' OR s.nama_status LIKE '%$keyword%' OR j.nama_jenis_file LIKE '%$keyword%')";
        }

        // Jika ada kondisi WHERE, gabungkan dengan 'AND' untuk memastikan query valid
        if (count($whereClauses) > 0) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        // Penerapan urutan
        if (isset($_GET['sort_by']) && $_GET['sort_by'] != '') {
            $sort_by = $_GET['sort_by'];
            $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC'; // Default ascending order
            $sql .= " ORDER BY $sort_by $sort_order";
        }

        // Pagination
        $result = $conn->query($sql);
        $total_rows = $result->num_rows;
        $per_page = 10;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $per_page;
        $sql .= " LIMIT $per_page OFFSET $offset";
        $result = $conn->query($sql);
        ?>
        <?php if ($user['hak_akses'] == 1 || $user['hak_akses'] == 2) { ?>
            <a href="tambah-dokumen.php" class="btn btn-warning mb-3">Tambah Dokumen</a>
        <?php } ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-rounded">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>No Dokumen</th>
                        <th>Nama Dokumen</th>
                        <th>Unit Kerja</th>
                        <th>Status</th>
                        <th>File</th>
                        <th>Jenis File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $offset + 1;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no . "</td>";
                            echo "<td>" . htmlspecialchars($row['no_dokumen'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_dokumen'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_unit'] ?? '') . "</td>";
                            echo "<td><button class='btn' style='color: white ;background-color:" . htmlspecialchars($row['warna'] ?? '#000') . ";'>" . htmlspecialchars($row['nama_status'] ?? '') . "</button></td>"; // Mengatur warna teks berdasarkan kolom 'warna'
                            echo "<td><a target='_blank' href='" . htmlspecialchars($row['file'] ?? '') . "' class='btn btn-success'>Unduh</a></td>";
                            echo "<td>" . htmlspecialchars($row['nama_jenis_file'] ?? '') . "</td>";
                            echo "<td>
                                    <a href='detail-dokumen.php?id=" . $row['id_dokumen'] . "' class='btn btn-info'>Detail</a>
                                    
                                    ";
                                    ?>
                                    <?php if ($user['hak_akses'] != 3 && $user['hak_akses'] != 4) { ?>
                                        <a href="edit-dokumen.php?id=<?php echo $row['id_dokumen'] ?>" class='btn btn-warning'>Edit</a>
                                    <a href="hapus-dokumen.php?id=<?php echo $row['id_dokumen']; ?>" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus dokumen ini?')">Hapus</a>
                                    <?php } ?>
                            <?php
                                    echo "</td>";
                            echo "</tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>Tidak ada data.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php
                $total_pages = ceil($total_rows / $per_page);
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = ($page == $i) ? 'active' : '';
                    echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>" . $i . "</a></li>";
                }
                ?>
            </ul>
        </nav>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

