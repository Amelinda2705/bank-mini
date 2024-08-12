<?php

require_once 'koneksi.php';

$nama = $_POST['nama'];
$kelas = $_POST['id_kelas'];
$jurusan = $_POST['id_jurusan'];
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
if (empty($tanggal_pembuatan)) {
    echo "<script>alert('Tanggal pembuatan harus diisi'); history.back();</script>";
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
if (isset($_POST['submit'])) {
    $q = $conn->query("INSERT INTO akun_nasabah (nama, id_kelas, id_jurusan, jenis_kelamin, tanggal_pembuatan, saldo, status) VALUES ('$nama', '$kelas', '$jurusan', '$jenis_kelamin', '$tanggal_pembuatan', '$saldo', '$status')");
    if ($q) {
        echo "<script>alert('Data nasabah berhasil ditambahkan'); window.location.href='form_nasabah.php'</script>";
    }
} else {
    header('Location: index.php');
}
