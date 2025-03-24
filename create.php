<?php
// Kết nối CSDL
require 'config.php';
// Include header
include 'views/header.php';
// Xử lý thêm sinh viên
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $masv = $_POST['MaSV'];
    $hoten = $_POST['HoTen'];
    $gioitinh = $_POST['GioiTinh'];
    $ngaysinh = $_POST['NgaySinh'];
    $manganh = $_POST['MaNganh'];

// Xử lý upload ảnh
$hinh = '';
if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
    $target_dir = "Content/images/";
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = $_FILES['Hinh']['type'];
    $fileSize = $_FILES['Hinh']['size'];

    // Kiểm tra định dạng và dung lượng ảnh (tối đa 2MB)
    if (in_array($fileType, $allowedTypes) && $fileSize <= 2 * 1024 * 1024) {
        $extension = pathinfo($_FILES['Hinh']['name'], PATHINFO_EXTENSION);

        // Đổi tên file thành MaSV + timestamp để tránh trùng
        $newFileName = $masv . '_' . time() . '.' . $extension;
        $target_file = $target_dir . $newFileName;

        // Di chuyển file vào thư mục
        if (move_uploaded_file($_FILES['Hinh']['tmp_name'], $target_file)) {
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


    // Thêm sinh viên vào CSDL
    $stmt = $conn->prepare("INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$masv, $hoten, $gioitinh, $ngaysinh, $hinh, $manganh]);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>THÊM SINH VIÊN</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Form Thêm sinh viên -->
    <div class="container mt-5">
        <h1 class="mb-4">THÊM SINH VIÊN</h1>
        <form method="post" enctype="multipart/form-data" class="col-md-6 mx-auto border p-4 shadow">
            <div class="form-group">
                <label>Mã SV</label>
                <input type="text" name="MaSV" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Họ Tên</label>
                <input type="text" name="HoTen" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Giới Tính</label>
                <select name="GioiTinh" class="form-control" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ngày Sinh</label>
                <input type="date" name="NgaySinh" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Hình</label>
                <input type="file" name="Hinh" class="form-control">
            </div>
            <div class="form-group">
                <label>Ngành</label>
                <input type="text" name="MaNganh" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success btn-block">Create</button>
            <a href="index.php" class="btn btn-secondary btn-block">Back to List</a>
        </form>
    </div>

</body>

</html>
<?php include 'views/footer.php'; ?>