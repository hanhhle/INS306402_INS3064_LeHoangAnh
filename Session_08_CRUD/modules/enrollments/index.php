<?php
require_once '../../classes/Database.php';
$db = Database::getInstance();

// Sử dụng JOIN để lấy Tên học sinh và Tên khóa học thay vì chỉ hiển thị ID [cite: 690-697]
$sql = '
    SELECT e.id, s.name AS student_name, c.title AS course_title, e.enrolled_at 
    FROM enrollments e 
    JOIN students s ON e.student_id = s.id 
    JOIN courses c ON e.course_id = c.id 
    ORDER BY e.enrolled_at DESC
';
$enrollments = $db->fetchAll($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head><title>Danh sách Ghi danh</title></head>
<body>
    <h2>Danh sách Ghi danh (Enrollments)</h2>
    <a href="create.php">Đăng ký môn học mới</a><br><br>
    
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Tên Học Sinh</th>
            <th>Tên Khóa Học</th>
            <th>Ngày Đăng Ký</th>
        </tr>
        <?php foreach ($enrollments as $e): ?>
        <tr>
            <td><?= $e['id'] ?></td>
            <td><?= htmlspecialchars($e['student_name']) ?></td>
            <td><?= htmlspecialchars($e['course_title']) ?></td>
            <td><?= htmlspecialchars($e['enrolled_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>