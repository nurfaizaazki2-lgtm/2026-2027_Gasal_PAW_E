<?php
require_once 'koneksi.php';

$id = $_GET['id'];
$hapus = $DBH->prepare("DELETE FROM supplier WHERE id = :id");
$hapus->execute([':id' => $id]);

header("Location: index.php");
exit;
?>