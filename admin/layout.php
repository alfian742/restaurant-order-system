<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Pan & Co. | <?= $title; ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/uploads/static/logo-square.jpg'); ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/uploads/static/logo-square.jpg'); ?>">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= base_url('assets/admin/vendor/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/admin/vendor/bootstrap-icons/bootstrap-icons.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/admin/vendor/quill/quill.snow.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/admin/vendor/quill/quill.bubble.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/admin/vendor/simple-datatables/style.css'); ?>" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?= base_url('assets/admin/css/style.css'); ?>" rel="stylesheet">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="<?= base_url('admin'); ?>" class="logo d-flex align-items-center">
                <img src="<?= base_url('assets/uploads/static/logo.png'); ?>" alt="Logo" height="40" class="brand-img d-block mx-auto">
                <!-- <span class="d-none d-lg-block">Pan & Co.</span> -->
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown">
                    <?php
                    $sql_jumlah_item = "SELECT * FROM pesanan WHERE status='Pending'";
                    $query_jumlah_item = mysqli_query($koneksi, $sql_jumlah_item);

                    $jumlah_item = mysqli_num_rows($query_jumlah_item) ?? 0;
                    ?>
                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-primary badge-number"><?= $jumlah_item; ?></span>
                    </a><!-- End Notification Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <?php if ($jumlah_item > 0): ?>
                            <li class="dropdown-header">
                                Ada <?= $jumlah_item ?> pesanan baru
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        <?php else: ?>
                            <li class="dropdown-header">
                                Tidak ada notifikasi
                            </li>
                        <?php endif ?>

                        <?php
                        function waktuYangLalu($tanggal)
                        {
                            $waktuSekarang = time();
                            $waktuPesanan = strtotime($tanggal);
                            $selisihDetik = $waktuSekarang - $waktuPesanan;

                            if ($selisihDetik < 60) {
                                return $selisihDetik . ' detik yang lalu';
                            } elseif ($selisihDetik < 3600) {
                                $menit = floor($selisihDetik / 60);
                                return $menit . ' menit yang lalu';
                            } elseif ($selisihDetik < 86400) {
                                $jam = floor($selisihDetik / 3600);
                                return $jam . ' jam yang lalu';
                            } elseif ($selisihDetik < 604800) {
                                $hari = floor($selisihDetik / 86400);
                                return $hari . ' hari yang lalu';
                            } elseif ($selisihDetik < 2419200) {
                                $minggu = floor($selisihDetik / 604800);
                                return $minggu . ' minggu yang lalu';
                            } elseif ($selisihDetik < 29030400) {
                                $bulan = floor($selisihDetik / 2419200);
                                return $bulan . ' bulan yang lalu';
                            } else {
                                $tahun = floor($selisihDetik / 29030400);
                                return $tahun . ' tahun yang lalu';
                            }
                        }

                        $sql_jumlah_pesanan = "SELECT * FROM pesanan INNER JOIN users
                                               ON pesanan.id_user=users.id_user
                                               WHERE status='Pending'
                                               ORDER BY tanggal ASC LIMIT 4";
                        $query_jumlah_pesanan = mysqli_query($koneksi, $sql_jumlah_pesanan);

                        if (mysqli_num_rows($query_jumlah_pesanan) > 0):
                        ?>

                            <?php while ($data_jumlah_pesanan = mysqli_fetch_array($query_jumlah_pesanan)): ?>
                                <li class="notification-item">
                                    <i class="bi bi-exclamation-circle text-warning"></i>
                                    <a href="<?= base_url('admin/order/detail.php?order-id=' . $data_jumlah_pesanan['id_pesanan']); ?>">
                                        <h4><?= $data_jumlah_pesanan['id_pesanan']; ?></h4>
                                        <p><?= $data_jumlah_pesanan['nama_lengkap']; ?></p>
                                        <p><?= waktuYangLalu($data_jumlah_pesanan['tanggal']); ?></p>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            <?php endwhile ?>

                            <li class="dropdown-footer">
                                <a href="<?= base_url('admin/order/status.php?status=Pending'); ?>">Lihat Semua Pesanan</a>
                            </li>
                        <?php endif ?>

                    </ul><!-- End Notification Dropdown Items -->
                </li><!-- End Notification Nav -->

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-2" href="#" data-bs-toggle="dropdown">
                        <div class="avatar"><?= strtoupper(substr($_SESSION['nama_lengkap'], 0, 1)); ?></div>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?= $_SESSION['nama_lengkap']; ?></h6>
                            <span><?= $_SESSION['level']; ?></span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/manage-user/profile.php'); ?>">
                                <i class="bi bi-person"></i>
                                <span>Profil</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Keluar</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->
    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link <?= ($title === 'Dashboard') ? '' : 'collapsed'; ?>" href="<?= base_url('admin/index.php'); ?>">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($title === 'Pesanan') ? '' : 'collapsed'; ?>" href="<?= base_url('admin/order/show.php'); ?>">
                    <i class="bi bi-journals"></i>
                    <span>Pesanan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($title === 'Menu') ? '' : 'collapsed'; ?>" href="<?= base_url('admin/menu/show.php'); ?>">
                    <i class="bi bi-book"></i>
                    <span>Menu</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($title === 'Galeri') ? '' : 'collapsed'; ?>" href="<?= base_url('admin/gallery/show.php'); ?>">
                    <i class="bi bi-images"></i>
                    <span>Galeri</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($title === 'Laporan') ? '' : 'collapsed'; ?>" href="<?= base_url('admin/report/show.php'); ?>">
                    <i class="bi bi-file-earmark"></i>
                    <span>Laporan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($title === 'Kelola Pengguna') ? '' : 'collapsed'; ?>" href="<?= base_url('admin/manage-user/show.php'); ?>">
                    <i class="bi bi-people"></i>
                    <span>Kelola Pengguna</span>
                </a>
            </li>
        </ul>
    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <!-- content -->
        <?= $content; ?>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="d-flex justify-content-between align-items-center px-5">
            <div class="fw-semibold">
                &copy; <?= date('Y'); ?> Pan & Co. Hak cipta dilindungi.
            </div>
            <div class="fw-semibold me-4">
                Desain oleh <a class="border-bottom text-primary" href="https://www.instagram.com/opi___11/">Novi</a>
            </div>
        </div>
    </footer><!-- End Footer -->

    <!-- Logout modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered border-0">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5" id="logoutModalLabel">Keluar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= $_SESSION['nama_lengkap']; ?>, apakah Anda yakin ingin mengakhiri sesi?
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="<?= base_url('auth/logout.php'); ?>" class="btn btn-primary px-4">Ya</a>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="<?= base_url('assets/admin/vendor/apexcharts/apexcharts.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/vendor/quill/quill.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/vendor/simple-datatables/simple-datatables.js'); ?>"></script>

    <!-- Template Main JS File -->
    <script src="<?= base_url('assets/admin/js/main.js'); ?>"></script>

</body>

</html>