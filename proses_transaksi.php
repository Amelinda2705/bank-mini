<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_rekening = $_POST['no_rekening'];
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $jumlah = $_POST['jumlah'];

    $sql = "SELECT * FROM akun_nasabah WHERE no_rekening = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$no_rekening]);
    $nasabah = $statement->fetch(PDO::FETCH_ASSOC);

    if ($nasabah) {
        date_default_timezone_set('Asia/Jakarta');
        $id_nasabah = $nasabah['id_nasabah'];
        $saldo = $nasabah['saldo'];
        $tanggal = date('Y-m-d H:i:s');

        if ($jenis_transaksi == 'setor') {
            $saldo_baru = $saldo + $jumlah;
        } elseif ($jenis_transaksi == 'tarik') {
                $saldo_baru = $saldo - $jumlah;
        }

        $sql_update = "UPDATE akun_nasabah SET saldo = ? WHERE no_rekening = ?";
        $statement_update = $pdo->prepare($sql_update);
        $statement_update->execute([$saldo_baru, $no_rekening]);

        $sql_insert = "INSERT INTO riwayat_transaksi (id_nasabah, nama, jenis_transaksi, jumlah, tanggal, saldo_setelah) VALUES (?, ?, ?, ?, ?, ?)";
        $statement_insert = $pdo->prepare($sql_insert);
        $statement_insert->execute([$id_nasabah, $nasabah['nama'], $jenis_transaksi, $jumlah, $tanggal, $saldo_baru]);

        echo "<script>alert('Data nasabah berhasil ditambahkan'); window.location.href='form_transaksi.php';</script></script>";
        exit();
    } else {
        echo "<script>alert('Nomor Rekening tidak ditemukan'); window.location.href='form_transaksi.php'; history.back();</script>";
        exit();
    }
}
