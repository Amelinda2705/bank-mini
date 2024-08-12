<?php
require_once 'koneksi.php';

$id = $_GET['id'];

try {
    $sql = "DELETE FROM akun_nasabah WHERE id_nasabah=?";
    $statement = $pdo->prepare($sql);
    $executionResult = $statement->execute([$id]);

    if ($executionResult) {
        echo "<script>
                alert('Data berhasil dihapus');
                window.location.href='form_nasabah.php#data_nasabah';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal dihapus');
                window.location.href='form_nasabah.php#data_nasabah';
              </script>";
    }
} catch (Exception $e) {
    echo "<script>
            alert('Data gagal dihapus: " . $e->getMessage() . "');
            window.location.href='form_nasabah.php';
          </script>";
}
