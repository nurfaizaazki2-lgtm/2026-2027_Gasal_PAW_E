<?php

const HOST = 'localhost';
const USER = 'root';
const PASS = '160307';
const DBNAME = 'penjualan';

const DSN = "mysql:host=" . HOST . ";dbname=" . DBNAME . ";charset=utf8mb4";

const OPTIONS = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false, 
];

try {
  $DBH = new PDO(DSN, USER, PASS, OPTIONS);
} catch (PDOException $err) {
  // Kalau gagal akan muncul error messege dibawah ini
  die("Koneksi Gagal: " . $err->getMessage());
}
