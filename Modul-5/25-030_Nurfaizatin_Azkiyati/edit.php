<?php
require_once 'koneksi.php';

$id = $_GET['id'];
$stmt = $DBH->prepare("SELECT * FROM supplier WHERE id = :id");
$stmt->execute([':id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "Data supplier tidak ditemukan.";
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $ubah = $DBH->prepare("
        UPDATE supplier
        SET nama = :nama,
            telp = :telp,
            alamat = :alamat
        WHERE id = :id
    ");

    $ubah->execute([
        ':nama' => $nama,
        ':telp' => $telp,
        ':alamat' => $alamat,
        ':id' => $id
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Supplier</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
        }

        .container {
            width: 450px;
            max-width: 100%;
            margin: auto;
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        .tombol {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .simpan {
            background-color: #198754;
        }

        .simpan:hover {
            background-color: #157347;
        }

        .batal {
            background-color: #dc3545;
        }

        .batal:hover {
            background-color: #bb2d3b;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Edit Supplier</h2>

    <form method="POST">
        <label>Nama Supplier</label>
        <input type="text" name="nama"
               value="<?= htmlspecialchars($data['nama']) ?>" required>

        <label>Nomor Telepon</label>
        <input type="text" name="telp"
               value="<?= htmlspecialchars($data['telp']) ?>" required>

        <label>Alamat</label>
        <textarea name="alamat" required><?= htmlspecialchars($data['alamat']) ?></textarea>

        <button type="submit" name="simpan" class="tombol simpan">
            Simpan Perubahan
        </button>

        <a href="index.php" class="tombol batal">Batal</a>
    </form>
</div>

</body>
</html>