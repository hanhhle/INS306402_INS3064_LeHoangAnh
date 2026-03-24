<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiển Thị Dữ Liệu - Shop DB</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 30px; 
            background-color: #f9f9f9;
        }
        /* Điểm nhấn màu đỏ cho tiêu đề */
        h2 { 
            color: #cc0000; 
            border-bottom: 2px solid #cc0000;
            padding-bottom: 5px;
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-bottom: 30px; 
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        .error { 
            color: white; 
            background-color: #cc0000; 
            padding: 10px; 
            border-radius: 4px;
        }
        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Trình Quản Lý Dữ Liệu (Shop DB)</h1>
    
    <?php
    // BẬT TẤT CẢ THÔNG BÁO LỖI: Chống màn hình trắng
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Cấu hình kết nối PDO (Dùng 127.0.0.1 để tránh lỗi socket)
    $host = '127.0.0.1';
    $db   = 'shop_db';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Bắt lỗi nghiêm ngặt
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        echo "<p class='success'>✓ Kết nối Database '{$db}' thành công!</p>";
        
        // Danh sách các bảng cần lấy dữ liệu
        $tables = ['users', 'categories', 'products', 'orders', 'order_items'];

        foreach ($tables as $table) {
            echo "<h2>Dữ liệu bảng: " . htmlspecialchars($table) . "</h2>";
            
            // 1. Kiểm tra xem bảng có tồn tại trong database không
            $checkTable = $pdo->query("SHOW TABLES LIKE '$table'")->rowCount();
            if ($checkTable == 0) {
                echo "<p class='error'>Bảng '{$table}' chưa được tạo trong database!</p>";
                continue; // Bỏ qua, chạy sang bảng tiếp theo
            }

            // 2. Lấy tối đa 5 dòng dữ liệu từ bảng
            $stmt = $pdo->query("SELECT * FROM $table LIMIT 5");
            $rows = $stmt->fetchAll();

            // 3. Render HTML Table nếu có dữ liệu
            if (count($rows) > 0) {
                echo "<table>";
                echo "<tr>";
                // Lấy tên các cột từ dòng đầu tiên để làm Header (th)
                foreach (array_keys($rows[0]) as $columnName) {
                    echo "<th>" . htmlspecialchars($columnName) . "</th>";
                }
                echo "</tr>";

                // In từng dòng dữ liệu (td)
                foreach ($rows as $row) {
                    echo "<tr>";
                    foreach ($row as $data) {
                        echo "<td>" . htmlspecialchars((string)$data) . "</td>"; // Chống XSS
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Bảng tồn tại nhưng hiện chưa có dòng dữ liệu nào.</p>";
            }
        }

    } catch (PDOException $e) {
        // Bắt và in thẳng lỗi ra màn hình nếu sai mật khẩu, sai tên DB...
        echo "<div class='error'>";
        echo "<strong>LỖI KẾT NỐI DATABASE:</strong><br>";
        echo $e->getMessage();
        echo "<br><br>Hãy kiểm tra lại xem XAMPP đã bật MySQL chưa và tên database có đúng là 'shop_db' không.";
        echo "</div>";
    }
    ?>
</body>
</html>