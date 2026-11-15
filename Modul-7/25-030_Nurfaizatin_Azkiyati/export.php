<?php
require_once 'koneksi.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

if (empty($tanggal_mulai) || empty($tanggal_selesai)) {
    die("Tanggal mulai dan tanggal selesai harus diisi.");
}

if ($tanggal_mulai > $tanggal_selesai) {
    die("Tanggal mulai tidak boleh melebihi tanggal selesai.");
}

// Mengambil data laporan penjualan
$sql = "
    SELECT
        DATE(waktu_transaksi) AS tanggal_transaksi,
        SUM(total) AS total_pendapatan
    FROM transaksi
    WHERE DATE(waktu_transaksi) BETWEEN :mulai AND :selesai
    GROUP BY DATE(waktu_transaksi)
    ORDER BY tanggal_transaksi ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':mulai' => $tanggal_mulai,
    ':selesai' => $tanggal_selesai
]);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengatur file Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Laporan_Penjualan.xls");

echo "\xEF\xBB\xBF";
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Total Pendapatan</th>
        <th>Tanggal Transaksi</th>
    </tr>

    <?php
    $no = 1;
    $total = 0;

    foreach ($data as $row):
        $total += $row['total_pendapatan'];
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['total_pendapatan'] ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal_transaksi'])) ?></td>
        </tr>
    <?php endforeach; ?>

    <tr>
        <th colspan="1">Total</th>
        <th><?= $total ?></th>
        <th></th>
    </tr>
</table>
