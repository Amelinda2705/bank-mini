<?php
require_once '../koneksi.php';

$sql = "SELECT * FROM kelas";
$statement = $pdo->query($sql);
$kelas = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Mini - Data Kelas</title>
    <link rel="stylesheet" href="style.php" media="screen">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Bank Mini</h2>
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="../index.php">Data Transaksi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../data_nasabah.php">Data Nasabah</a>
            </li>
      <li class="nav-item">
        <a class="nav-link" href="../data_perjurusan.php">Data PerJurusan</a>
      </li>
            <li class="nav-item">
                <a class="nav-link" href="../jurusan/data_jurusan.php">Data Jurusan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="data_kelas.php">Data Kelas</a>
            </li>
            <li class="nav-item dropdown" id="dropdown-aksi">
                <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Aksi</a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li><a class="dropdown-item" href="../form_transaksi.php">Tambah Transaksi</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="../form_nasabah.php">Tambah Data Nasabah</a></li>
                    <li><a class="dropdown-item" href="../jurusan/form.php">Tambah Data Jurusan</a></li>
                    <li><a class="dropdown-item" href="form.php">Tambah Data Kelas</a></li>
                </ul>
            </li>
        </ul>
        <legend id="data_nasabah" class="mt-4">Data kelas:</legend>
        <table class="table table-bordered align-middle text-align-center mb-5">
            <thead>
                <tr>
                    <th scope="col">Nama kelas</th>
                    <th scope="col" colspan="2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = $conn->query("SELECT * FROM kelas");
                while ($dt = $q->fetch_assoc()) :
                ?>
                    <tr>
                        <td><?= htmlspecialchars($dt['nama_kelas'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><a href="update.php?id=<?= $dt['id_kelas'] ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg></a></td>
                        <td><a href="delete.php?id=<?= $dt['id_kelas'] ?>" onclick="return confirm('Anda yakin akan menghapus data ini?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                </svg></a></td>
                    </tr>
                <?php
                endwhile;
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>