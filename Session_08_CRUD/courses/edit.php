<?php
require_once __DIR__ . '/../classes/Database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header('Location: index.php'); exit; }

$errors = [];
$db = Database::getInstance();

try {
    $course = $db->fetch('SELECT * FROM courses WHERE id = ?', [$id]);
    if (!$course) { header('Location: index.php'); exit; }
} catch (Exception $e) {
    die('Không lấy được dữ liệu khóa học.');
}

$title       = $_POST['title'] ?? $course['title'];
$description = $_POST['description'] ?? $course['description'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($title);
    $description = trim($description);

    if ($title === '') {
        $errors['title'] = 'Vui lòng nhập tiêu đề khóa học.';
    } elseif (strlen($title) < 3) {
        $errors['title'] = 'Tiêu đề phải có ít nhất 3 ký tự.';
    }

    if (empty($errors)) {
        try {
            $db->update('courses', ['title' => $title, 'description' => $description], 'id = ?', [$id]);
            header('Location: index.php?updated=1');
            exit;
        } catch (Exception $e) {
            $errors['general'] = 'Có lỗi khi cập nhật, vui lòng thử lại.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Sửa khóa học</title></head>
<body>
<h1>Sửa khóa học</h1>
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
    <button type="submit">Cập nhật</button> <a href="index.php">Hủy</a>
</form>
</body>
</html>