<?php
require 'config.php';
require 'views/header.php';

session_start(); // Đảm bảo khởi động session

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $masv = trim($_POST['MaSV']);
    
    $stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ?");
    $stmt->execute([$masv]);
    $sinhvien = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($sinhvien) {
        $_SESSION['sinhvien'] = [
            'MaSV' => $sinhvien['MaSV'],
            'HoTen' => $sinhvien['HoTen'],
            'NgaySinh' => $sinhvien['NgaySinh'],
            'MaNganh' => $sinhvien['MaNganh']
        ];
        header("Location: index.php");
        exit;
    } else {
        $error = "Mã sinh viên không đúng!";
    }
}
?>

<div class="container mt-4">
    <h1 class="text-center">ĐĂNG NHẬP</h1>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"> <?= $error ?> </div>
    <?php endif; ?>
    <form method="post" action="login.php">
        <div class="mb-3">
            <label class="form-label">Mã Sinh Viên</label>
            <input type="text" name="MaSV" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
    </form>
</div>

<?php require 'views/footer.php'; ?>
