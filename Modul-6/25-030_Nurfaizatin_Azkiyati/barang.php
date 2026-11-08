<?php
require_once 'koneksi.php';

// Mengambil data barang dan nama supplier
$query = "SELECT
            b.id,
            b.kode_barang,
            b.nama_barang,
            b.harga,
            b.stok,
            s.nama AS nama_supplier
          FROM penjualan_barang b
          LEFT JOIN penjualan_supplier s
            ON b.supplier_id = s.id
          ORDER BY b.id ASC";

$stmt = $pdo->prepare($query);
$stmt->execute();

$barang = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengambil status notifikasi
$status = $_GET['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Barang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">

        <!-- Judul halaman -->
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Data Barang</h4>
        </div>

        <div class="card-body">

            <!-- Notifikasi -->
            <?php if ($status == 'digunakan'): ?>

                <div class="alert alert-danger">
                    Barang tidak dapat dihapus karena sudah digunakan dalam transaksi!
                </div>

            <?php elseif ($status == 'terhapus'): ?>

                <div class="alert alert-success">
                    Barang berhasil dihapus!
                </div>

            <?php elseif ($status == 'gagal'): ?>

                <div class="alert alert-warning">
                    Barang gagal dihapus atau tidak ditemukan.
                </div>

            <?php elseif ($status == 'invalid'): ?>

                <div class="alert alert-warning">
                    ID barang tidak valid.
                </div>

            <?php endif; ?>

            <p>Berikut adalah daftar barang yang tersedia.</p>

            <!-- Tabel data barang -->
            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Supplier</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($barang) > 0): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($barang as $row): ?>

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

                                    <td>
                                        <?= htmlspecialchars((string) $row['stok']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_supplier'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <form action="hapus_barang.php"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus barang ini?')">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?= $row['id'] ?>">

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>

                                        </form>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center">
                                    Belum ada data barang.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <!-- Tombol kembali -->
            <a href="transaksi.php" class="btn btn-secondary">
                Kembali ke Transaksi
            </a>

        </div>

    </div>

</div>

</body>
</html>
