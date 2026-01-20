<?php
require_once "../config/database.php";

$username = "faruk";
$password = "faruk";   // password asli
$id_role  = 2;         // 1=admin, 2=guru, 3=siswa

// HASH PASSWORD
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// SIMPAN KE DATABASE
$query = "INSERT INTO users (username, password, id_role)
          VALUES ('$username', '$password_hash', $id_role)";

if (mysqli_query($conn, $query)) {
    echo "Akun berhasil dibuat";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
