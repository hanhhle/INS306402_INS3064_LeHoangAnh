<?php
require_once '../../classes/Database.php';
$db = Database::getInstance();
$errors = [];

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) die('ID không hợp lệ');

// Lấy dữ liệu cũ
$course = $db->fetch('SELECT * FROM courses WHERE id = ?', [$id]);
if (!$course) die('Không tìm thấy khóa học');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validation 
    if (empty($title)) {
        $errors['title'] = 'Tiêu đề khóa học là bắt buộc.';
    } elseif (strlen($title) < 3) {
        $errors['title'] = 'Tiêu đề phải có ít nhất 3 ký tự.';
    }

    if (empty($errors)) {
        $db->query('UPDATE courses SET title = ?, description = ? WHERE id = ?', [$title, $description, $id]);
        header('Location: index.php?updated=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head><title>Sửa Khóa học</title></head>
<body>
    <h2>Sửa Khóa học</h2>
    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $err) echo "<li>$err</li>"; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Tiêu đề:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? $course['title']) ?>"><br><br>
        
        <label>Mô tả:</label><br>
        <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($_POST['description'] ?? $course['description']) ?></textarea><br><br>
        
        <button type="submit">Cập nhật</button>
        <a href="index.php">Hủy</a>
    </form>
</body>
</html>