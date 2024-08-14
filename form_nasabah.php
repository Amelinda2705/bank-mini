<?php

require_once 'koneksi.php';

$sql_kelas = "SELECT id_kelas, nama_kelas FROM kelas";
$statement_kelas = $pdo->prepare($sql_kelas);
$statement_kelas->execute();
$kelas_list = $statement_kelas->fetchAll(PDO::FETCH_ASSOC);

$sql_jurusan = "SELECT id_jurusan, nama_jurusan FROM jurusan";
$statement_jurusan = $pdo->prepare($sql_jurusan);
$statement_jurusan->execute();
$jurusan_list = $statement_jurusan->fetchAll(PDO::FETCH_ASSOC);

$kelas_map = array_column($kelas_list, 'nama_kelas', 'id_kelas');
$jurusan_map = array_column($jurusan_list, 'nama_jurusan', 'id_jurusan');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama'];
  // $no_rekening = $_POST['no_rekening'];
  $id_kelas = $_POST['id_kelas'];
  $id_jurusan = $_POST['id_jurusan'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $tanggal_pembuatan = $_POST['tanggal_pembuatan'];
  $saldo = $_POST['saldo'];
  $status = $_POST['status'];

  $sql = "INSERT INTO akun_nasabah (nama, id_kelas, id_jurusan, jenis_kelamin, tanggal_pembuatan, saldo, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $statement = $pdo->prepare($sql);
  $statement->execute([$nama, $id_kelas, $id_jurusan, $jenis_kelamin, $tanggal_pembuatan, $saldo, $status]);
  echo "<script>alert('Data nasabah berhasil ditambahkan');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bank Mini - Form Tambah Data Nasabah</title>
  <link rel="stylesheet" href="style.php" media="screen">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script>
    const maxSaldo = 100000000000;

    function validateName() {
      const nama = document.getElementById('nama').value.trim();
      if (nama === "") {
        alert('Nama harus diisi');
        return false;
      }
      return true;
    }

    function validateKelas() {
      const kelas = document.getElementById('id_kelas').value;
      if (kelas === "") {
        alert('Kelas harus diisi');
        return false;
      }
      return true;
    }

    function validateJurusan() {
      const jurusan = document.getElementById('id_jurusan').value;
      if (jurusan === "") {
        alert('Jurusan harus diisi');
        return false;
      }
      return true;
    }

    function validateJenisKelamin() {
      const jenis_kelamin = document.querySelector('input[name="jenis_kelamin"]:checked');
      if (!jenis_kelamin) {
        alert('Jenis kelamin harus diisi');
        return false;
      }
      return true;
    }

    function validateSaldo() {
      const saldo = parseFloat(document.getElementById('saldo').value.trim());
      if (isNaN(saldo)) {
        alert('Saldo harus diisi');
        return false;
      }
      if (saldo <= 1) {
        alert('Saldo dilarang di bawah angka 1');
        return false;
      }
      if (saldo > maxSaldo) {
        alert('Saldo tidak boleh melebihi batas maksimal Rp 100.000.000.000.');
        return false;
      }
      return true;
    }

    function validateStatus() {
      const status = document.getElementById('status').value.trim();
      if (status === "") {
        alert('Status harus diisi');
        return false;
      }
      return true;
    }

    function confirmSave() {
      if (!validateName() || !validateKelas() || !validateJurusan() || !validateJenisKelamin() || !validateTanggalPembuatan() || !validateSaldo() || !validateStatus()) {
        return false;
      }
      return confirm('Apakah anda yakin ingin menyimpan data ini?');
    }
  </script>

</head>

<body>

  <div class="container mt-5">
    <h2 class="mb-4">Bank Mini</h2>
    
    <!-- navbar -->
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link" aria-current="page" href="index.php">Data Transaksi</a>
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
        <a class="nav-link dropdown-toggle active" href="#" data-bs-toggle="dropdown" aria-expanded="false">Aksi</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="form_transaksi.php">Tambah Transaksi</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item active" href="form_nasabah.php">Tambah Data Nasabah</a></li>
          <li><a class="dropdown-item" href="jurusan/form.php">Tambah Data Jurusan</a></li>
          <li><a class="dropdown-item" href="kelas/form.php">Tambah Data Kelas</a></li>
        </ul>
      </li>
    </ul>

    <!-- form -->
    <h2 class="text-center mt-5">Membuat Akun Nasabah Bank Mini</h2>
    <div class="m-5 px-5">
      <legend>Data Nasabah Baru</legend>
      <form method="post" onsubmit="return confirmSave();" class="row g-3" id="formNasabah">
        <div class="col-12">
          <label for="nama" class="form-label">Nama Nasabah: </label>
          <input type="text" name="nama" id="nama" class="form-control">
        </div>
        <div class="col-md-4">
          <label for="id_kelas" class="form-label">Kelas:</label>
          <select id="id_kelas" name="id_kelas" class="form-select">
            <option value="" disabled selected>Pilih Kelas</option>
            <?php foreach ($kelas_list as $k) { ?>
              <option value="<?= htmlspecialchars($k['id_kelas']) ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-4">
          <label for="id_jurusan" class="form-label">Jurusan:</label>
          <select id="id_jurusan" name="id_jurusan" class="form-select">
            <option value="">Pilih Jurusan</option>
            <?php foreach ($jurusan_list as $j) { ?>
              <option value="<?= htmlspecialchars($j['id_jurusan']) ?>"><?= htmlspecialchars($j['nama_jurusan']) ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-4">
          <label for="jenis_kelamin" class="form-label">Jenis Kelamin: </label><br />
          <input type="radio" id="jenis_kelamin_l" class="form-check-input" name="jenis_kelamin" value="L">
          <label for="jenis_kelamin_l" class="form-check-label">L</label>
          <input type="radio" id="jenis_kelamin_p" class="form-check-input" name="jenis_kelamin" value="P">
          <label for="jenis_kelamin_p" class="form-check-label">P</label>
        </div>
        <div class="col-md-4">
          <label for="tanggal_pembuatan" class="form-label">Tanggal Pembuatan: </label>
          <input type="date" name="tanggal_pembuatan" id="tanggal_pembuatan" class="form-control">
        </div>
        <div class="col-md-4">
          <label for="saldo" class="form-label">Saldo: </label>
          <input type="number" name="saldo" id="saldo" class="form-control">
        </div>
        <div class="col-md-4">
          <label for="status" class="form-label">Status: </label>
          <select name="status" id="status" class="form-select">
            <option value="" disabled selected>Status</option>
            <option value="Aktif">Aktif</option>
            <option value="Tidak Aktif">Tidak Aktif</option>
          </select>
        </div>
        <div class="col-12">
          <input type="submit" class="btn btn-primary" name="submit" value="Buat Akun" />
          <button type="button" class="btn"><a href="index.php">Kembali</a></button>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>