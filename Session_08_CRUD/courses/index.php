<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();
$courses = $db->fetchAll('SELECT * FROM courses ORDER BY created_at DESC');

$successMessage = '';
if (isset($_GET['success'])) $successMessage = 'Thêm khóa học thành công!';
elseif (isset($_GET['updated'])) $successMessage = 'Cập nhật khóa học thành công!';
elseif (isset($_GET['deleted'])) $successMessage = 'Xóa khóa học thành công!';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý khóa học</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #FF9800; color: #fff; }
        .btn { padding: 4px 8px; text-decoration: none; border-radius: 3px; }
        .btn-add { background: #FF9800; color: #fff; }
        .btn-edit { background: #2196F3; color: #fff; }
        .btn-delete { background: #f44336; color: #fff; }
    </style>
</head>
<body>
<h1>Quản lý khóa học</h1>
<p><a href="../students/index.php">Chuyển sang module Sinh viên</a> | <a href="../enrollments/index.php">Chuyển sang module Ghi danh</a></p>

<?php if ($successMessage): ?>
    <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
<?php endif; ?>

<p><a href="create.php" class="btn btn-add">+ Thêm khóa học</a></p>

<table>
    <tr><th>ID</th><th>Tiêu đề</th><th>Mô tả</th><th>Ngày tạo</th><th>Hành động</th></tr>
    <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= $course['id'] ?></td>
            <td><?= htmlspecialchars($course['title']) ?></td>
            <td><?= htmlspecialchars($course['description'] ?? '') ?></td>
            <td><?= $course['created_at'] ?></td>
            <td>
                <a href="edit.php?id=<?= $course['id'] ?>" class="btn btn-edit">Sửa</a>
                <a href="delete.php?id=<?= $course['id'] ?>" class="btn btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa?');">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>