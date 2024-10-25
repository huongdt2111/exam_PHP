<?php
// Thông tin kết nối đến cơ sở dữ liệu
$server = "localhost"; // Địa chỉ máy chủ cơ sở dữ liệu
$database = "db_dao_thu_huong"; // Tên cơ sở dữ liệu
$username = "root"; // Tên đăng nhập cơ sở dữ liệu
$password = ""; // Mật khẩu (nếu có)

// Kết nối đến cơ sở dữ liệu
$conn = new mysqli($server, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die('Kết nối không thành công: ' . $conn->connect_error);
}

mysqli_set_charset($conn, 'utf8mb4');
?>
