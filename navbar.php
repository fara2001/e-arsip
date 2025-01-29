<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" style="color: black;" href="index.php">E-Arsip</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dokumen.php">Dokumen</a>
                </li>
                <?php
                $sql = "SELECT * FROM users WHERE email='" . $_SESSION['email'] . "'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if ($row['hak_akses'] == 1) {
                ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="referensiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Referensi
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="referensiDropdown">
                                <li><a class="dropdown-item" href="unit_kerja.php">Unit Kerja</a></li>
                                <!-- <li><a class="dropdown-item" href="jabatan.php">Jabatan</a></li> -->
                                <li><a class="dropdown-item" href="status.php">Jenis Status</a></li>
                                <li><a class="dropdown-item" href="jenis_file.php">Jenis File</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pengguna.php">Pengguna</a>
                        </li>
                <?php
                    }
                }
                ?>

            </ul>
            <span class="navbar-text" style="color: black;">
            <?php
                    if (isset($_SESSION['email'])) {
                        $sql = "SELECT u.*, uk.nama_unit AS nama_unit_kerja 
                                FROM users u 
                                INNER JOIN unit_kerja uk ON u.id_unit_kerja = uk.id_unit 
                                WHERE email='" . $_SESSION['email'] . "'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $userName = $row['nama_lengkap'];
                            $userAccess = '';
                            switch ($row['hak_akses']) {
                                case 1:
                                    $userAccess = 'Super admin';
                                    break;
                                case 2:
                                    $userAccess = 'Admin Unit';
                                    break;
                                case 3:
                                    $userAccess = 'Viewer (All)';
                                    break;
                                case 4:
                                    $userAccess = 'Viewer (Per Unit)';
                                    break;
                                default:
                                    $userAccess = 'Unknown';
                            }
                            echo "<span class='navbar-text'>Logged in as: $userName ($userAccess - " . $row['nama_unit_kerja'] . ")</span>";
                        }
                    }
                    ?>
            </span>
            <ul class="navbar-nav me-4 ms-4 mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="white btn btn-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
if (isset($_SESSION['email'])) {
    $sql = "SELECT status_pengguna FROM users WHERE email='" . $_SESSION['email'] . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status_pengguna'] == 'Nonaktif') {
            echo "<script>alert('User tidak aktif, silahkan hubungi administrator');</script>";
            
            header("Location: login.php");
            exit;
        }
    }
}
?>
