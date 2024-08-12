<?php
require_once '../koneksi.php';

if (isset($_POST['submit'])) {
    $id = $_GET['id'];
    $nama = trim($_POST['nama']);

    if (empty($nama)) {
        echo "<script>alert('Nama kelas harus diisi'); history.back();</script>";
        exit;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE kelas SET nama_kelas = ? WHERE id_kelas = ?");
    $stmt->bind_param("si", $nama, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data kelas berhasil diubah'); window.location.href='form.php'</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat mengubah data'); history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: form.php');
}
?>
