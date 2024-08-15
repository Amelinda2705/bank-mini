<?php
session_start();
require_once 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data no_rekening dari tabel akun_nasabah berdasarkan user_id
$user_id = $_SESSION['user_id'];

$sql_rekening = "SELECT no_rekening, nama FROM akun_nasabah WHERE id_nasabah = :user_id";
$stmt_rekening = $pdo->prepare($sql_rekening);
$stmt_rekening->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_rekening->execute();
$akun = $stmt_rekening->fetch(PDO::FETCH_ASSOC);

if (!$akun) {
    echo "Nomor rekening tidak ditemukan!";
    exit;
}

// Ambil riwayat transaksi pengguna berdasarkan no_rekening
$no_rekening = $akun['no_rekening'];

$sql_transaksi = "SELECT an.no_rekening, an.nama, rt.jenis_transaksi, rt.jumlah, rt.tanggal 
                  FROM riwayat_transaksi rt
                  JOIN akun_nasabah an ON rt.id_nasabah = an.id_nasabah
                  WHERE an.no_rekening = :no_rekening
                  ORDER BY rt.tanggal DESC";
$stmt_transaksi = $pdo->prepare($sql_transaksi);
$stmt_transaksi->bindParam(':no_rekening', $no_rekening, PDO::PARAM_STR);
$stmt_transaksi->execute();
$transaksi = $stmt_transaksi->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="style.php" media="screen">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <!-- Navbar -->
    <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
            <a class="nav-link active" href="transaksi_user.php">Riwayat Transaksi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="user.php">Profile</a>
        </li>
    </ul>

    <!-- Riwayat Transaksi Content -->
    <div class="mt-5">
        <h2 class="mb-4">Riwayat Transaksi</h2>
        <?php if (count($transaksi) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">No Rek</th>
                        <th scope="col">Nama Nasabah</th>
                        <th scope="col">Jenis Transaksi</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksi as $t): ?>
                        <tr>
                            <td><?= htmlspecialchars($t['no_rekening']) ?></td>
                            <td><?= htmlspecialchars($t['nama']) ?></td>
                            <td><?= htmlspecialchars($t['jenis_transaksi']) ?></td>
                            <td>Rp <?= number_format($t['jumlah'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($t['tanggal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada riwayat transaksi.</p>
        <?php endif; ?>
    </div>

    <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
