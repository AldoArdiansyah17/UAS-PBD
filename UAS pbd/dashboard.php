<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Menambahkan nilai baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $mahasiswa = $_POST['mahasiswa'];
    $mata_kuliah = $_POST['mata_kuliah'];
    $nilai = $_POST['nilai'];

    $sql = "INSERT INTO kelas (mahasiswa, mata_kuliah, nilai) VALUES ('$mahasiswa', '$mata_kuliah', '$nilai')";
    $conn->query($sql);
}

// Menghapus seluruh baris nilai
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM kelas WHERE id=$id");
    header("Location: dashboard.php");
    exit;
}

// Mengambil data nilai
$result = $conn->query("SELECT * FROM kelas ORDER BY mahasiswa, mata_kuliah");

// Daftar Mata Kuliah
$mata_kuliah_list = [
    'Struktur Data',
    'Bahasa Indonesia',
    'Succes Skill',
    'Rekayasa Perangkat Lunak',
    'Pengolahan Basis Data',
    'Back End Development',
    'Human Computer Interaction',
    'Desain Grafis'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <link rel="stylesheet" href="styleDAS.css">
</head>
<body>

<div class="container">
    <h1>Dashboard</h1>
    <h2>Welcome, <?= $_SESSION['username'] ?></h2>
    <a href="logout.php" class="logout-btn">Logout</a>

    <form method="post">
        Mahasiswa: <input type="text" name="mahasiswa" required><br>
        Mata Kuliah: 
        <select name="mata_kuliah" required>
            <?php foreach ($mata_kuliah_list as $mata_kuliah): ?>
                <option value="<?= htmlspecialchars($mata_kuliah) ?>"><?= htmlspecialchars($mata_kuliah) ?></option>
            <?php endforeach; ?>
        </select><br>
        Nilai: <input type="number" step="0.01" name="nilai" required><br>
        <button type="submit" name="add">Tambah</button>
    </form>
    
    <h3>Daftar Nilai Mahasiswa</h3>
    <table border="1">
        <tr>
            <th>Mahasiswa</th>
            <th>Mata Kuliah</th>
            <th>Nilai</th>
            <th>Action</th>
        </tr>
        
        <?php 
        $current_mahasiswa = null;
        while ($row = $result->fetch_assoc()): 
        ?>
            <tr>
                <?php if ($row['mahasiswa'] !== $current_mahasiswa): ?>
                    <td rowspan="<?= $conn->query("SELECT COUNT(*) AS count FROM kelas WHERE mahasiswa='{$row['mahasiswa']}'")->fetch_assoc()['count'] ?>">
                        <?= htmlspecialchars($row['mahasiswa']) ?>
                    </td>
                    <?php $current_mahasiswa = $row['mahasiswa']; ?>
                <?php endif; ?>
                
                <td><?= htmlspecialchars($row['mata_kuliah']) ?></td>
                <td><?= htmlspecialchars($row['nilai']) ?></td>
                <td>
                    <a href="view.php?id=<?= $row['id'] ?>">View</a> | 
                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> | 
                    <a href="dashboard.php?delete=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
