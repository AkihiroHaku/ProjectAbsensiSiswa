<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../auth/login.php");
    exit;
}
date_default_timezone_set('Asia/Jakarta');
$tgl_indo = date('d F Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/teach.css">
</head>
<body>
    <div class="container">
        
        <header>
            <div class="header-info">
                <i class="fas fa-user-graduate"></i>
                <div class="header-text">
                    <h1>Sistem Absensi Siswa</h1>
                    <p>SMK Islam Salakbrojo</p>
                </div>
            </div>
            <div class="date-display">
                <?= $tgl_indo; ?>
            </div>
        </header>

        <div class="tabs">
            <a href="index.php" class="tab <?= ($active_tab == 'dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="absensi.php" class="tab <?= ($active_tab == 'absensi') ? 'active' : ''; ?>">
                <i class="fas fa-clipboard-check"></i> Absensi Harian
            </a>
            <a href="siswa.php" class="tab <?= ($active_tab == 'siswa') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Data Siswa
            </a>
            <a href="laporan.php" class="tab <?= ($active_tab == 'laporan') ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i> Laporan
            </a>
        </div>