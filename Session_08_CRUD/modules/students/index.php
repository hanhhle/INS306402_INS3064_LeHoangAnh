<?php
require_once '../../classes/Database.php';
$db = Database::getInstance();
$students = $db->fetchAll('SELECT * FROM students ORDER BY id DESC'); // [cite: 615]
?>
<h2>Danh sách Học sinh</h2>
<a href="create.php">Thêm mới</a>
<table border="1">
    <tr><th>Tên</th><th>Email</th><th>Hành động</th></tr>
    <?php foreach ($students as $s): ?>
    <tr>
        <td><?= htmlspecialchars($s['name']) ?></td> <td><?= htmlspecialchars($s['email']) ?></td> <td>
            <a href="edit.php?id=<?= $s['id'] ?>">Sửa</a> <a href="delete.php?id=<?= $s['id'] ?>" onclick="return confirm('Xóa học sinh này?')">Xóa</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>