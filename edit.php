<?php
// Kết nối CSDL
require 'config.php';
// Include header
include 'views/header.php';
// Lấy thông tin sinh viên cần sửa
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ?");
$stmt->execute([$id]);
$sinhvien = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sinhvien) {
    die("Không tìm thấy sinh viên!");
}

// Xử lý cập nhật thông tin sinh viên
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hoten = $_POST['HoTen'];
    $gioitinh = $_POST['GioiTinh'];
    $ngaysinh = $_POST['NgaySinh'];
    $manganh = $_POST['MaNganh'];

    // Xử lý upload ảnh mới
    $hinh = $sinhvien['Hinh']; // Mặc định giữ ảnh cũ
    if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
        $target_dir = "Content/images/";
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['Hinh']['type'];
        $fileSize = $_FILES['Hinh']['size'];

        if (in_array($fileType, $allowedTypes) && $fileSize <= 2 * 1024 * 1024) {
            $extension = pathinfo($_FILES['Hinh']['name'], PATHINFO_EXTENSION);
            $newFileName = $id . '_' . time() . '.' . $extension;
            $target_file = $target_dir . $newFileName;

            if (move_uploaded_file($_FILES['Hinh']['tmp_name'], $target_file)) {
                // Xóa ảnh cũ nếu có
                if (!empty($sinhvien['Hinh']) && file_exists($sinhvien['Hinh'])) {
                    unlink($sinhvien['Hinh']);
                }
                $hinh = $target_file;
            } else {
                echo "<script>alert('Lỗi khi tải ảnh lên!'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Chỉ chấp nhận file JPG, PNG, GIF và dung lượng tối đa 2MB!'); window.history.back();</script>";
            exit;
        }
    }

    // Cập nhật sinh viên trong CSDL
    $stmt = $conn->prepare("UPDATE SinhVien SET HoTen=?, GioiTinh=?, NgaySinh=?, Hinh=?, MaNganh=? WHERE MaSV=?");
    $stmt->execute([$hoten, $gioitinh, $ngaysinh, $hinh, $manganh, $id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>CHỈNH SỬA SINH VIÊN</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">CHỈNH SỬA SINH VIÊN</h1>
        <form method="post" enctype="multipart/form-data" class="col-md-6 mx-auto border p-4 shadow">
            <div class="form-group">
                <label>Họ Tên</label>
                <input type="text" name="HoTen" class="form-control" value="<?= $sinhvien['HoTen'] ?>" required>
            </div>
            <div class="form-group">
                <label>Giới Tính</label>
                <select name="GioiTinh" class="form-control" required>
                    <option value="Nam" <?= $sinhvien['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ" <?= $sinhvien['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ngày Sinh</label>
                <input type="date" name="NgaySinh" class="form-control" value="<?= $sinhvien['NgaySinh'] ?>" required>
            </div>
            <div class="form-group">
                <label>Hình</label>
                <input type="file" name="Hinh" class="form-control">
                <br>
                <img src="<?= $sinhvien['Hinh'] ?>" width="150" />
            </div>
            <div class="form-group">
                <label>Ngành</label>
                <input type="text" name="MaNganh" class="form-control" value="<?= $sinhvien['MaNganh'] ?>" required>
            </div>
            <button type="submit" class="btn btn-success btn-block">Lưu Thay Đổi</button>
            <a href="index.php" class="btn btn-secondary btn-block">Back to List</a>
        </form>
    </div>
</body>

</html>
<?php include 'views/footer.php'; ?>