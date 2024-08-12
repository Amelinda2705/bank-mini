<?php

require_once 'koneksi.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $id_kelas = $_POST['id_kelas'] ?? '';
    $id_jurusan = $_POST['id_jurusan'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $saldo = $_POST['saldo'] ?? '';
    $status = $_POST['status'] ?? '';

    // Validasi data
    if (empty($nama) || empty($id_kelas) || empty($id_jurusan) || empty($jenis_kelamin) || empty($saldo)) {
        echo "<script>alert('Semua field wajib diisi.'); window.history.back();</script>";
        exit();
    }

    $sql = "UPDATE akun_nasabah SET nama=?, id_kelas=?, id_jurusan=?, jenis_kelamin=?, saldo=?, status=? WHERE id_nasabah=?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$nama, $id_kelas, $id_jurusan, $jenis_kelamin, $saldo, $status, $id]);

    header('Location: index.php');
    exit;
} else {
    $sql = "SELECT * FROM akun_nasabah WHERE id_nasabah=?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$id]);
    $nasabah = $statement->fetch();
}

// Mengambil data kelas
$kelas = $pdo->query("SELECT id_kelas, nama_kelas FROM kelas")->fetchAll(PDO::FETCH_ASSOC);
// Mengambil data jurusan
$jurusan = $pdo->query("SELECT id_jurusan, nama_jurusan FROM jurusan")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bank mini - add nasabah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.php" media="screen">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script>
        const maxSaldo = 100000000000; // Maximum saldo limit

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

        function validateTanggalPembuatan() {
            const tanggal_pembuatan = document.getElementById('tanggal_pembuatan').value.trim();
            if (tanggal_pembuatan === "") {
                alert('Tanggal pembuatan harus diisi');
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
                alert('Saldo tidak boleh melebihi batas maksimal Rp 1.000.000.');
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

        document.getElementById('formNasabah').onsubmit = function() {
            return confirmSave();
        };
    </script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Bank Mini</h2>
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="data_transaksi.php">Data Transaksi</a>
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
        <h2 class="text-center mt-5">Ubah data nasabah</h2>
        <div class="m-5 px-5">
            <legend>Data Nasabah Baru</legend>
            <form method="post" onsubmit="return confirmSave();" class="row g-3">
                <div class="col-12">
                    <label for="nama" class="form-label">Nama Nasabah : </label>
                    <input type="text" name="nama" id="nama" class="form-control" value="<?= htmlspecialchars($nasabah['nama']) ?>">
                </div>
                <div class="col-md-4">
                    <label for="id_kelas" class="form-label">Kelas: </label>
                    <select name="id_kelas" id="kelas" class="form-select">
                        <?php foreach ($kelas as $k) { ?>
                            <option value="<?= $k['id_kelas'] ?>" <?= $nasabah['id_kelas'] == $k['id_kelas'] ? 'selected' : '' ?>><?= $k['nama_kelas'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="id_jurusan" class="form-label">Jurusan: </label>
                    <select name="id_jurusan" id="id_jurusan" class="form-select">
                        <?php foreach ($jurusan as $j) { ?>
                            <option value="<?= $j['id_jurusan'] ?>" <?= $nasabah['id_jurusan'] == $j['id_jurusan'] ? 'selected' : '' ?>><?= $j['nama_jurusan'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin: </label><br />
                    <input type="radio" name="jenis_kelamin" class="form-check-input" value="L" <?= $nasabah['jenis_kelamin'] === 'L' ? 'checked' : '' ?>> L
                    <input type="radio" name="jenis_kelamin" class="form-check-input" value="P" <?= $nasabah['jenis_kelamin'] === 'P' ? 'checked' : '' ?>> P <br>
                </div>
                <div class="col-md-4">
                    <label for="tanggal_pembuatan" class="form-label">Tanggal Pembuatan : </label>
                    <input type="date" name="tanggal_pembuatan" id="tanggal_pembuatan" class="form-control" value="<?= htmlspecialchars($nasabah['tanggal_pembuatan']) ?>" disabled>
                </div>
                <div class="col-md-4">
                    <label for="saldo" class="form-label">Saldo : </label>
                    <input type="number" name="saldo" id="saldo" class="form-control" value="<?= $nasabah['saldo'] ?>">
                </div>
                <div class="col-md-4" class="form-label">
                    <label for="status">Status: </label>
                    <select name="status" id="status" class="form-select">
                        <option value="Aktif" <?= $nasabah['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Tidak Aktif" <?= $nasabah['status'] === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-12">
                    <input type="submit" class="btn btn-primary" name="submit" value="Ubah" />
                    <button type="button" class="btn"><a href="data_nasabah.php">Kembali</a></button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>