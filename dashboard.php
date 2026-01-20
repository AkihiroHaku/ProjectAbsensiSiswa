<?php
session_start();
require_once "config/database.php";

// Cek Login
if (!isset($_SESSION['role'])) {
    header("Location: auth/login.php");
    exit;
}

$active_menu = 'dashboard';

// --- LOGIC MENGHITUNG STATISTIK ---

// 1. Hitung Total Siswa (Gabungan dari Kelas 10, 11, 12)
// Kita pakai try-catch atau logika sederhana biar kalau tabelnya belum ada isinya tidak error fatal
$jml_siswa = 0;

// Cek Siswa Kelas 10
$q10 = mysqli_query($conn, "SELECT COUNT(*) as jum FROM siswa_kelas10");
$d10 = mysqli_fetch_assoc($q10);
$jml_siswa += $d10['jum']; // Tambahkan ke total

// Cek Siswa Kelas 11
$q11 = mysqli_query($conn, "SELECT COUNT(*) as jum FROM siswa_kelas11");
$d11 = mysqli_fetch_assoc($q11);
$jml_siswa += $d11['jum']; // Tambahkan ke total

// Cek Siswa Kelas 12
$q12 = mysqli_query($conn, "SELECT COUNT(*) as jum FROM siswa_kelas12");
$d12 = mysqli_fetch_assoc($q12);
$jml_siswa += $d12['jum']; // Tambahkan ke total


// 2. Hitung Guru
$query_guru = mysqli_query($conn, "SELECT COUNT(*) as jum FROM guru");
$data_guru  = mysqli_fetch_assoc($query_guru);
$jml_guru   = $data_guru['jum'];


// 3. Hitung Kelas
$query_kelas = mysqli_query($conn, "SELECT COUNT(*) as jum FROM kelas");
$data_kelas  = mysqli_fetch_assoc($query_kelas);
$jml_kelas   = $data_kelas['jum'];


// 4. Hitung Mapel
$query_mapel = mysqli_query($conn, "SELECT COUNT(*) as jum FROM mapel");
$data_mapel  = mysqli_fetch_assoc($query_mapel);
$jml_mapel   = $data_mapel['jum'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Absensi</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>

    <?php include "layout/sidebar.php"; ?>

    <div class="main">
        
        <div class="dashboard-header">
            <h2>Dashboard</h2>
            <p style="color:#666; margin-top:5px;">
                Selamat Datang di Sistem Absensi Sekolah. Berikut adalah ringkasan data saat ini.
            </p>
        </div>

        <div class="dashboard-cards">
            
            <div class="card-box bg-blue">
                <div class="card-count"><?= $jml_siswa; ?></div>
                <div class="card-title">Total Siswa</div>
            </div>

            <div class="card-box bg-green">
                <div class="card-count"><?= $jml_guru; ?></div>
                <div class="card-title">Total Guru</div>
            </div>

            <div class="card-box bg-orange">
                <div class="card-count"><?= $jml_kelas; ?></div>
                <div class="card-title">Total Kelas</div>
            </div>

            <div class="card-box bg-red">
                <div class="card-count"><?= $jml_mapel; ?></div>
                <div class="card-title">Total Mapel</div>
            </div>

        </div>

    </div>

</body>
</html>