<?php
require_once '../../classes/Database.php';
$db = Database::getInstance();
$error = '';

// 1. Lấy dữ liệu cho các Dropdown (<select>) [cite: 700-701]
$students = $db->fetchAll('SELECT id, name FROM students ORDER BY name ASC');
$courses = $db->fetchAll('SELECT id, title FROM courses ORDER BY title ASC');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int)($_POST['student_id'] ?? 0);
    $course_id = (int)($_POST['course_id'] ?? 0);

    // 2. Validation: Đảm bảo đã chọn đúng ID [cite: 702]
    if ($student_id <= 0 || $course_id <= 0) {
        $error = 'Vui lòng chọn cả học sinh và khóa học.';
    } else {
        // 3. Kiểm tra trùng lặp: Học sinh không được đăng ký cùng 1 môn 2 lần [cite: 703-711]
        $exists = $db->fetch(
            'SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?', 
            [$student_id, $course_id]
        );

        if ($exists) {
            $error = "Học sinh này đã đăng ký khóa học này rồi!";
        } else {
            // Thêm mới ghi danh
            $db->query(
                'INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)', 
                [$student_id, $course_id]
            );
            header('Location: index.php?success=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head><title>Đăng ký Khóa học</title></head>
<body>
    <h2>Ghi danh Học sinh vào Khóa học</h2>
    
    <?php if ($error): ?>
        <p style="color: red;"><strong>Lỗi:</strong> <?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Chọn Học sinh:</label><br>
        <select name="student_id" required>
            <option value="">-- Chọn học sinh --</option>
            <?php foreach ($students as $s): ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Chọn Khóa học:</label><br>
        <select name="course_id" required>
            <option value="">-- Chọn khóa học --</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Lưu Ghi danh</button>
        <a href="index.php">Hủy</a>
    </form>
</body>
</html>