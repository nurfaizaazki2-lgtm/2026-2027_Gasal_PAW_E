USE penjualan;


INSERT INTO `user` (username, password, nama, alamat, hp, level) VALUES
('admin01', 'admin123', 'Administrator', 'Surabaya', '081300000001', 1),
('kasir01', 'kasir123', 'Kasir Satu', 'Bangkalan', '081300000002', 2),
('kasir02', 'kasir123', 'Kasir Dua', 'Sidoarjo', '081300000003', 2),
('operator01', 'operator123', 'Operator Satu', 'Gresik', '081300000004', 2),
('operator02', 'operator123', 'Operator Dua', 'Madura', '081300000005', 2),
('admin02', 'admin123', 'Administrator Dua', 'Lamongan', '081300000006', 1),
('kasir03', 'kasir123', 'Kasir Tiga', 'Mojokerto', '081300000007', 2),
('kasir04', 'kasir123', 'Kasir Empat', 'Surabaya', '081300000008', 2),
('operator03', 'operator123', 'Operator Tiga', 'Bangkalan', '081300000009', 2),
('kasir05', 'kasir123', 'Kasir Lima', 'Surabaya', '081300000010', 2);

SELECT * FROM user;