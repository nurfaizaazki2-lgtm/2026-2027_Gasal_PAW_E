USE penjualan;


INSERT INTO transaksi
(waktu_transaksi, keterangan, total, pelanggan_id)
VALUES
('2026-10-01 09:00:00', 'Pembelian alat tulis', 10000, 131),
('2026-10-02 10:00:00', 'Pembelian perlengkapan sekolah', 9000, 132),
('2026-10-03 11:00:00', 'Pembelian barang kantor', 7500, 133),
('2026-10-04 12:00:00', 'Pembelian alat tulis', 8000, 134),
('2026-10-05 13:00:00', 'Pembelian perlengkapan', 14000, 135),
('2026-10-06 14:00:00', 'Pembelian spidol', 14000, 136),
('2026-10-07 09:30:00', 'Pembelian map', 7000, 137),
('2026-10-08 10:30:00', 'Pembelian kertas', 45000, 138),
('2026-10-09 11:30:00', 'Pembelian stapler', 30000, 139),
('2026-10-10 12:30:00', 'Pembelian isi staples', 10000, 140);

SELECT * FROM transaksi;
