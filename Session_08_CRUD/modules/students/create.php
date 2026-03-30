<?php
require_once '../../classes/Database.php';
$db = Database::getInstance();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($name)) $errors[] = 'Tên là bắt buộc.'; // [cite: 633, 634]
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.'; // [cite: 635, 636]

    if (empty($errors)) {
        // Kiểm tra email tồn tại [cite: 639, 640]
        $existing = $db->fetch('SELECT id FROM students WHERE email = ?', [$email]);
        if ($existing) {
            $errors[] = 'Email này đã được sử dụng!'; // [cite: 641, 642]
        } else {
            $db->query('INSERT INTO students (name, email) VALUES (?, ?)', [$name, $email]); // [cite: 644]
            header('Location: index.php?success=1'); // [cite: 645]
            exit;
        }
    }
}
?>