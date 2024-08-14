<?php

require_once 'koneksi.php';

$results_per_page = 10;
$params = [];
$conditions = [];

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$starting_limit = ($page - 1) * $results_per_page;

// Dapatkan data jurusan dari database
$sql_jurusan = "SELECT id_jurusan, nama_jurusan FROM jurusan";
$statement_jurusan = $pdo->prepare($sql_jurusan);
$statement_jurusan->execute();
$jurusan_list = $statement_jurusan->fetchAll(PDO::FETCH_ASSOC);

// Ambil jurusan pertama sebagai default jika jurusan tidak dipilih
$jurusan = isset($_GET['jurusan']) && !empty($_GET['jurusan']) ? $_GET['jurusan'] : $jurusan_list[0]['id_jurusan'];

$status = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : null;

if ($status) {
  $status_value = ($status === 'tidak-aktif') ? 'Tidak Aktif' : $status;
  $conditions[] = "status = :status";
  $params[':status'] = $status_value;
}

if ($jurusan) {
  $conditions[] = "id_jurusan = :jurusan";
  $params[':jurusan'] = $jurusan;
}

$where_clause = '';
if (count($conditions) > 0) {
  $where_clause = "WHERE " . implode(' AND ', $conditions);
}

$sql_count = "SELECT COUNT(*) AS total FROM akun_nasabah $where_clause";
$statement_count = $pdo->prepare($sql_count);
$statement_count->execute($params);
$row_count = $statement_count->fetch();
$total_records = $row_count['total'];

$total_pages = ceil($total_records / $results_per_page);

if ($page > $total_pages) {
  $page = $total_pages;
} elseif ($page < 1) {
  $page = 1;
}

$sql_nasabah = "SELECT * FROM akun_nasabah $where_clause LIMIT :limit OFFSET :offset";
$statement_nasabah = $pdo->prepare($sql_nasabah);
if ($status) {
  $statement_nasabah->bindValue(':status', $params[':status'], PDO::PARAM_STR);
}
if ($jurusan) {
  $statement_nasabah->bindValue(':jurusan', $params[':jurusan'], PDO::PARAM_INT);
}
$statement_nasabah->bindValue(':limit', $results_per_page, PDO::PARAM_INT);
$statement_nasabah->bindValue(':offset', $starting_limit, PDO::PARAM_INT);
$statement_nasabah->execute();

$nasabah = $statement_nasabah->fetchAll();

$sql_kelas = "SELECT id_kelas, nama_kelas FROM kelas";
$statement_kelas = $pdo->prepare($sql_kelas);
$statement_kelas->execute();
$kelas_list = $statement_kelas->fetchAll(PDO::FETCH_ASSOC);

$kelas_map = array_column($kelas_list, 'nama_kelas', 'id_kelas');
$jurusan_map = array_column($jurusan_list, 'nama_jurusan', 'id_jurusan');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bank Mini - Data Nasabah</title>
  <link rel="stylesheet" href="style.php" media="screen">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
    tr,
    th {
      vertical-align: middle;
    }
    .jurusan-btn.active {
      background-color: #007bff;
      color: white;
    }
  </style>

</head>

<body>

  <div class="container mt-5">

    <h2 class="mb-4">Bank Mini</h2>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link" aria-current="page" href="index.php">Data Transaksi</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="data_nasabah.php">Data Nasabah</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="data_perjurusan.php">Data PerJurusan</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="jurusan/data_jurusan.php">Data Jurusan</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="kelas/data_kelas.php">Data Kelas</a>
      </li>
      <li class="nav-item dropdown" id="dropdown-aksi">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Aksi</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="form_transaksi.php">Tambah Transaksi</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item" href="form_nasabah.php">Tambah Data Nasabah</a></li>
          <li><a class="dropdown-item" href="jurusan/form.php">Tambah Data Jurusan</a></li>
          <li><a class="dropdown-item" href="kelas/form.php">Tambah Data Kelas</a></li>
        </ul>
      </li>
    </ul>

    <legend class="mt-4" id="data_perjurusan">Data Nasabah Perjurusan: </legend>

    <div class="my-3">
      <?php foreach ($jurusan_list as $jur): ?>
        <a href="data_perjurusan.php?jurusan=<?= $jur['id_jurusan'] ?>&status=<?= urlencode($status ?? '') ?>" 
           class="btn m-1 jurusan-btn btn-outline-primary <?= ($jurusan == $jur['id_jurusan']) ? 'active' : '' ?>"><?= htmlspecialchars($jur['nama_jurusan']) ?></a>
      <?php endforeach; ?>
    </div>


    <table class="table table-bordered text-center mb-5">
      <thead>
        <tr>
          <th scope="col">No.</th>
          <th scope="col">No Rek</th>
          <th scope="col">Nama Nasabah</th>
          <th scope="col">Kelas</th>
          <th scope="col">Jurusan</th>
          <th scope="col">Saldo</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = $starting_limit + 1;
        foreach ($nasabah as $n) : ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($n['no_rekening']) ?></td>
            <td><?= htmlspecialchars($n['nama']) ?></td>
            <td><?= isset($kelas_map[$n['id_kelas']]) ? htmlspecialchars($kelas_map[$n['id_kelas']]) : 'N/A' ?></td>
            <td><?= isset($jurusan_map[$n['id_jurusan']]) ? htmlspecialchars($jurusan_map[$n['id_jurusan']]) : 'N/A' ?></td>
            <td><?= number_format($n['saldo'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="data_perjurusan.php?page=<?= $page - 1 ?>&jurusan=<?= urlencode($jurusan) ?>&status=<?= urlencode($status ?? '') ?>">Sebelumnya</a>
          </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="data_perjurusan.php?page=<?= $i ?>&jurusan=<?= urlencode($jurusan) ?>&status=<?= urlencode($status ?? '') ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <li class="page-item">
            <a class="page-link" href="data_perjurusan.php?page=<?= $page + 1 ?>&jurusan=<?= urlencode($jurusan) ?>&status=<?= urlencode($status ?? '') ?>">Selanjutnya</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
