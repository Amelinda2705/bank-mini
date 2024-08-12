<?php
require_once '../koneksi.php';

if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama_jurusan']);

    if (empty($nama)) {
        echo "<script>alert('Nama Jurusan harus diisi'); history.back();</script>";
        exit;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO jurusan (nama_jurusan) VALUES (?)");
    $stmt->bind_param("s", $nama);

    if ($stmt->execute()) {
        echo "<script>alert('Data jurusan berhasil ditambahkan'); window.location.href='form.php'</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan data'); history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: form.php');
}
?>
