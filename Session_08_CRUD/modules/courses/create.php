<?php
require_once '../../classes/Database.php';
$db = Database::getInstance();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validation: Title bắt buộc và >= 3 ký tự 
    if (empty($title)) {
        $errors['title'] = 'Tiêu đề khóa học là bắt buộc.';
    } elseif (strlen($title) < 3) {
        $errors['title'] = 'Tiêu đề phải có ít nhất 3 ký tự.';
    }

    if (empty($errors)) {
        $db->query('INSERT INTO courses (title, description) VALUES (?, ?)', [$title, $description]);
        header('Location: index.php?success=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head><title>Thêm Khóa học</title></head>
<body>
    <h2>Thêm Khóa học mới</h2>
    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $err) echo "<li>$err</li>"; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Tiêu đề:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"><br><br>
        
        <label>Mô tả:</label><br>
        <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea><br><br>
        
        <button type="submit">Lưu</button>
        <a href="index.php">Hủy</a>
    </form>
</body>
</html>