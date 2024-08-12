<?php
require_once '../koneksi.php';

if (isset($_POST['submit'])) {
    $id = $_GET['id'];
    $nama = trim($_POST['nama']);

    if (empty($nama)) {
        echo "<script>alert('Nama Jurusan harus diisi'); history.back();</script>";
        exit;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE jurusan SET nama_jurusan = ? WHERE id_jurusan = ?");
    $stmt->bind_param("si", $nama, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data jurusan berhasil diubah'); window.location.href='form.php'</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat mengubah data'); history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: form.php');
}
?>
