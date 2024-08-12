<?php
require_once '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Check if there are any akun_nasabah records associated with this kelas
    $sql_check = "SELECT COUNT(*) FROM akun_nasabah WHERE id_kelas = ?";
    $statement_check = $pdo->prepare($sql_check);
    $statement_check->execute([$id]);
    $count = $statement_check->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('Tidak dapat menghapus kelas karena ada akun nasabah terkait'); window.location.href='form.php';</script>";
    } else {
        $sql_delete = "DELETE FROM kelas WHERE id_kelas = ?";
        $statement_delete = $pdo->prepare($sql_delete);
        $statement_delete->execute([$id]);

        if ($statement_delete->rowCount() > 0) {
            echo "<script>alert('Data berhasil dihapus'); window.location.href='form.php';</script>";
        } else {
            echo "<script>alert('Data tidak berhasil dihapus'); window.location.href='form.php';</script>";
        }
    }
} else {
    header('Location: form.php');
}
