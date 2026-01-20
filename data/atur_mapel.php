<?php
session_start();
require_once "../config/database.php";

$id_kelas = $_GET['id'];

// Ambil info kelas
$q_kelas = mysqli_query($conn, "SELECT * FROM kelas WHERE id_kelas = '$id_kelas'");
$d_kelas = mysqli_fetch_assoc($q_kelas);

// Logic Tambah Mapel
if (isset($_POST['tambah_mapel'])) {
    $nama_mapel = htmlspecialchars($_POST['nama_mapel']);
    mysqli_query($conn, "INSERT INTO mapel (id_kelas, nama_mapel) VALUES ('$id_kelas', '$nama_mapel')");
    header("Location: atur_mapel.php?id=$id_kelas");
    exit;
}

// Logic Hapus Mapel
if (isset($_GET['hapus_mapel'])) {
    $id_mapel = $_GET['hapus_mapel'];
    mysqli_query($conn, "DELETE FROM mapel WHERE id_mapel = '$id_mapel'");
    header("Location: atur_mapel.php?id=$id_kelas");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Atur Mapel</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/data.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>
    <?php include "../layout/sidebar.php"; ?>
    <div class="main">
        <a href="data_kelas.php" style="text-decoration:none; color:black;">&larr; Kembali</a>
        <h2>Mata Pelajaran: <?= $d_kelas['nama_kelas']; ?></h2>

        <div class="content-wrapper">
            <form action="" method="POST" style="margin-bottom:30px; display:flex; gap:10px;">
                <input type="text" name="nama_mapel" class="input-text" placeholder="Nama Mata Pelajaran..." required>
                <button type="submit" name="tambah_mapel" class="btn-simpan">Tambah Mapel</button>
            </form>

            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap:15px;">
                <?php
                $q_mapel = mysqli_query($conn, "SELECT * FROM mapel WHERE id_kelas = '$id_kelas'");
                if(mysqli_num_rows($q_mapel) > 0) {
                    while ($mapel = mysqli_fetch_assoc($q_mapel)) {
                ?>
                    <div style="background:#f8f9fa; border:1px solid #ddd; padding:20px; border-radius:8px; position:relative;">
                        <h3 style="margin:0; font-size:16px;"><?= $mapel['nama_mapel']; ?></h3>
                        
                        <a href="atur_mapel.php?id=<?= $id_kelas; ?>&hapus_mapel=<?= $mapel['id_mapel']; ?>" 
                           style="color:red; text-decoration:none; position:absolute; top:10px; right:10px; font-weight:bold;"
                           onclick="return confirm('Hapus mapel ini?')">x</a>
                    </div>
                <?php 
                    } 
                } else {
                    echo "<p style='color:#777;'>Belum ada mapel di kelas ini.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>