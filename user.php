<?php
session_start();
require_once 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data pengguna dari database berdasarkan session user_id
$user_id = $_SESSION['user_id'];

$sql = "SELECT 
            an.nama, 
            an.no_rekening, 
            an.saldo, 
            an.jenis_kelamin, 
            an.no_rekening AS password, 
            k.nama_kelas, 
            j.nama_jurusan
        FROM akun_nasabah an
        LEFT JOIN kelas k ON an.id_kelas = k.id_kelas
        LEFT JOIN jurusan j ON an.id_jurusan = j.id_jurusan
        WHERE an.id_nasabah = :user_id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Data pengguna tidak ditemukan!";
    exit;
}

// Ambil riwayat transaksi pengguna
$sql_transaksi = "SELECT * FROM riwayat_transaksi WHERE id_nasabah = :user_id ORDER BY tanggal DESC";
$stmt_transaksi = $pdo->prepare($sql_transaksi);
$stmt_transaksi->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_transaksi->execute();
$transaksi = $stmt_transaksi->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pengguna</title>
    <link rel="stylesheet" href="style.php" media="screen">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <!-- Navbar -->
    
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="transaksi_user.php">Riwayat Transaksi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="user.php">Profile</a>
            </li>
        </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-5">
        <!-- Profile Tab -->
        <div class="tab-pane fade show active" id="profile">
            <h2 class="mb-4">Selamat datang, <?= htmlspecialchars($user['nama']) ?></h2>
            <ul class="list-group">
                <li class="list-group-item"><strong>Nama:</strong> <?= htmlspecialchars($user['nama']) ?></li>
                <li class="list-group-item"><strong>No Rekening:</strong> <?= htmlspecialchars($user['no_rekening']) ?></li>
                <li class="list-group-item"><strong>Saldo:</strong> Rp <?= number_format($user['saldo'], 0, ',', '.') ?></li>
                <li class="list-group-item"><strong>Jenis Kelamin:</strong> <?= htmlspecialchars($user['jenis_kelamin']) ?></li>
                <li class="list-group-item"><strong>Password:</strong> <?= htmlspecialchars($user['password']) ?></li>
                <li class="list-group-item"><strong>Kelas:</strong> <?= htmlspecialchars($user['nama_kelas']) ?></li>
                <li class="list-group-item"><strong>Jurusan:</strong> <?= htmlspecialchars($user['nama_jurusan']) ?></li>
            </ul>
        </div>

        <!-- Riwayat Transaksi Tab -->
        <div class="tab-pane fade" id="transaksi">
            <h2 class="mb-4">Riwayat Transaksi</h2>
            <?php if (count($transaksi) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Tipe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksi as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['tanggal']) ?></td>
                                <td><?= htmlspecialchars($item['deskripsi']) ?></td>
                                <td>Rp <?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($item['tipe']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Tidak ada riwayat transaksi.</p>
            <?php endif; ?>
        </div>
    </div>

    <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
