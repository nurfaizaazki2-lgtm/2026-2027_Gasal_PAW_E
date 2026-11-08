<?php
require_once 'koneksi.php';

// Mengambil ID transaksi dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID transaksi tidak valid.");
}

// Memeriksa apakah transaksi tersedia
$stmt = $pdo->prepare(
    "SELECT * FROM penjualan_transaksi WHERE id = ?"
);
$stmt->execute([$id]);
$transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

$pesan = "";

// Proses tambah barang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $barang_id = isset($_POST['barang_id'])
        ? (int) $_POST['barang_id'] : 0;

    $qty = isset($_POST['qty'])
        ? (int) $_POST['qty'] : 0;

    if ($barang_id <= 0 || $qty <= 0) {

        $pesan = "Pilih barang dan masukkan jumlah yang valid.";

    } else {

        try {
            $pdo->beginTransaction();

            // Mengambil harga barang dari database
            // Memastikan barang belum digunakan pada transaksi ini
            $stmt = $pdo->prepare("
                SELECT b.id, b.harga
                FROM penjualan_barang b
                WHERE b.id = ?
                AND NOT EXISTS (
                    SELECT 1
                    FROM penjualan_transaksi_detail d
                    WHERE d.transaksi_id = ?
                    AND d.barang_id = b.id
                )
            ");

            $stmt->execute([$barang_id, $id]);
            $barang_dipilih = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$barang_dipilih) {
                throw new Exception(
                    "Barang tidak tersedia atau sudah ditambahkan."
                );
            }

            // Menyimpan detail transaksi
            $stmt = $pdo->prepare("
                INSERT INTO penjualan_transaksi_detail
                (transaksi_id, barang_id, harga, qty)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $id,
                $barang_dipilih['id'],
                $barang_dipilih['harga'],
                $qty
            ]);

            // Menghitung ulang total transaksi
            $stmt = $pdo->prepare("
                UPDATE penjualan_transaksi
                SET total = (
                    SELECT COALESCE(SUM(harga * qty), 0)
                    FROM penjualan_transaksi_detail
                    WHERE transaksi_id = ?
                )
                WHERE id = ?
            ");

            $stmt->execute([$id, $id]);

            // Menyimpan perubahan
            $pdo->commit();

            // Kembali ke halaman detail transaksi
            header("Location: detail_transaksi.php?id=" . $id);
            exit;

        } catch (Exception $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $pesan = $e->getMessage();
        }
    }
}

// Mengambil barang yang belum digunakan pada transaksi ini
$stmt = $pdo->prepare("
    SELECT b.id, b.kode_barang, b.nama_barang, b.harga
    FROM penjualan_barang b
    WHERE NOT EXISTS (
        SELECT 1
        FROM penjualan_transaksi_detail d
        WHERE d.transaksi_id = ?
        AND d.barang_id = b.id
    )
    ORDER BY b.nama_barang ASC
");

$stmt->execute([$id]);
$barang = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Detail Transaksi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4>Tambah Barang Transaksi #<?= $id ?></h4>
        </div>

        <div class="card-body">

            <?php if ($pesan != ""): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($pesan) ?>
                </div>
            <?php endif; ?>

            <?php if (count($barang) > 0): ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Pilih Barang</label>

                        <select name="barang_id"
                                id="barang_id"
                                class="form-select"
                                required>

                            <option value="">-- Pilih Barang --</option>

                            <?php foreach ($barang as $b): ?>

                                <option
                                    value="<?= $b['id'] ?>"
                                    data-harga="<?= $b['harga'] ?>">

                                    <?= htmlspecialchars($b['kode_barang']) ?>
                                    -
                                    <?= htmlspecialchars($b['nama_barang']) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga Barang</label>

                        <input type="text"
                               id="harga"
                               class="form-control"
                               readonly
                               placeholder="Harga otomatis muncul">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah (Qty)</label>

                        <input type="number"
                               name="qty"
                               class="form-control"
                               min="1"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                    <a href="detail_transaksi.php?id=<?= $id ?>"
                       class="btn btn-secondary">
                        Kembali
                    </a>

                </form>

            <?php else: ?>

                <div class="alert alert-info">
                    Semua barang sudah ditambahkan ke transaksi ini.
                </div>

                <a href="detail_transaksi.php?id=<?= $id ?>"
                   class="btn btn-secondary">
                    Kembali
                </a>

            <?php endif; ?>

        </div>

    </div>

</div>

<script>
document.getElementById('barang_id')?.addEventListener('change', function() {

    let pilihan = this.options[this.selectedIndex];
    let harga = pilihan.getAttribute('data-harga');

    document.getElementById('harga').value = harga
        ? 'Rp ' + Number(harga).toLocaleString('id-ID')
        : '';

});
</script>

</body>
</html>
