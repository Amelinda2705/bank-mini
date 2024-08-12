<?php
require_once 'koneksi.php';

// Define how many results you want per page
$results_per_page = 10;

// Determine the SQL LIMIT starting number for the results on the displaying page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$starting_limit = ($page - 1) * $results_per_page;

// Fetch the filter status value
$status = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : null;
$status_condition = '';
$params = [];

if ($status) {
    // Adjust the status value to match the value in the database
    $status_value = ($status === 'tidak-aktif') ? 'Tidak Aktif' : $status;
    $status_condition = "WHERE status = :status";
    $params[':status'] = $status_value;
}

// Query to count the number of results after filtering
$sql_count = "SELECT COUNT(*) AS total FROM akun_nasabah $status_condition";
$statement_count = $pdo->prepare($sql_count);
$statement_count->execute($params);
$row_count = $statement_count->fetch();
$total_records = $row_count['total'];

// Determine the total number of pages available after filtering
$total_pages = ceil($total_records / $results_per_page);

// Validate page number
if ($page > $total_pages) {
    $page = $total_pages;
} elseif ($page < 1) {
    $page = 1;
}

// Query to fetch the selected results from database
$sql_nasabah = "SELECT * FROM akun_nasabah $status_condition LIMIT :limit OFFSET :offset";
$statement_nasabah = $pdo->prepare($sql_nasabah);
if ($status) {
    $statement_nasabah->bindValue(':status', $params[':status'], PDO::PARAM_STR);
}
$statement_nasabah->bindValue(':limit', $results_per_page, PDO::PARAM_INT);
$statement_nasabah->bindValue(':offset', $starting_limit, PDO::PARAM_INT);
$statement_nasabah->execute();

// Fetch results
$nasabah = $statement_nasabah->fetchAll();

// Fetch all kelas records
$sql_kelas = "SELECT id_kelas, nama_kelas FROM kelas";
$statement_kelas = $pdo->prepare($sql_kelas);
$statement_kelas->execute();
$kelas_list = $statement_kelas->fetchAll(PDO::FETCH_ASSOC);

// Fetch all jurusan records
$sql_jurusan = "SELECT id_jurusan, nama_jurusan FROM jurusan";
$statement_jurusan = $pdo->prepare($sql_jurusan);
$statement_jurusan->execute();
$jurusan_list = $statement_jurusan->fetchAll(PDO::FETCH_ASSOC);

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
    tr, th {
      vertical-align: middle;
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
                <a class="nav-link active" href="data_nasabah.php">Data Nasabah</a>
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
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="form_nasabah.php">Tambah Data Nasabah</a></li>
                    <li><a class="dropdown-item" href="jurusan/form.php">Tambah Data Jurusan</a></li>
                    <li><a class="dropdown-item" href="kelas/form.php">Tambah Data Kelas</a></li>
                </ul>
            </li>
        </ul>
  <legend class="mt-4" id="data_nasabah">Data Nasabah: </legend><form method="GET" action="data_nasabah.php" class="row g-3 mb-3">
    <div class="col-md-2">
        <select name="status" id="status_filter" class="form-select">
            <option value="" <?= !isset($_GET['status']) || $_GET['status'] === '' ? 'selected' : '' ?>>Semua</option>
            <option value="aktif" <?= isset($_GET['status']) && $_GET['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="tidak-aktif" <?= isset($_GET['status']) && ($_GET['status'] == 'Tidak Aktif' || $_GET['status'] == 'tidak-aktif') ? 'selected' : '' ?>>Tidak Aktif</option>
        </select>
    </div>
    <div class="col-md-4 align-self-end">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

  <table class="table table-bordered text-center mb-5">
    <thead>
      <tr>
        <th scope="col">No.</th>
        <th scope="col">No Rek</th>
        <th scope="col">Nama Nasabah</th>
        <th scope="col">Kelas</th>
        <th scope="col">Jurusan</th>
        <th scope="col">Jenis Kelamin</th>
        <th scope="col">Tanggal Pembuatan</th>
        <th scope="col">Saldo</th>
        <th scope="col">Status</th>
        <th scope="col" colspan="2">Aksi</th>
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
          <td><?= htmlspecialchars($n['jenis_kelamin']) ?></td>
          <td><?= htmlspecialchars($n['tanggal_pembuatan']) ?></td>
          <td><?= number_format($n['saldo'], 0, ',', '.') ?></td>
          <td><?= htmlspecialchars($n['status']) ?></td>
          <td><a href="update.php?id=<?= $n['id_nasabah'] ?>" class="text-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
              </svg>
            </a></td>
          <td><a href="delete.php?id=<?= $n['id_nasabah'] ?>" class="text-danger" onclick="return confirm('Anda yakin akan menghapus data ini?')">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
              </svg>
            </a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <!-- Pagination Links -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="data_nasabah.php?page=<?= $page-1 ?>&status=<?= urlencode($status ?? '') ?>">Sebelumnya</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="data_nasabah.php?page=<?= $i ?>&status=<?= urlencode($status ?? '') ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="data_nasabah.php?page=<?= $page+1 ?>&status=<?= urlencode($status ?? '') ?>">Selanjutnya</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>



  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
