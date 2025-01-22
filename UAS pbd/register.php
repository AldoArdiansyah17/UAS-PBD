<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitasi input untuk menghindari SQL Injection
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Cek apakah username sudah ada
    $sql = "SELECT * FROM mahasiswa WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "Username sudah terdaftar.";
    } else {
        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data pengguna baru ke database
        $sql = "INSERT INTO mahasiswa (username, password) VALUES ('$username', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $username;
            header("Location: login.php");
            exit();
        } else {
            $error = "Terjadi kesalahan. Coba lagi nanti.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styleREGIS.css">
</head>
<body>

<div class="container">
    <h1>Daftar Akun</h1>

    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

    <form action="register.php" method="post">
        <input type="text" name="username" placeholder="Nama Pengguna" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar</button>
    </form>

    <a href="login.php">Sudah punya akun? Login di sini</a>
</div>

</body>
</html>
