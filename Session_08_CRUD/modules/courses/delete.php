<?php
require_once '../../classes/Database.php';
$db = Database::getInstance();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    // Lưu ý: Do có ON DELETE CASCADE, xóa khóa học sẽ tự xóa các bản ghi danh liên quan [cite: 549, 672-674]
    $db->query('DELETE FROM courses WHERE id = ?', [$id]);
}

header('Location: index.php?deleted=1');
exit;
?>