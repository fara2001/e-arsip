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
    <div class="container mt-3">
        <h2>Referensi Unit Kerja</h2>
        <a href="tambah-unit-kerja.php" class="btn btn-warning mb-3" style="background-color: #ffa726; border-color: #ffa726;">Tambah Unit Kerja</a>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="keyword" placeholder="Cari unit kerja" aria-label="Cari unit kerja" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Cari</button>
            </div>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Nama Unit</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $keyword = "";
                if (isset($_GET['keyword'])) {
                    $keyword = $_GET['keyword'];
                }

                $sql = "SELECT * FROM unit_kerja WHERE nama_unit LIKE '%".$keyword."%' OR keterangan LIKE '%".$keyword."%'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $nomor = 1;
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $nomor . "</td>";
                        echo "<td>" . $row['nama_unit'] . "</td>";
                        echo "<td>" . $row['keterangan'] . "</td>";
                        echo "<td>
                            <a href='edit-unit-kerja.php?id=" . $row['id_unit'] . "' class='btn btn-warning' style='background-color: #ffa726; border-color: #ffa726;'>Edit</a>"?>
                            <a href="javascript:void(0)" class="btn btn-danger" style="background-color: #ff7043; border-color: #ff7043;" onclick="if(confirm('Anda yakin ingin menghapus unit kerja ini?')){location.href='hapus-unit-kerja.php?id=<?php echo $row['id_unit']; ?>'}">Hapus</a>
                        </td>
                        <?php
                        echo "</tr>";
                        $nomor++;
                    }
                } else {
                    echo "<tr><td colspan='4'>0 results</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
