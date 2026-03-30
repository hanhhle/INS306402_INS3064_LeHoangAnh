<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

$sql = 'SELECT e.id,
               s.name  AS student_name,
               s.email,
               c.title AS course_title,
               e.enrolled_at
        FROM enrollments e
        JOIN students s ON e.student_id = s.id
        JOIN courses  c ON e.course_id  = c.id
        ORDER BY e.enrolled_at DESC';

$enrollments = $db->fetchAll($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách đăng ký học</title>
</head>
<body>
<h1>Danh sách đăng ký học</h1>
<p><a href="../students/index.php">Chuyển sang module Sinh viên</a> | <a href="../courses/index.php">Chuyển sang module Khóa học</a></p>

<p><a href="create.php">+ Thêm đăng ký</a></p>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <tr style="background: #9C27B0; color: white;">
        <th>ID</th>
        <th>Sinh viên</th>
        <th>Email</th>
        <th>Khóa học</th>
        <th>Thời gian đăng ký</th>
        <th>Hành động</th>
    </tr>

    <?php foreach ($enrollments as $enroll): ?>
        <tr>
            <td><?= $enroll['id'] ?></td>
            <td><?= htmlspecialchars($enroll['student_name']) ?></td>
            <td><?= htmlspecialchars($enroll['email']) ?></td>
            <td><?= htmlspecialchars($enroll['course_title']) ?></td>
            <td><?= $enroll['enrolled_at'] ?></td>
            <td>
                <a href="delete.php?id=<?= $enroll['id'] ?>" onclick="return confirm('Hủy đăng ký này?');">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>