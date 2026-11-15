<?php
require_once 'koneksi.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$data_laporan = [];
$jumlah_pelanggan = 0;
$total_pendapatan = 0;
$pesan = '';

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {

    if ($tanggal_mulai > $tanggal_selesai) {
        $pesan = "Tanggal mulai tidak boleh melebihi tanggal selesai.";
    } else {

        // Mengambil rekapitulasi penjualan per hari
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

        $data_laporan = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Menghitung pelanggan unik dan total pendapatan
        $sql_ringkasan = "
            SELECT
                COUNT(DISTINCT pelanggan_id) AS jumlah_pelanggan,
                COALESCE(SUM(total), 0) AS total_pendapatan
            FROM transaksi
            WHERE DATE(waktu_transaksi) BETWEEN :mulai AND :selesai
        ";

        $stmt = $pdo->prepare($sql_ringkasan);
        $stmt->execute([
            ':mulai' => $tanggal_mulai,
            ':selesai' => $tanggal_selesai
        ]);

        $ringkasan = $stmt->fetch(PDO::FETCH_ASSOC);

        $jumlah_pelanggan = $ringkasan['jumlah_pelanggan'];
        $total_pendapatan = $ringkasan['total_pendapatan'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #f4f6f9;
        }

        .container {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .summary-card {
            background: #ffffff;
            padding: 20px;
            border-left: 5px solid #0d6efd;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .container {
                width: 100%;
                max-width: 100%;
                margin: 0;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #ddd;
            }

            canvas {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Laporan Penjualan</h3>
        <span class="badge bg-primary">Reporting</span>
    </div>

    <!-- FORM FILTER TANGGAL -->
    <div class="card shadow-sm p-4 mb-4 no-print">

        <h5 class="mb-3">Filter Tanggal Transaksi</h5>

        <form method="GET" action="">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input
                        type="date"
                        name="tanggal_mulai"
                        class="form-control"
                        value="<?= htmlspecialchars($tanggal_mulai) ?>"
                        required
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input
                        type="date"
                        name="tanggal_selesai"
                        class="form-control"
                        value="<?= htmlspecialchars($tanggal_selesai) ?>"
                        required
                    >
                </div>

                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        Tampilkan
                    </button>
                </div>

            </div>

        </form>
    </div>

    <!-- PESAN ERROR -->
    <?php if ($pesan): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($pesan) ?>
        </div>
    <?php endif; ?>

    <?php if ($tanggal_mulai && $tanggal_selesai && !$pesan): ?>

        <!-- TOMBOL CETAK DAN EKSPOR -->
        <div class="d-flex justify-content-end gap-2 mb-3 no-print">

            <button onclick="window.print()" class="btn btn-secondary">
                Cetak
            </button>

            <a
                href="export.php?tanggal_mulai=<?= urlencode($tanggal_mulai) ?>&tanggal_selesai=<?= urlencode($tanggal_selesai) ?>"
                class="btn btn-success"
            >
                Ekspor Excel
            </a>

        </div>

        <!-- GRAFIK PENJUALAN -->
        <div class="card shadow-sm p-4 mb-4">

            <h5 class="mb-3">Grafik Pendapatan Penjualan Harian</h5>

            <canvas id="grafikPenjualan"></canvas>

        </div>

        <!-- TABEL REKAPITULASI -->
        <div class="card shadow-sm p-4 mb-4">

            <h5 class="mb-3">Rekapitulasi Penjualan Harian</h5>

            <div class="table-responsive">

                <table class="table table-bordered table-striped text-center">

                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Total Pendapatan</th>
                            <th>Tanggal Transaksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($data_laporan) > 0): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($data_laporan as $data): ?>
                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td>
                                        Rp <?= number_format($data['total_pendapatan'], 0, ',', '.') ?>
                                    </td>

                                    <td>
                                        <?= date('d-m-Y', strtotime($data['tanggal_transaksi'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="3">Tidak ada data penjualan.</td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>
        </div>

        <!-- KARTU RINGKASAN -->
        <div class="row mb-4">

            <div class="col-md-6 mb-3">

                <div class="card summary-card shadow-sm">

                    <h6 class="text-muted">Jumlah Pelanggan Unik</h6>

                    <h3>
                        <?= number_format($jumlah_pelanggan, 0, ',', '.') ?>
                    </h3>

                    <small class="text-muted">
                        Berdasarkan pelanggan_id tanpa duplikasi
                    </small>

                </div>

            </div>

            <div class="col-md-6 mb-3">

                <div class="card summary-card shadow-sm">

                    <h6 class="text-muted">Total Pendapatan Keseluruhan</h6>

                    <h3>
                        Rp <?= number_format($total_pendapatan, 0, ',', '.') ?>
                    </h3>

                    <small class="text-muted">
                        Akumulasi seluruh transaksi pada periode terpilih
                    </small>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>

<!-- SCRIPT GRAFIK CHART.JS -->
<script>
    const labels = <?= json_encode(array_column($data_laporan, 'tanggal_transaksi')) ?>;

    const values = <?= json_encode(array_map('floatval', array_column($data_laporan, 'total_pendapatan'))) ?>;

    const ctx = document.getElementById('grafikPenjualan');

    if (ctx) {
        new Chart(ctx, {
            type: 'bar',

            data: {
                labels: labels,

                datasets: [{
                    label: 'Total Pendapatan (Rp)',
                    data: values,
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgb(13, 110, 253)',
                    borderWidth: 1
                }]
            },

            options: {
                responsive: true,

                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal Transaksi'
                        }
                    },

                    y: {
                        beginAtZero: true,

                        title: {
                            display: true,
                            text: 'Total Pendapatan (Rp)'
                        },

                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
</script>

</body>
</html>
