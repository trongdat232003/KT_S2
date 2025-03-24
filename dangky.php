<?php
session_start();
require 'config.php';
require 'views/header.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['sinhvien'])) {
    echo "<script>alert('Bạn chưa đăng nhập!'); window.location.href='login.php';</script>";
    exit();
}

$sinhVien = $_SESSION['sinhvien'];
$maSV = $sinhVien['MaSV'];

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Thêm học phần vào giỏ hàng
if (isset($_GET['MaHP'])) {
    $maHP = $_GET['MaHP'];
    $stmt = $conn->prepare("SELECT * FROM HocPhan WHERE MaHP = ?");
    $stmt->execute([$maHP]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($course && !in_array($maHP, array_column($_SESSION['cart'], 'MaHP'))) {
        $_SESSION['cart'][] = $course;
    }
}

// Xóa học phần khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($hp) => $hp['MaHP'] !== $_GET['remove']);
}

// Xóa toàn bộ giỏ hàng
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
}

// Lưu thông tin đăng ký vào CSDL
if (isset($_POST['confirm_save']) && !empty($_SESSION['cart'])) {
    $ngayDK = date('Y-m-d');
    $conn->beginTransaction();

    try {
        $stmt = $conn->prepare("INSERT INTO DangKy (NgayDK, MaSV) VALUES (?, ?)");
        $stmt->execute([$ngayDK, $maSV]);
        $maDK = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)");
        foreach ($_SESSION['cart'] as $course) {
            $stmt->execute([$maDK, $course['MaHP']]);
        }

        $conn->commit();
        $_SESSION['cart'] = [];
        echo "<script>alert('Đăng ký thành công!'); window.location.href='dangky.php';</script>";
    } catch (Exception $e) {
        $conn->rollBack();
        echo "<script>alert('Lỗi khi lưu đăng ký: {$e->getMessage()}');</script>";
    }
}

$totalCredits = array_sum(array_column($_SESSION['cart'], 'SoTinChi'));
?>

<div class="container mt-4">
    <h1 class="text-center">Đăng Ký Học Phần</h1>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Mã HP</th>
                <th>Tên Học Phần</th>
                <th>Số Tín Chỉ</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $course): ?>
            <tr>
                <td><?= htmlspecialchars($course['MaHP']) ?></td>
                <td><?= htmlspecialchars($course['TenHP']) ?></td>
                <td><?= htmlspecialchars($course['SoTinChi']) ?></td>
                <td><a href="dangky.php?remove=<?= $course['MaHP'] ?>" class="btn btn-danger btn-sm">Xóa</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Số học phần:</strong> <?= count($_SESSION['cart']) ?></p>
    <p><strong>Tổng số tín chỉ:</strong> <?= $totalCredits ?></p>

    <a href="dangky.php?clear=1" class="btn btn-warning">Xóa Đăng Ký</a>
    <button class="btn btn-success" onclick="showConfirmModal()">Lưu Đăng Ký</button>
</div>

<!-- Modal xác nhận -->
<div id="confirmModal" style="display:none;">
    <h2 class="text-center">Thông tin Đăng kí</h2>
    <p><strong>Mã số sinh viên:</strong> <?= htmlspecialchars($sinhVien['MaSV']) ?></p>
    <p><strong>Họ tên:</strong> <?= htmlspecialchars($sinhVien['HoTen']) ?></p>
    <p><strong>Ngày sinh:</strong> <?= htmlspecialchars($sinhVien['NgaySinh']) ?></p>
    <p><strong>Ngành học:</strong> <?= htmlspecialchars($sinhVien['MaNganh']) ?></p>
    <p><strong>Ngày đăng ký:</strong> <?= date('d/m/Y') ?></p>
    <form method="post">
        <button type="submit" name="confirm_save" class="btn btn-primary">Xác Nhận</button>
        <button type="button" onclick="hideConfirmModal()" class="btn btn-secondary">Hủy</button>
    </form>
</div>

<script>
function showConfirmModal() {
    document.getElementById('confirmModal').style.display = 'block';
}

function hideConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
}
</script>

<?php require 'views/footer.php'; ?>
