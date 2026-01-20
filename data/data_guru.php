<?php
session_start();
require_once "../config/database.php";

if ($_SESSION['role'] !== 'admin') {
    header("Location: /absensi/dashboard.php");
    exit;
}
$active_menu = 'data_guru';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include "../layout/sidebar.php"; ?>

<div class="main">
    <h2>Data Guru</h2>
</div>

</body>
</html>
