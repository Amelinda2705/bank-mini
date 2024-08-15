<?php
require_once 'koneksi.php';
require 'vendor/autoload.php'; // Pastikan Anda sudah menginstal PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menambahkan header kolom
$sheet->setCellValue('A1', 'Tanggal');
$sheet->setCellValue('B1', 'No Rek');
$sheet->setCellValue('C1', 'Nama Nasabah');
$sheet->setCellValue('D1', 'Jenis Transaksi');
$sheet->setCellValue('E1', 'Jumlah');
$sheet->setCellValue('F1', 'Saldo Setelah Transaksi');

// Query untuk mendapatkan data transaksi
$sql_transaksi = "
    SELECT rt.id_nasabah, an.no_rekening, an.nama, rt.jenis_transaksi, rt.jumlah, rt.tanggal, rt.saldo_setelah
    FROM riwayat_transaksi rt
    JOIN akun_nasabah an ON rt.id_nasabah = an.id_nasabah
    ORDER BY rt.tanggal DESC
";
$statement_transaksi = $pdo->prepare($sql_transaksi);
$statement_transaksi->execute();
$transaksi = $statement_transaksi->fetchAll(PDO::FETCH_ASSOC);

// Menambahkan data ke baris selanjutnya
$row = 2;
foreach ($transaksi as $t) {
    $sheet->setCellValue('A' . $row, $t['tanggal']);
    $sheet->setCellValue('B' . $row, $t['no_rekening']);
    $sheet->setCellValue('C' . $row, $t['nama']);
    $sheet->setCellValue('D' . $row, $t['jenis_transaksi']);
    $sheet->setCellValue('E' . $row, number_format($t['jumlah'], 0, ',', '.'));
    $sheet->setCellValue('F' . $row, number_format($t['saldo_setelah'], 0, ',', '.'));
    $row++;
}

// Menyimpan spreadsheet ke file dan memberikan output ke browser untuk diunduh
$writer = new Xlsx($spreadsheet);
$filename = 'data_transaksi_bank_mini.xlsx';

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
