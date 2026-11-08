<?php
require_once 'koneksi.php';

// Mengambil ID transaksi dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID transaksi tidak valid.");
}

// Mengambil data transaksi
$stmt = $pdo->prepare("
    SELECT * FROM penjualan_transaksi
    WHERE id = ?
");

$stmt->execute([$id]);
$transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

// Mengambil detail barang transaksi
$query = "SELECT
            d.transaksi_id,
            b.kode_barang,
            b.nama_barang,
            d.harga,
            d.qty,
            (d.harga * d.qty) AS subtotal
          FROM penjualan_transaksi_detail d
          JOIN penjualan_barang b
          ON d.barang_id = b.id
          WHERE d.transaksi_id = ?";

$stmt = $pdo->prepare($query);
$stmt->execute([$id]);

$detail = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Transaksi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4>Detail Transaksi #<?= htmlspecialchars((string) $id) ?></h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $no = 1;
                    $total = 0;

                    foreach ($detail as $row):
                        $total += $row['subtotal'];
                    ?>

                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <?= htmlspecialchars($row['kode_barang']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nama_barang']) ?>
                        </td>

                        <td>
                            Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                        </td>

                        <td><?= $row['qty'] ?></td>

                        <td>
                            Rp <?= number_format($row['subtotal'], 0, ',', '.') ?>
                        </td>
                    </tr>

                    <?php endforeach; ?>

                    <?php if (count($detail) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada barang dalam transaksi ini.
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Total</th>

                        <th>
                            Rp <?= number_format($total, 0, ',', '.') ?>
                        </th>
                    </tr>
                </tfoot>

            </table>

            <!-- Tombol Tambah Barang -->
            <a href="tambah_detail.php?id=<?= $id ?>"
               class="btn btn-primary">
                + Tambah Barang
            </a>

            <!-- Tombol Kembali -->
            <a href="transaksi.php" class="btn btn-secondary">
                Kembali
            </a>

        </div>

    </div>

</div>

</body>
</html>
