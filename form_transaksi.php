<?php
require_once 'koneksi.php';

if (isset($_GET['no_rekening'])) {
    $no_rekening = $_GET['no_rekening'];
    $sql = "SELECT nama, saldo FROM akun_nasabah WHERE no_rekening = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$no_rekening]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
    exit();
}

// Handle AJAX request for autocomplete
if (isset($_GET['term'])) {
    $term = $_GET['term'];
    $sql = "SELECT no_rekening, nama, saldo FROM akun_nasabah WHERE (no_rekening LIKE ? OR nama LIKE ?) AND status='Aktif'";
    $statement = $pdo->prepare($sql);
    $statement->execute(["$term%", "$term%"]);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
    exit();
}

$filter_start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$filter_end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Mini - Form Tambah Data Transaksi</title>
    <link rel="stylesheet" href="style.php" media="screen">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script><script>
    $(document).ready(function() {
        let saldoNasabah = 0;

        $('#no_rekening').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: 'form_transaksi.php',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.no_rekening + ' - ' + item.nama,
                                value: item.no_rekening,
                                nama: item.nama,
                                saldo: item.saldo
                            };
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                $('#no_rekening').val(ui.item.value);
                $('#nama').val(ui.item.nama);
                $('#saldo').val(formatRupiah(ui.item.saldo, 'Rp. '));
                saldoNasabah = parseFloat(ui.item.saldo); // Simpan saldo dalam format numerik
                return false;
            }
        });

        $('#formTransaksi').on('submit', function(e) {
            const jenisTransaksi = $('#jenis_transaksi').val();
            const jumlah = parseFloat($('#jumlah').val());

            if (jenisTransaksi === 'tarik') {
                const sisaSaldo = saldoNasabah - jumlah;

                // Validasi sisa saldo minimal Rp 10.000
                if (sisaSaldo < 10000) { 
                    alert('Jumlah tarik tidak diperbolehkan karena sisa saldo setelah penarikan harus minimal Rp 10.000.');
                    e.preventDefault(); // Mencegah form dari pengiriman
                }

                // Validasi jumlah tarik minimal Rp 50.000
                if (jumlah < 50000) {
                    alert('Jumlah tarik minimal adalah Rp 50.000.');
                    e.preventDefault(); // Mencegah form dari pengiriman
                }
            }
        });
    });

    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    function validateNoRek() {
        const no_rekening = document.getElementById('no_rekening').value.trim();
        if (no_rekening === "") {
            alert('Nomor rekening harus diisi');
            return false;
        }
        return true;
    }

    function validateJenisTransaksi() {
        const jenis_transaksi = document.getElementById('jenis_transaksi').value;
        if (jenis_transaksi === "") {
            alert('Jenis Transaksi harus diisi');
            return false;
        }
        return true;
    }

    function validateSaldo() {
        const jumlah = parseFloat(document.getElementById('jumlah').value.trim());
        if (isNaN(jumlah)) {
            alert('Jumlah harus diisi');
            return false;
        }
        if (jumlah <= 1) {
            alert('Jumlah dilarang di bawah angka 1');
            return false;
        }
        return true;
    }

    function confirmSave() {
        if (!validateNoRek() || !validateJenisTransaksi() || !validateSaldo()) {
            return false;
        }
        return confirm('Apakah anda yakin ingin menyimpan data ini?');
    }

    document.getElementById('formTransaksi').onsubmit = function() {
        return confirmSave();
    };
</script>

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
                <a class="nav-link" href="jurusan/data_jurusan.php">Data Jurusan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="kelas/data_kelas.php">Data Kelas</a>
            </li>
            <li class="nav-item dropdown" id="dropdown-aksi">
                <a class="nav-link dropdown-toggle active" href="#" data-bs-toggle="dropdown" aria-expanded="false">Aksi</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item active" href="form_transaksi.php">Tambah Transaksi</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="form_nasabah.php">Tambah Data Nasabah</a></li>
                    <li><a class="dropdown-item" href="jurusan/form.php">Tambah Data Jurusan</a></li>
                    <li><a class="dropdown-item" href="kelas/form.php">Tambah Data Kelas</a></li>
                </ul>
            </li>
        </ul>
        <h2 class="text-center mt-5">Transaksi Bank Mini</h2>
        <div class="m-5 px-5">
            <legend>Form Transaksi</legend>
            <form action="proses_transaksi.php" method="post" class="row g-3" id="formTransaksi" onsubmit="return confirmSave();">
                <div class="col-md-6">
                    <label for="no_rekening" class="form-label">No Rekening: </label>
                    <input type="text" name="no_rekening" id="no_rekening" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama Nasabah: </label>
                    <input type="text" id="nama" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                    <label for="saldo" class="form-label">Saldo: </label>
                    <input type="text" id="saldo" class="form-control" readonly>
                </div>

                <div class="col-md-6">
                    <label for="jenis_transaksi" class="form-label">Jenis Transaksi: </label>
                    <select name="jenis_transaksi" id="jenis_transaksi" class="form-select">
                        <option value="" disabled selected>Pilih Jenis Transaksi</option>
                        <option value="setor">Setor</option>
                        <option value="tarik">Tarik</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="jumlah" class="form-label">Jumlah: </label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control">
                </div>
                <div class="col-12">
                    <input type="submit" class="btn btn-primary" name="submit" value="Proses Transaksi" />
                    <button type="button" class="btn"><a href="index.php">Kembali</a></button>
                </div>
            </form>
        </div>



    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>