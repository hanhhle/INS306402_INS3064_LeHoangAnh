<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$errors = [];
$successMessage = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu và làm sạch cơ bản
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? ''; // Không trim password vì space có thể là 1 ký tự hợp lệ
    
    // 1. Validate Username (chỉ chứa chữ và số)
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errors[] = "Username must contain only alphanumeric characters.";
    }

    // 2. Validate Password (Từng điều kiện riêng biệt để báo lỗi cụ thể)
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password missing uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password missing lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password missing number.";
    }
    // Ký tự đặc biệt: Phủ định của chữ cái và chữ số
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $errors[] = "Password missing symbol (special character).";
    }

    if (empty($errors)) {
        $successMessage = "Registration successful! Strong password detected.";
        // Reset form data sau khi thành công
        $username = ''; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Regex Validation</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: #1e1e1e; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); width: 100%; max-width: 400px; border: 1px solid #333; }
        h2 { border-bottom: 1px solid #333; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #b3b3b3; font-size: 0.9em; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; background: #2d2d2d; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box; }
        button { background: #6f42c1; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%; font-weight: bold; }
        button:hover { background: #5936a2; }
        .error-list { background: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; padding: 10px; border-radius: 4px; margin-bottom: 15px; color: #ea868f; font-size: 0.9em; }
        .error-list ul { margin: 0; padding-left: 20px; }
        .success { background: rgba(25, 135, 84, 0.2); color: #75b798; border: 1px solid #198754; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register Account</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($successMessage): ?>
            <div class="success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username (Alphanumeric only):</label>
                <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            <div class="form-group">
                <label>Password (1 Upper, 1 Lower, 1 Num, 1 Sym):</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>