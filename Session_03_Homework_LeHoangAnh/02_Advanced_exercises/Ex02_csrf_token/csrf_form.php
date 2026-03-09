<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

// PHẦN 1: LOGIC XỬ LÝ
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy token từ POST request
    $postToken = $_POST['csrf_token'] ?? '';
    // Lấy token từ Session
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    // Validate: So sánh token (sử dụng hash_equals để chống Timing Attack)
    if (empty($postToken) || empty($sessionToken) || !hash_equals($sessionToken, $postToken)) {
        // Nếu không khớp, dừng chương trình và trả về 403
        http_response_code(403);
        die("403 Forbidden");
    }

    // Nếu token hợp lệ, tiếp tục xử lý
    $message = "Success: Form submitted securely! CSRF Token validated.";
    
    // Tùy chọn: Xóa token cũ và tạo mới sau khi submit thành công để tăng bảo mật
    unset($_SESSION['csrf_token']);
}

// Tạo CSRF token mới nếu chưa có trong session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSRF Protection</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: #1e1e1e; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); width: 100%; max-width: 400px; border: 1px solid #333; }
        h2 { border-bottom: 1px solid #333; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #b3b3b3; }
        input[type="text"] { width: 100%; padding: 10px; background: #2d2d2d; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box; }
        button { background: #0d6efd; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%; font-weight: bold; }
        button:hover { background: #0b5ed7; }
        .success { background: rgba(25, 135, 84, 0.2); color: #75b798; border: 1px solid #198754; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .token-display { font-family: monospace; font-size: 0.8em; color: #888; margin-top: 15px; word-break: break-all; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Secure Transfer Form</h2>
        
        <?php if ($message): ?>
            <div class="success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="amount">Transfer Amount ($):</label>
                <input type="text" name="amount" id="amount" required placeholder="100.00">
            </div>
            
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            
            <button type="submit">Submit Transfer</button>
        </form>

        <div class="token-display">
            Current Token: <?= htmlspecialchars($csrfToken) ?>
        </div>
    </div>
</body>
</html>