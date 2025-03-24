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

// Xử lý xóa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("DELETE FROM SinhVien WHERE MaSV = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Xóa thông tin sinh viên</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="mt-3 text-danger">XÓA THÔNG TIN</h1>
        <p>Are you sure you want to delete this?</p>
        <ul>
            <li><strong>Họ Tên:</strong> <?= $sinhvien['HoTen'] ?></li>
            <li><strong>Giới Tính:</strong> <?= $sinhvien['GioiTinh'] ?></li>
            <li><strong>Ngày Sinh:</strong> <?= $sinhvien['NgaySinh'] ?></li>
            <li><strong>Hình:</strong> <img src="<?= $sinhvien['Hinh'] ?>" width="150" /></li>
        </ul>
        <form method="post">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="/KT_S2/index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
