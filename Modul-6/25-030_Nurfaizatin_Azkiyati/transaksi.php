<?php
require_once 'koneksi.php';

// Mengambil data transaksi
$query = "SELECT
            t.id,
            t.waktu_transaksi,
            t.keterangan,
            t.total,
            p.nama AS nama_pelanggan
          FROM penjualan_transaksi t
          JOIN penjualan_pelanggan p
          ON t.pelanggan_id = p.id
          ORDER BY t.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute();

$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Data Transaksi Penjualan</h4>
        </div>

        <div class="card-body">

            <p>Berikut adalah daftar transaksi penjualan.</p>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>ID Transaksi</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Keterangan</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($transaksi) > 0): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($transaksi as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td><?= $row['id'] ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['waktu_transaksi']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_pelanggan']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['keterangan']) ?>
                                    </td>

                                    <td>
                                        Rp <?= number_format($row['total'], 0, ',', '.') ?>
                                    </td>

                                    <td>
                                        <a href="detail_transaksi.php?id=<?= $row['id'] ?>"
                                           class="btn btn-sm btn-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center">
                                    Belum ada transaksi.
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

</body>
</html>