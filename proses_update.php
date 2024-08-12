<?php

require_once 'koneksi.php';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_pembuatan = $_POST['tanggal_pembuatan'];
    $saldo = $_POST['saldo'];
    $status = $_POST['status'];

    // id_nasabah bernilai '' karena auto increment

    if (empty($nama)) {
        echo "<script>alert('Nama harus diisi'); history.back();</script>";
        exit;
    }
    if (empty($kelas)) {
        echo "<script>alert('Kelas harus diisi'); history.back();</script>";
        exit;
    }
    if (empty($jurusan)) {
        echo "<script>alert('Jurusan harus diisi'); history.back();</script>";
        exit;
    }
    if (empty($jenis_kelamin)) {
        echo "<script>alert('Jenis kelamin harus diisi'); history.back();</script>";
        exit;
    }
    if (empty($saldo)) {
        echo "<script>alert('Saldo harus diisi'); history.back();</script>";
        exit;
    }
    if ($saldo <= 1) {
        echo "<script>alert('Saldo dilarang di bawah angka 1'); history.back();</script>";
        exit;
    }
    if (empty($status)) {
        echo "<script>alert('Status harus diisi'); history.back();</script>";
        exit;
    }

    $q = $conn->query("UPDATE akun_nasabah SET nama = '$nama', kelas = '$kelas', jurusan = '$jurusan', kelas = '$kelas', jenis_kelamin = '$jenis_kelamin', tanggal_pembuatan = '$tanggal_pembuatan', saldo = '$saldo', status = '$status' WHERE id_nasabah = '$id'");

    if ($q) {
        echo "<script>alert('Data akun nasabah berhasil diubah'); window.location.href='index.php#data_nasabah'</script>";
    }
} else {
    header('Location: index.php');
}
