<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Mengecek apakah parameter id ada di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    // Mengambil data nilai berdasarkan ID
    $sql = "SELECT * FROM kelas WHERE id = $id";
    $result = $conn->query($sql);

    // Jika data ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $mahasiswa = $row['mahasiswa']; // Mengambil nama mahasiswa untuk ditampilkan di header
        // Query untuk mengambil semua nilai mahasiswa dengan ID yang sama
        $sqlAll = "SELECT * FROM kelas WHERE mahasiswa = '$mahasiswa'";
        $resultAll = $conn->query($sqlAll);
    } else {
        echo "Data nilai tidak ditemukan.";
        exit;
    }
} else {
    // Jika ID tidak diberikan atau tidak valid
    echo "ID tidak diberikan atau tidak valid.";
    exit;
}
// Menghapus atau merubah nilai menjadi 0
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "UPDATE kelas SET nilai = 0 WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        // Mengambil id mahasiswa untuk redirect
        $sqlMahasiswa = "SELECT mahasiswa FROM kelas WHERE id = $id LIMIT 1";
        $resultMahasiswa = $conn->query($sqlMahasiswa);
        if ($resultMahasiswa->num_rows > 0) {
            $row = $resultMahasiswa->fetch_assoc();
            $mahasiswa = $row['mahasiswa'];
            echo "<script>alert('Nilai berhasil dihapus.'); window.location.href='view.php?id=$id';</script>";
        }
    } else {
        echo "Error: " . $conn->error;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Nilai - <?= htmlspecialchars($mahasiswa) ?></title>
    <link rel="stylesheet" href="styleView.css">
</head>
<body>

<div class="container">
    <h1>Data Nilai Mahasiswa - <?= htmlspecialchars($mahasiswa) ?></h1>
    <a href="dashboard.php" class="btn-back">Kembali ke Dashboard</a>

    <h3>Detail Nilai</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Mata Kuliah</th>
                <th>Nilai</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop untuk menampilkan semua mata kuliah dan nilai yang dimiliki mahasiswa
            while ($row = $resultAll->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['mata_kuliah']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nilai']) . "</td>";
                echo "<td>
                        <a href='view.php?id=$id&delete=" . $row['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus nilai ini?\")'>Delete</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
