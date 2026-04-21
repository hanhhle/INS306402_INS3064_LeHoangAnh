<?php
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/BorrowController.php';

$controllerParam = $_GET['controller'] ?? 'book';
$action = $_GET['action'] ?? 'index';

if ($controllerParam === 'borrow') {
    $controller = new BorrowController();
} else {
    $controller = new BookController();
}

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    // Trường hợp gõ sai action trên URL
    http_response_code(404);
    echo "<h1 style='text-align:center; color:red; margin-top:50px;'>404 - Trang bạn tìm không tồn tại!</h1>";
    echo "<p style='text-align:center;'><a href='index.php'>Quay lại trang chủ</a></p>";
}