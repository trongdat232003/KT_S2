<?php
require 'config.php';
require 'views/header.php';

// Lấy danh sách học phần
$stmt = $conn->query("SELECT * FROM HocPhan");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-4">
    <h1 class="text-center">DANH SÁCH HỌC PHẦN</h1>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Mã Học Phần</th>
                <th>Tên Học Phần</th>
                <th>Số Tín Chỉ</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td><?= $course['MaHP'] ?></td>
                <td><?= $course['TenHP'] ?></td>
                <td><?= $course['SoTinChi'] ?></td>
                <td>
                    <a href="dangky.php?MaHP=<?= $course['MaHP'] ?>" class="btn btn-success btn-sm">Đăng Ký</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require 'views/footer.php'; ?>
