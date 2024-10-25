<?php
$server = "localhost";
$database = "db_dao_thu_huong";
$username = "root";
$password = "";

// Kết nối đến cơ sở dữ liệu
$conn = new mysqli($server, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die('Kết nối không thành công: ' . $conn->connect_error);
}

mysqli_set_charset($conn, 'utf8mb4');
?>
