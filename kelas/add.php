<?php
require_once '../koneksi.php';

if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama_kelas']);

    if (empty($nama)) {
        echo "<script>alert('Nama kelas harus diisi'); history.back();</script>";
        exit;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO kelas (nama_kelas) VALUES (?)");
    $stmt->bind_param("s", $nama);

    if ($stmt->execute()) {
        echo "<script>alert('Data kelas berhasil ditambahkan'); window.location.href='form.php'</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan data'); history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: form.php');
}
?>
