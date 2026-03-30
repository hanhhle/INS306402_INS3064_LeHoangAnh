<?php
require_once __DIR__ . '/../classes/Database.php';

$errors = [];
$title  = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $errors['title'] = 'Vui lòng nhập tiêu đề khóa học.';
    } elseif (strlen($title) < 3) {
        $errors['title'] = 'Tiêu đề phải có ít nhất 3 ký tự.';
    }

    if (empty($errors)) {
        try {
            $db = Database::getInstance();
            $db->insert('courses', ['title' => $title, 'description' => $description]);
            header('Location: index.php?success=1');
            exit;
        } catch (Exception $e) {
            $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại sau.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Thêm khóa học</title></head>
<body>
<h1>Thêm khóa học mới</h1>
<?php if (!empty($errors['general'])): ?><p style="color: red;"><?= htmlspecialchars($errors['general']) ?></p><?php endif; ?>

<form method="post">
    <div>
        <label>Tiêu đề:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>">
        <?php if (!empty($errors['title'])): ?><span style="color: red;"><?= htmlspecialchars($errors['title']) ?></span><?php endif; ?>
    </div><br>
    <div>
        <label>Mô tả:</label><br>
        <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($description) ?></textarea>
    </div><br>
    <button type="submit">Lưu</button> <a href="index.php">Hủy</a>
</form>
</body>
</html>