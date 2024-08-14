<?php
require_once 'koneksi.php';

$filter_start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$filter_end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$filter_jenis_transaksi = isset($_GET['jenis_transaksi']) ? $_GET['jenis_transaksi'] : '';
$filter_no_rekening = isset($_GET['no_rekening']) ? $_GET['no_rekening'] : '';
$filter_nama_nasabah = isset($_GET['nama_nasabah']) ? $_GET['nama_nasabah'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Mini - Data Transaksi</title>
    <link rel="stylesheet" href="style.php" media="screen">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Bank Mini</h2>
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Data Transaksi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="data_nasabah.php">Data Nasabah</a>
            </li>
      <li class="nav-item">
        <a class="nav-link" href="data_perjurusan.php">Data PerJurusan</a>
      </li> 
            <li class="nav-item">
                <a class="nav-link" href="jurusan/data_jurusan.php">Data Jurusan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="kelas/data_kelas.php">Data Kelas</a>
            </li>
            <li class="nav-item dropdown" id="dropdown-aksi">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Aksi</a>
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
        <legend class="mt-4">Riwayat Transaksi</legend>
        <form action="index.php" method="get" class="row g-3 mb-3" onsubmit="return validateDateRange();">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Tanggal Mulai: </label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo htmlspecialchars($filter_start_date); ?>">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Tanggal Akhir: </label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo htmlspecialchars($filter_end_date); ?>">
            </div>
            <div class="col-md-3">
                <label for="jenis_transaksi" class="form-label">Jenis Transaksi: </label>
                <select name="jenis_transaksi" id="jenis_transaksi" class="form-select">
                    <option value="">Semua</option>
                    <option value="setor" <?php echo $filter_jenis_transaksi == 'setor' ? 'selected' : ''; ?>>Setor</option>
                    <option value="tarik" <?php echo $filter_jenis_transaksi == 'tarik' ? 'selected' : ''; ?>>Tarik</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="no_rekening" class="form-label">No Rekening: </label>
                <input type="text" name="no_rekening" id="no_rekening" class="form-control" value="<?php echo htmlspecialchars($filter_no_rekening); ?>">
            </div>
            <div class="col-md-3">
                <label for="nama_nasabah" class="form-label">Nama Nasabah: </label>
                <input type="text" name="nama_nasabah" id="nama_nasabah" class="form-control" value="<?php echo htmlspecialchars($filter_nama_nasabah); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label d-block">&nbsp;</label>
                <input type="submit" class="btn btn-primary" value="Filter">
            </div>
        </form>

        <script>
            function validateDateRange() {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;

                if (startDate > endDate) {
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
                    return false;
                }
                return true;
            }
        </script>
        <table class="table table-bordered align-middle text-center mb-5">
            <thead>
                <tr>
                    <th scope="col">Tanggal</th>
                    <th scope="col">No Rek</th>
                    <th scope="col">Nama Nasabah</th>
                    <th scope="col">Jenis Transaksi</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Saldo Setelah Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_transaksi = "
                    SELECT rt.id_nasabah, an.no_rekening, an.nama, rt.jenis_transaksi, rt.jumlah, rt.tanggal, rt.saldo_setelah
                    FROM riwayat_transaksi rt
                    JOIN akun_nasabah an ON rt.id_nasabah = an.id_nasabah
                ";

                $conditions = [];

                if ($filter_start_date && $filter_end_date) {
                    $filter_end_date .= " 23:59:59";
                    $conditions[] = "rt.tanggal BETWEEN :start_date AND :end_date";
                }

                if ($filter_jenis_transaksi) {
                    $conditions[] = "rt.jenis_transaksi = :jenis_transaksi";
                }

                if ($filter_no_rekening) {
                    $conditions[] = "an.no_rekening LIKE :no_rekening";
                }

                if ($filter_nama_nasabah) {
                    $conditions[] = "an.nama LIKE :nama_nasabah";
                }

                if ($conditions) {
                    $sql_transaksi .= "WHERE " . implode(' AND ', $conditions) . " ";
                }

                $sql_transaksi .= "ORDER BY rt.tanggal DESC";
                $statement_transaksi = $pdo->prepare($sql_transaksi);

                if ($filter_start_date && $filter_end_date) {
                    $statement_transaksi->bindParam(':start_date', $filter_start_date);
                    $statement_transaksi->bindParam(':end_date', $filter_end_date);
                }

                if ($filter_jenis_transaksi) {
                    $statement_transaksi->bindParam(':jenis_transaksi', $filter_jenis_transaksi);
                }

                if ($filter_no_rekening) {
                    $filter_no_rekening = $filter_no_rekening . '%';
                    $statement_transaksi->bindParam(':no_rekening', $filter_no_rekening);
                }

                if ($filter_nama_nasabah) {
                    $filter_nama_nasabah = $filter_nama_nasabah . '%';
                    $statement_transaksi->bindParam(':nama_nasabah', $filter_nama_nasabah);
                }

                $statement_transaksi->execute();
                $transaksi = $statement_transaksi->fetchAll(PDO::FETCH_ASSOC);

                foreach ($transaksi as $t) {
                    echo "<tr>
                        <td>{$t['tanggal']}</td>
                        <td>{$t['no_rekening']}</td>
                        <td>{$t['nama']}</td>
                        <td>{$t['jenis_transaksi']}</td>
                        <td>" . number_format($t['jumlah'], 0, ',', '.') . "</td>
                        <td>" . number_format($t['saldo_setelah'], 0, ',', '.') . "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>