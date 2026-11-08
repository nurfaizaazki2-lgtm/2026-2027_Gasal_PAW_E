CREATE TABLE penjualan_supplier (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nama VARCHAR(100),
  telp VARCHAR(12),
  alamat TEXT
);

CREATE TABLE penjualan_pelanggan (
  id VARCHAR(20) PRIMARY KEY,
  nama VARCHAR(100),
  jenis_kelamin ENUM('L','P'),
  telp VARCHAR(12),
  alamat TEXT
);

CREATE TABLE penjualan_barang (
  id INT PRIMARY KEY AUTO_INCREMENT,
  kode_barang VARCHAR(10),
  nama_barang VARCHAR(100),
  harga INT,
  stok INT,
  supplier_id INT,
  FOREIGN KEY (supplier_id) REFERENCES penjualan_supplier(id)
);

CREATE TABLE penjualan_transaksi (
  id INT PRIMARY KEY AUTO_INCREMENT,
  waktu_transaksi DATE,
  keterangan TEXT,
  total INT,
  pelanggan_id VARCHAR(20),
  FOREIGN KEY (pelanggan_id) REFERENCES penjualan_pelanggan(id)
);

CREATE TABLE penjualan_transaksi_detail (
  transaksi_id INT,
  barang_id INT,
  harga INT,
  qty INT,
  PRIMARY KEY (transaksi_id, barang_id),
  FOREIGN KEY (transaksi_id) REFERENCES penjualan_transaksi(id),
  FOREIGN KEY (barang_id) REFERENCES penjualan_barang(id)
);

CREATE TABLE penjualan_pembayaran (
  id INT PRIMARY KEY AUTO_INCREMENT,
  waktu_bayar DATETIME,
  total INT,
  metode ENUM('TUNAI','TRANSFER','EDC'),
  transaksi_id INT,
  FOREIGN KEY (transaksi_id) REFERENCES penjualan_transaksi(id)
);

CREATE TABLE penjualan_user (
  id_user TINYINT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(30),
  password VARCHAR(35),
  nama VARCHAR(50),
  alamat VARCHAR(150),
  hp VARCHAR(20),
  level TINYINT(1)
);

INSERT INTO penjualan_supplier (nama, telp, alamat) VALUES
('PT Sumber Makmur', '081234567801', 'Surabaya'),
('CV Berkah Jaya', '081234567802', 'Bangkalan'),
('PT Mitra Sejahtera', '081234567803', 'Sidoarjo'),
('CV Abadi Sentosa', '081234567804', 'Gresik'),
('PT Nusantara Niaga', '081234567805', 'Madura');


-- 2. DATA PELANGGAN (5 DATA)

INSERT INTO penjualan_pelanggan
(id, nama, jenis_kelamin, telp, alamat) VALUES
('P001', 'Andi Saputra', 'L', '081111111111', 'Bangkalan'),
('P002', 'Siti Aminah', 'P', '081222222222', 'Surabaya'),
('P003', 'Budi Santoso', 'L', '081333333333', 'Sampang'),
('P004', 'Dewi Lestari', 'P', '081444444444', 'Pamekasan'),
('P005', 'Rina Kartika', 'P', '081555555555', 'Sumenep');


-- =====================================
-- 3. DATA BARANG (10 DATA)
-- =====================================

INSERT INTO penjualan_barang
(kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001', 'Laptop ASUS', 7500000, 10, 1),
('BRG002', 'Mouse Logitech', 150000, 30, 2),
('BRG003', 'Keyboard Mechanical', 350000, 20, 3),
('BRG004', 'Monitor LG 24 Inch', 1800000, 15, 4),
('BRG005', 'Flashdisk 64GB', 100000, 50, 5),
('BRG006', 'Headset Gaming', 250000, 25, 1),
('BRG007', 'Printer Epson', 2200000, 8, 2),
('BRG008', 'Webcam HD', 400000, 18, 3),
('BRG009', 'Speaker Bluetooth', 300000, 20, 4),
('BRG010', 'Harddisk External 1TB', 900000, 12, 5);


-- =====================================
-- 4. DATA USER (5 DATA)
-- =====================================

INSERT INTO penjualan_user
(username, password, nama, alamat, hp, level) VALUES
('admin', 'admin123', 'Administrator', 'Bangkalan', '081666666661', 1),
('kasir01', 'kasir123', 'Kasir Satu', 'Surabaya', '081666666662', 2),
('kasir02', 'kasir123', 'Kasir Dua', 'Sampang', '081666666663', 2),
('gudang01', 'gudang123', 'Petugas Gudang', 'Pamekasan', '081666666664', 3),
('owner', 'owner123', 'Pemilik Toko', 'Sumenep', '081666666665', 1);

-- =====================================
-- 5. DATA TRANSAKSI (10 DATA)
-- =====================================

INSERT INTO penjualan_transaksi
(waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-20', 'Pembelian perlengkapan komputer', 0, 'P001'),
('2026-10-21', 'Pembelian aksesoris komputer', 0, 'P002'),
('2026-10-22', 'Pembelian perangkat elektronik', 0, 'P003'),
('2026-10-23', 'Pembelian perlengkapan kantor', 0, 'P004'),
('2026-10-24', 'Pembelian perangkat komputer', 0, 'P005'),
('2026-10-25', 'Pembelian aksesoris laptop', 0, 'P001'),
('2026-10-26', 'Pembelian perlengkapan kerja', 0, 'P002'),
('2026-10-27', 'Pembelian perangkat tambahan', 0, 'P003'),
('2026-10-28', 'Pembelian alat elektronik', 0, 'P004'),
('2026-10-29', 'Pembelian perlengkapan komputer', 0, 'P005');


-- =====================================
-- 6. DATA TRANSAKSI DETAIL (20 DATA)
-- =====================================

INSERT INTO penjualan_transaksi_detail
(transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 7500000, 1),
(1, 2, 150000, 2),

(2, 3, 350000, 1),
(2, 5, 100000, 3),

(3, 4, 1800000, 1),
(3, 6, 250000, 2),

(4, 7, 2200000, 1),
(4, 8, 400000, 1),

(5, 9, 300000, 2),
(5, 10, 900000, 1),

(6, 1, 7500000, 1),
(6, 6, 250000, 1),

(7, 2, 150000, 3),
(7, 3, 350000, 2),

(8, 4, 1800000, 1),
(8, 8, 400000, 2),

(9, 5, 100000, 5),
(9, 9, 300000, 1),

(10, 7, 2200000, 1),
(10, 10, 900000, 2);


-- =====================================
-- 7. HITUNG TOTAL SETIAP TRANSAKSI
-- =====================================

UPDATE penjualan_transaksi t
SET t.total = (
    SELECT COALESCE(SUM(d.harga * d.qty), 0)
    FROM penjualan_transaksi_detail d
    WHERE d.transaksi_id = t.id
);

SELECT 'Supplier' AS tabel, COUNT(*) AS jumlah
FROM penjualan_supplier

UNION ALL

SELECT 'Pelanggan', COUNT(*)
FROM penjualan_pelanggan

UNION ALL

SELECT 'Barang', COUNT(*)
FROM penjualan_barang

UNION ALL

SELECT 'User', COUNT(*)
FROM penjualan_user

UNION ALL

SELECT 'Transaksi', COUNT(*)
FROM penjualan_transaksi

UNION ALL

SELECT 'Transaksi Detail', COUNT(*)
FROM penjualan_transaksi_detail

UNION ALL

SELECT 'Pembayaran', COUNT(*)
FROM penjualan_pembayaran;