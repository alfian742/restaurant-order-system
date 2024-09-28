<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Cek auth
if (!isset($_SESSION['email']) && !isset($_SESSION['level'])) {
    $_SESSION['error'] = "Maaf, Anda harus masuk terlebih dahulu";
    echo "<script>window.location.href = '" . base_url('auth/login.php') . "';</script>";
    exit();
}

// Judul Halaman
$title = 'Detail Pesanan';

ob_start(); // Start output buffering 
?>

<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6"><?= $title; ?></h1>
</div>
<!-- Single Page Header End -->

<!-- Order Page Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="d-flex justify-content-between gap-2 mb-4">
            <a href="<?= base_url('user/order.php'); ?>" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-2"></i> Riwayat Pesanan</a>
        </div>

        <?php
        $id_user = $_SESSION['id_user'];
        $id_pesanan = $_GET['order-id'];

        $sql_keranjang = "SELECT * FROM keranjang INNER JOIN menu 
                          ON keranjang.id_menu=menu.id_menu
                          WHERE keranjang.id_pesanan='$id_pesanan'
                          AND keranjang.id_user='$id_user' 
                          ORDER BY keranjang.id_keranjang ASC";
        $query_keranjang = mysqli_query($koneksi, $sql_keranjang);

        if (mysqli_num_rows($query_keranjang) > 0):
        ?>
            <div class="table-responsive">
                <table class="table table-hover" style="white-space: nowrap !important;">
                    <thead>
                        <tr>
                            <th scope="col">Produk</th>
                            <th scope="col">Nama Menu</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data_keranjang = mysqli_fetch_array($query_keranjang)): ?>
                            <tr>
                                <th scope="row">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= get_image_url(base_url('assets/uploads/menu/' . $data_keranjang['gambar'])); ?>" class="rounded-circle" style="width: 80px; height: 80px;" alt="<?= $data_keranjang['nama_menu']; ?>">
                                    </div>
                                </th>
                                <td>
                                    <p class="mb-0 mt-4"><?= $data_keranjang['nama_menu']; ?></p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4"><?= 'Rp ' . number_format($data_keranjang['harga'], 0, ',', '.'); ?></p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4"><?= $data_keranjang['jumlah']; ?></p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">
                                        <?php
                                        $subtotal = $data_keranjang['harga'] * $data_keranjang['jumlah'];

                                        echo 'Rp ' . number_format($subtotal, 0, ',', '.');
                                        ?>
                                    </p>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            </div>

            <!-- Bagian untuk total belanja -->
            <div class="row g-4 justify-content-end mt-5">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                    <div class="bg-light rounded">
                        <div class="p-4">
                            <h1 class="display-6 mb-4"><span class="fw-normal">Belanja</span></h1>
                            <?php
                            $sql_pesanan = "SELECT * FROM pesanan WHERE id_pesanan='$id_pesanan' AND id_user='$id_user'";
                            $query_pesanan = mysqli_query($koneksi, $sql_pesanan);

                            $data_pesanan = mysqli_fetch_array($query_pesanan);
                            ?>
                            <div class="d-flex justify-content-between mb-4">
                                <!-- Menampilkan total belanja -->
                                <h5 class="mb-0 me-4">Total Belanja:</h5>
                                <p class="mb-0">
                                    <?= 'Rp ' . number_format($data_pesanan['total_pembayaran'], 0, ',', '.'); ?>
                                </p>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <!-- Menampilkan status -->
                                <h6 class="mb-0 me-4">status:</h6>
                                <p class="mb-0">
                                    <?php if ($data_pesanan['status'] == 'Pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php elseif ($data_pesanan['status'] == 'Confirmed'): ?>
                                        <span class="badge bg-success">Confirmed</span>
                                    <?php elseif ($data_pesanan['status'] == 'In Progress'): ?>
                                        <span class="badge bg-info">In Progress</span>
                                    <?php elseif ($data_pesanan['status'] == 'Completed'): ?>
                                        <span class="badge bg-primary">Completed</span>
                                    <?php elseif ($data_pesanan['status'] == 'Cancelled'): ?>
                                        <span class="badge bg-danger">Cancelled</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        else:
            include('components/empty-cart.php');
        endif;
        ?>
    </div>
</div>
<!-- Order Page End -->

<?php
$content = ob_get_clean(); // Get content and clean buffer

// Menyertakan layout
include('layout.php');
?>