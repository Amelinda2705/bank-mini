<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Mini - Form Tambah Data Jurusan</title>
    <link rel="stylesheet" href="style.php" media="screen">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script>
        function validateForm() {
            const namaJurusan = document.getElementById("nama_jurusan").value;
            if (namaJurusan.trim() === "") {
                alert("Nama Jurusan harus diisi");
                return false;
            }
            return confirm("Apakah anda yakin untuk menambah jurusan?");
        }
    </script>
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
                <a class="nav-link" href="data_jurusan.php">Data Jurusan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../kelas/data_kelas.php">Data Kelas</a>
            </li>
            <li class="nav-item dropdown" id="dropdown-aksi">
                <a class="nav-link dropdown-toggle active" href="#" id="dropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Aksi</a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li><a class="dropdown-item" href="../form_transaksi.php">Tambah Transaksi</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../form_nasabah.php">Tambah Data Nasabah</a></li>
                    <li><a class="dropdown-item active" href="form.php">Tambah Data Jurusan</a></li>
                    <li><a class="dropdown-item" href="../kelas/form.php">Tambah Data Kelas</a></li>
                </ul>
            </li>
        </ul>
        <h2 class="text-center mt-5">Menambahkan Data Jurusan Baru</h2>
        <div class="m-5 px-5">
            <form action="add.php" method="post" onsubmit="return validateForm();" class="row g-3">
                <div class="col-12">
                    <label for="nama_jurusan" class="form-label">Nama Jurusan:</label>
                    <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan">
                </div>
                <div class="col-12">
                    <input type="submit" class="btn btn-primary" name="submit" value="Tambah data">
                    <button type="button" class="btn"><a href="../index.php" class="text-decoration-none">Kembali</a></button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
