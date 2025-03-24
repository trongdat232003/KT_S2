<?php
// Kết nối CSDL
require 'config.php';

// Include header
include 'views/header.php';

// Xử lý xóa sinh viên
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM SinhVien WHERE MaSV = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

// Lấy danh sách sinh viên
$stmt = $conn->query("SELECT * FROM SinhVien");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="text-center">Danh Sách Sinh Viên</h1>
    <a class="btn btn-primary mb-3" href="create.php">Thêm Sinh Viên</a>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Mã SV</th>
                <th>Họ Tên</th>
                <th>Giới Tính</th>
                <th>Ngày Sinh</th>
                <th>Hình Ảnh</th>
                <th>Ngành</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['MaSV']) ?></td>
                <td><?= htmlspecialchars($student['HoTen']) ?></td>
                <td><?= htmlspecialchars($student['GioiTinh']) ?></td>
                <td><?= htmlspecialchars($student['NgaySinh']) ?></td>
                <td><img src="/KT_S2/<?= htmlspecialchars($student['Hinh']) ?>" width="100" height="100" alt="Hình sinh viên"></td>
                <td><?= htmlspecialchars($student['MaNganh']) ?></td>
                <td>
                    <a class="btn btn-warning btn-sm" href="edit.php?id=<?= htmlspecialchars($student['MaSV']) ?>">Sửa</a>
                    <a class="btn btn-info btn-sm" href="detail.php?id=<?= htmlspecialchars($student['MaSV']) ?>">Chi tiết</a>
                    <a class="btn btn-danger btn-sm" href="delete.php?id=<?= htmlspecialchars($student['MaSV']) ?>">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'views/footer.php'; ?>