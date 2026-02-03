<?php
declare(strict_types=1);

session_start();

$username_fixed = "admin"; 
$password_fixed = "123456";
$message = "";
$message_type = ""; 

if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_input = trim($_POST['username'] ?? '');
    $pass_input = trim($_POST['password'] ?? '');

    if ($user_input === $username_fixed && $pass_input === $password_fixed) {
        $message = "Login Successful";
        $message_type = "success";
        $_SESSION['failed_attempts'] = 0;
    } else {
        $message = "Invalid Credentials";
        $message_type = "error";
        $_SESSION['failed_attempts']++;
    }
}

if (isset($_GET['reset'])) {
    $_SESSION['failed_attempts'] = 0;
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Simple Login - Exercise 3.3</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; padding-top: 50px; background-color: #f4f7f6; }
        .login-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 350px; }
        .test-credentials { background: #e7f3ff; color: #0c5460; padding: 12px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9em; border-left: 5px solid #17a2b8; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        button:hover { background-color: #0056b3; }
        .message { padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-weight: bold; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .attempt-counter { margin-top: 20px; text-align: center; border-top: 1px solid #eee; padding-top: 15px; color: #666; }
    </style>
</head>
<body>

<div class="login-box">
    <h2 style="text-align: center; margin-top: 0;">Login</h2>

    <div class="test-credentials">
        Username: <code>admin</code> | Password: <code>123456</code>
    </div>

    <?php if ($message !== ""): ?>
        <div class="message <?= $message_type ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>

    <div class="attempt-counter">
        Failed Attempts: <strong style="color: #d9534f;"><?= $_SESSION['failed_attempts'] ?></strong>
        <?php if ($_SESSION['failed_attempts'] > 0): ?>
            <br><small><a href="?reset=1" style="color: #007bff; text-decoration: none;">Reset Counter</a></small>
        <?php endif; ?>
    </div>
</div>

</body>
</html>