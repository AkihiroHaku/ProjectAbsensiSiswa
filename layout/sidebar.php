<?php
$role = $_SESSION['role'];
?>

<div class="sidebar">
    <img src="/absensi/assets/img/smkislam.png" width="140" alt="SMK Islam Salakbrojo Logo">

    <ul>
        <li><a href="/absensi/dashboard.php">Dashboard</a></li>

        <?php if ($role == 'admin'): ?>
            <li><a href="/absensi/data/data_guru.php">Data Guru</a></li>
            <li><a href="/absensi/data/data_siswa.php">Data Siswa</a></li>
            <li><a href="/absensi/data/data_kelas.php">Data Kelas</a></li>
        <?php endif; ?>

        <?php if ($role == 'guru'): ?>
            <li><a href="/absensi/absen/absen_guru.php">Absensi</a></li>
            <li><a href="/absensi/absen/absen_siswa.php">Absensi Siswa</a></li>
        <?php endif; ?>

        <li><a href="/absensi/auth/logout.php">Logout</a></li>
    </ul>
</div>
