<?php
session_start();
require_once "../config/database.php";

// Cek Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}
$active_menu = 'data_kelas';

// --- LOGIC TAMBAH DATA ---
if (isset($_POST['simpan'])) {
    $tingkat = $_POST['tingkat'];
    $jurusan = htmlspecialchars($_POST['jurusan']);
    $nama_kelas = htmlspecialchars($_POST['nama_kelas']);

    mysqli_query($conn, "INSERT INTO kelas (tingkat, jurusan, nama_kelas) VALUES ('$tingkat', '$jurusan', '$nama_kelas')");
    header("Location: data_kelas.php");
    exit;
}

// --- LOGIC HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kelas WHERE id_kelas = '$id'");
    header("Location: data_kelas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Kelas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/data.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>
    <?php include "../layout/sidebar.php"; ?>

    <div class="main">
        <h2>Manajemen Data Kelas</h2>
        <div class="content-wrapper">
            <h3>Tambah Kelas Baru</h3>
            <form action="" method="POST">
                <div class="form-input" style="display:block;">
                    <label>Tingkat:</label>
                    <select name="tingkat" class="input-text" required>
                        <option value="">-- Pilih --</option>
                        <option value="10">Kelas 10</option>
                        <option value="11">Kelas 11</option>
                        <option value="12">Kelas 12</option>
                    </select>
                    <br><br>
                    <label>Jurusan:</label>
                    <input type="text" name="jurusan" class="input-text" placeholder="Contoh: RPL" required>
                    <br><br>
                    <label>Nama Kelas:</label>
                    <input type="text" name="nama_kelas" class="input-text" placeholder="Contoh: X RPL 1" required>
                    <br><br>
                    <button type="submit" name="simpan" class="btn-simpan">Simpan Data</button>
                </div>
            </form>

            <hr style="border:0; border-top:1px solid #eee; margin:20px 0;">

            <h3>Daftar Kelas</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Nama Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $q = mysqli_query($conn, "SELECT * FROM kelas ORDER BY tingkat ASC, jurusan ASC");
                    while ($d = mysqli_fetch_assoc($q)) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>Kelas <?= $d['tingkat']; ?></td>
                        <td><?= $d['jurusan']; ?></td>
                        <td><?= $d['nama_kelas']; ?></td>
                        <td>
                            <a href="atur_mapel.php?id=<?= $d['id_kelas']; ?>" class="btn-warning">Mapel</a>
                            <a href="edit_kelas.php?id=<?= $d['id_kelas']; ?>" class="btn-edit">Edit</a>
                            <a href="data_kelas.php?hapus=<?= $d['id_kelas']; ?>" class="btn-hapus">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>