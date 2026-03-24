<?php
require_once 'Database.php';

$db = Database::getInstance()->getConnection();

// 1. Chuẩn bị và thực thi câu lệnh SQL (Lấy từ Task 3 - Phần 2)
$sql = "SELECT u.name, u.email, SUM(o.total_amount) AS total_spent
        FROM users u
        JOIN orders o ON u.id = o.user_id
        GROUP BY u.id, u.name, u.email
        ORDER BY total_spent DESC
        LIMIT 3";

// 2. Lấy toàn bộ kết quả trả về dạng mảng
$stmt = $db->query($sql);
$top_customers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Top 3 VIP Customers</title>
    <style>
        table { border-collapse: collapse; width: 50%; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Danh sách 3 khách hàng chi tiêu nhiều nhất</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Total Spent</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Triển khai vòng lặp foreach để in dữ liệu 
            foreach ($top_customers as $customer): 
            ?>
                <tr>
                    <td><?= htmlspecialchars($customer['name']) ?></td>
                    <td><?= htmlspecialchars($customer['email']) ?></td>
                    <td>$<?= number_format($customer['total_spent'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>