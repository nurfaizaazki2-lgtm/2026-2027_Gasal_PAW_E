<?php
require_once 'koneksi.php';

// Mengambil ID barang
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    header("Location: barang.php?status=invalid");
    exit;
}

// Memeriksa apakah barang digunakan dalam transaksi
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM penjualan_transaksi_detail
    WHERE barang_id = ?
");

$stmt->execute([$id]);
$jumlah = $stmt->fetchColumn();

// Jika barang sudah digunakan, batalkan penghapusan
if ($jumlah > 0) {
    header("Location: barang.php?status=digunakan");
    exit;
}

// Jika belum digunakan, hapus barang
$stmt = $pdo->prepare("
    DELETE FROM penjualan_barang
    WHERE id = ?
");

$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    header("Location: barang.php?status=terhapus");
    exit;
} else {
    header("Location: barang.php?status=gagal");
    exit;
}
?>
