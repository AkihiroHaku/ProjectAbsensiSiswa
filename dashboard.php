<?php
session_start();

// Cegah akses tanpa login
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$role = $_SESSION['role']; // admin / guru / siswa
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="sidebar">
    <h2>ABSENSI</h2>
    <ul>
        <li class="active">Dashboard</li>

        <?php if ($role == 'admin') : ?>
            <li>Data User</li>
            <li>Data Kelas</li>
        <?php endif; ?>

        <?php if ($role == 'guru') : ?>
            <li>Data Siswa</li>
            <li>Absensi</li>
        <?php endif; ?>

        <?php if ($role == 'siswa') : ?>
            <li>Absensi Saya</li>
        <?php endif; ?>

        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main">
    <h1>Selamat Datang (<?= ucfirst($role); ?>)</h1>
</div>

<script src="js/script.js"></script>
</body>
</html>
