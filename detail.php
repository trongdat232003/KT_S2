<?php
require 'config.php';

// Lấy thông tin sinh viên
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ?");
$stmt->execute([$id]);
$sinhvien = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sinhvien) {
    die("Không tìm thấy sinh viên!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thông tin chi tiết sinh viên</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="mt-3">Thông tin chi tiết</h1>
        <ul>
            <li><strong>Họ Tên:</strong> <?= $sinhvien['HoTen'] ?></li>
            <li><strong>Giới Tính:</strong> <?= $sinhvien['GioiTinh'] ?></li>
            <li><strong>Ngày Sinh:</strong> <?= $sinhvien['NgaySinh'] ?></li>
            <li><strong>Hình:</strong> <img src="/KT_S2/<?= $sinhvien['Hinh'] ?>" width="150" /></li>
            <li><strong>Ngành:</strong> <?= $sinhvien['MaNganh'] ?></li>
        </ul>
        <a href="/KT_S2/index.php" class="btn btn-secondary">Back to List</a>
    </div>
</body>
</html>
