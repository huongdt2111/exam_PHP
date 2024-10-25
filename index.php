<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">PHP Example</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container my-3">
        <nav class="alert alert-primary" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </nav>

        <form class="row" method="POST" enctype="multipart/form-data">
            <div class="col">
                <div class="mb-3">
                    <input type="file" accept=".txt" class="form-control" name="file">
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Import database</button>
            </div>
            <div class="col">
            </div>
        </form>

        <?php
        require 'connect.php'; // Bao gồm file kết nối cơ sở dữ liệu

        if (isset($_POST['submit'])) {
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                // Đường dẫn file tạm
                $fileTmpPath = $_FILES['file']['tmp_name'];
                $fileType = $_FILES['file']['type'];

                // Kiểm tra định dạng file
                if ($fileType == 'text/plain') {
                    $successCount = 0;
                    $failureCount = 0;

                    // Mở file và đọc từng dòng
                    $file = fopen($fileTmpPath, "r");

                    while (($line = fgets($file)) !== false) {
                        // Tách dòng thành các giá trị dựa trên dấu phẩy
                        $data = str_getcsv($line);
                        if (count($data) == 3) {
                            $title = $conn->real_escape_string(trim($data[0])); // Trim để loại bỏ khoảng trắng
                            $description = $conn->real_escape_string(trim($data[1])); // Trim để loại bỏ khoảng trắng
                            $imageUrl = $conn->real_escape_string(trim($data[2])); // Trim để loại bỏ khoảng trắng

                            // Kiểm tra xem tiêu đề đã tồn tại chưa
                            $checkQuery = "SELECT * FROM course WHERE Title = '$title'";
                            $checkResult = $conn->query($checkQuery);

                            if ($checkResult->num_rows == 0) {
                                // Chèn dữ liệu vào database nếu chưa tồn tại
                                $insertQuery = "INSERT INTO course (Title, Description, imageUrl) VALUES ('$title', '$description', '$imageUrl')";
                                if ($conn->query($insertQuery)) {
                                    $successCount++;
                                } else {
                                    $failureCount++;
                                }
                            } else {
                                $failureCount++;
                            }
                        }
                    }
                    fclose($file);

                    // Thông báo số bản ghi thành công và thất bại
                    echo '<div class="alert alert-info mt-2" role="alert">';
                    echo "$successCount bản ghi được thêm thành công, $failureCount bản ghi thất bại.";
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-warning" role="alert">Vui lòng tải lên file .txt!</div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">Lỗi tải file!</div>';
            }
        }
        ?>

        <hr>

        <?php
        // Hiển thị dữ liệu từ database
        $query = "SELECT * FROM course";
        $result = $conn->query($query);

        echo '<div class="row row-cols-1 row-cols-md-2 g-4">';
        if ($result->num_rows > 0) {
            while ($course = $result->fetch_assoc()) {
        ?>
                <div class="col">
                    <div class="card">
                        <img src="<?= htmlspecialchars($course['imageUrl']) ?>" class="card-img-top" alt="<?= htmlspecialchars($course['Title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['Title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($course['Description']) ?></p>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        echo ' </div>';

        $conn->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>