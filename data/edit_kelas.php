<?php
session_start();
require_once "../config/database.php";

// Pastikan ada ID yang dikirim
if (!isset($_GET['id'])) {
    header("Location: data_kelas.php");
    exit;
}

$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM kelas WHERE id_kelas='$id'");
$data = mysqli_fetch_assoc($q);

// Logic Update
if (isset($_POST['update'])) {
    $tingkat = $_POST['tingkat'];
    $jurusan = htmlspecialchars($_POST['jurusan']);
    $nama = htmlspecialchars($_POST['nama_kelas']);

    mysqli_query($conn, "UPDATE kelas SET tingkat='$tingkat', jurusan='$jurusan', nama_kelas='$nama' WHERE id_kelas='$id'");
    
    // Setelah update, balik ke halaman tabel
    header("Location: data_kelas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kelas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/data.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>
    <?php include "../layout/sidebar.php"; ?>

    <div class="main">
        <h2>Edit Data Kelas</h2>
        <div class="content-wrapper">
            <form action="" method="POST">
                <div class="form-input" style="display:block;">
                    
                    <label>Tingkat:</label>
                    <select name="tingkat" class="input-text">
                        <option value="10" <?= ($data['tingkat'] == '10') ? 'selected' : '' ?>>Kelas 10</option>
                        <option value="11" <?= ($data['tingkat'] == '11') ? 'selected' : '' ?>>Kelas 11</option>
                        <option value="12" <?= ($data['tingkat'] == '12') ? 'selected' : '' ?>>Kelas 12</option>
                    </select>
                    <br><br>
                    
                    <label>Jurusan:</label>
                    <input type="text" name="jurusan" class="input-text" value="<?= $data['jurusan']; ?>">
                    <br><br>

                    <label>Nama Kelas:</label>
                    <input type="text" name="nama_kelas" class="input-text" value="<?= $data['nama_kelas']; ?>">
                    <br><br>

                    <button type="submit" name="update" class="btn-simpan">Update Data</button>
                    <a href="data_kelas.php" class="btn-hapus" style="background:gray;">Batal</a>
                    
                </div>
            </form>
        </div>
    </div>
</body>
</html>