<?php
require_once 'koneksi.php';

$supplier = $DBH->prepare("SELECT * FROM supplier");
$supplier->execute();

$data = $supplier->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Master Supplier</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
        }

        h2 {
            color: #56899e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #d9edf7;
        }

        .tambah {
            background-color: #70ad47;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            float: right;
            border-radius: 3px;
        }

        .edit, .hapus {
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 3px;
        }

        .edit {
            background-color: #e87519;
        }

        .hapus {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

    <h2>Data Master Supplier</h2>

    <a href="tambah.php" class="tambah">Tambah Data</a>
    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Alamat</th>
            <th>Tindakan</th>
        </tr>

        <?php
        $no = 1;

        foreach ($data as $row) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td><?= htmlspecialchars($row['telp']); ?></td>
            <td><?= htmlspecialchars($row['alamat']); ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id']; ?>" class="edit">Edit</a>
                <a href="hapus.php?id=<?= $row['id']; ?>"
                   class="hapus"
                   onclick="return confirm('Anda yakin akan menghapus supplier ini?')">
                   Hapus
                </a>
            </td>
        </tr>
        <?php } ?>

    </table>
</body>
</html>