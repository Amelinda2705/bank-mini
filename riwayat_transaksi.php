<?php
require_once 'koneksi.php';

$sql = "SELECT * FROM riwayat_transaksi";
$statement = $pdo->prepare($sql);
$statement->execute();
$transaksi = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="style.php" media="screen">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-5">Riwayat Transaksi</h2>
        <table class="table table-bordered align-middle text-center mb-5">
            <thead>
                <tr>
                    <th scope="col">No Rek</th>
                    <th scope="col">Nama Nasabah</th>
                    <th scope="col">Jenis Transaksi</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($transaksi as $t) {
                    echo "<tr>
                        <td>{$t['no_rekening']}</td>
                        <td>{$t['nama']}</td>
                        <td>{$t['jenis_transaksi']}</td>
                        <td>" . number_format($t['jumlah'], 0, ',', '.') . "</td>
                        <td>{$t['tanggal']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>