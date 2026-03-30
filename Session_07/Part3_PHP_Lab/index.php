<?php
require_once 'Database.php';

// Khởi tạo kết nối PDO
$db = Database::getInstance()->getConnection();

// --- 1. LẤY DỮ LIỆU CHO DROPDOWN DANH MỤC ---
$stmtCategories = $db->query("SELECT * FROM categories");
$categories = $stmtCategories->fetchAll();

// --- 2. XỬ LÝ DỮ LIỆU ĐẦU VÀO TỪ NGƯỜI DÙNG (GET) ---
$search_query = $_GET['search'] ?? '';
$category_filter = $_GET['category_id'] ?? '';

// --- 3. XÂY DỰNG TRUY VẤN SQL ĐỘNG ---
// Sử dụng LEFT JOIN để lấy được cả sản phẩm có category_id = NULL
$sql = "SELECT p.id, p.name, p.price, p.stock, c.category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE 1=1";

$params = [];

if (!empty($search_query)) {
    $sql .= " AND p.name LIKE :search";
    $params[':search'] = "%" . $search_query . "%";
}

// Xử lý Category Filter
if (!empty($category_filter)) {
    $sql .= " AND p.category_id = :category_id";
    $params[':category_id'] = $category_filter;
}

// Sắp xếp ID giảm dần cho dễ nhìn
$sql .= " ORDER BY p.id DESC";

// --- 4. THỰC THI TRUY VẤN VỚI PREPARED STATEMENTS ---
$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Product Administration Dashboard</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .filter-bar { margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        
        /* Class CSS cho Visual Alerts (Cảnh báo tồn kho) */
        .low-stock { background-color: #ffe6e6; color: #cc0000; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Product Administration Dashboard</h2>

    <div class="filter-bar">
        <form method="GET" action="index.php">
            <input type="text" name="search" 
                   value="<?= htmlspecialchars($search_query) ?>" 
                   placeholder="Tìm tên sản phẩm...">
            
            <select name="category_id">
                <option value="">-- Tất cả danh mục --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($category_filter == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Lọc dữ liệu</button>
            <a href="index.php"><button type="button">Xóa lọc</button></a>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock Level</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    
                    <?php $rowClass = ($product['stock'] < 10) ? 'low-stock' : ''; ?>
                    
                    <tr class="<?= $rowClass ?>">
                        <td><?= htmlspecialchars($product['id']) ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        
                        <td><?= $product['category_name'] ? htmlspecialchars($product['category_name']) : '<i>Chưa phân loại</i>' ?></td>
                        
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['stock']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Không tìm thấy sản phẩm nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>