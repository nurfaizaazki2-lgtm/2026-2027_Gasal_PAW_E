USE penjualan;

INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('01', 'Buku Tulis', 5000, 100, 51),
('02', 'Pulpen', 3000, 150, 52),
('03', 'Pensil', 2500, 120, 53),
('04', 'Penghapus', 2000, 80, 54),
('05', 'Penggaris', 4000, 75, 55),
('06', 'Spidol', 7000, 60, 56),
('07', 'Map Plastik', 3500, 90, 57),
('08', 'Kertas HVS', 45000, 40, 58),
('09', 'Stapler', 15000, 30, 59),
('10', 'Isi Staples', 5000, 55, 60);

SELECT * FROM barang;
