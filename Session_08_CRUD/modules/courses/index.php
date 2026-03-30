<?php
require_once '../../classes/Database.php';
$db = Database::getInstance();

// Lấy danh sách khóa học
$courses = $db->fetchAll('SELECT * FROM courses ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="vi">
<head><title>Danh sách Khóa học</title></head>
<body>
    <h2>Danh sách Khóa học</h2>
    <a href="create.php">Thêm khóa học mới</a><br><br>
    
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($courses as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['title']) ?></td>
            <td><?= htmlspecialchars($c['description'] ?? '') ?></td>
            <td>
                <a href="edit.php?id=<?= $c['id'] ?>">Sửa</a> |
                <a href="delete.php?id=<?= $c['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa khóa học này?');">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>