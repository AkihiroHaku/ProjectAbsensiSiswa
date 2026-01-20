<?php
session_start();
require_once "../config/database.php";

if (isset($_SESSION['login'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'guru') {
        header("Location: /absensi/dashboard_guru.php");
    } else {
        header("Location: /absensi/dashboard.php");
    }
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT users.*, roles.nama_role 
              FROM users 
              JOIN roles ON users.id_role = roles.id_role
              WHERE username = '$username'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            
            // Set Session
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['nama_role']; // Pastikan di database namanya 'admin' atau 'guru'

            if ($user['nama_role'] == 'guru') {
                header("Location: ../guru/index.php");
            } else {
                header("Location: /absensi/dashboard.php");
            }
            exit;

        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Absensi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>

    <div class="login-container">
        <div class="login-card">

            <img src="../assets/img/smkislam.png" width="130" alt="SMK Islam Salakbrojo Logo">

            <h2>SMK Islam Salakbrojo</h2>
            <p>Sistem Informasi Absensi Siswa</p>
                <p> SMK Islam Salakbrojo
            </p>

            <?php if ($error): ?>
                <div id="error-message" class="error-box">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-icon">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="username" placeholder="Masukan Username" required>
                </div>
                <div class="input-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Masukan Password" required>
                </div>
                <button class="btn-login" type="submit">Masuk</button>
            </form>

        </div>
    </div>
    <script src="../js/script.js"></script>
</body>

</html>