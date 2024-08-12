<?php

require_once '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $q = $conn->query("DELETE FROM jurusan WHERE id_jurusan = '$id'");

    if ($q) {
        echo "<script>alert('Data berhasil dihapus'); window.location.href='form.php';</script>";
    } else {
        echo "<script>alert('Data tidak berhasil dihapus'); window.location.href='form.php';</script>";
    }
} else {
    header('Location: form.php');
}
