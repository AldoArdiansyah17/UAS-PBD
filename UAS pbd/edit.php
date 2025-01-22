<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Mendapatkan data berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM kelas WHERE id = $id");
    $row = $result->fetch_assoc();
}

// Menyimpan perubahan nilai
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nilai = $_POST['nilai'];
    $conn->query("UPDATE kelas SET nilai='$nilai' WHERE id=$id");
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nilai - <?= htmlspecialchars($row['mahasiswa']) ?></title>
    <link rel="stylesheet" href="styleEdit.css">
</head>
<body>

<div class="container">
    <h1>Edit Nilai - <?= htmlspecialchars($row['mahasiswa']) ?></h1>
    <a href="dashboard.php" class="btn-back">Kembali ke Dashboard</a>

    <h3>Form Edit Nilai</h3>
    <form method="post">
        <label for="nilai">Nilai: </label>
        <input type="number" step="0.01" name="nilai" value="<?= htmlspecialchars($row['nilai']) ?>" required><br>
        <button type="submit">Simpan</button>
    </form>
</div>

</body>
</html>
