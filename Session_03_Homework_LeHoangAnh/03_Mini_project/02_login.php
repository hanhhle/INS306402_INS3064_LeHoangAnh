<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

// Redirect if already authenticated
if (isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit;
}

$errorMsg = '';

// Rate Limiting Logic: Wait 3 fails before blocking
$maxAttempts = 3;
$lockoutDuration = 60; // seconds

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check if user is currently locked out
    if (isset($_SESSION['lockout_time']) && (time() - $_SESSION['lockout_time']) < $lockoutDuration) {
        $remaining = $lockoutDuration - (time() - $_SESSION['lockout_time']);
        $errorMsg = "Account locked due to multiple failed attempts. Try again in {$remaining} seconds.";
    } else {
        // Reset lockout if time has expired
        if (isset($_SESSION['lockout_time']) && (time() - $_SESSION['lockout_time']) >= $lockoutDuration) {
            unset($_SESSION['lockout_time']);
            $_SESSION['login_attempts'] = 0;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $dataFile = __DIR__ . '/data/users.json';
        $users = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

        if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
            // Success: Reset attempts and initialize session
            $_SESSION['login_attempts'] = 0;
            $_SESSION['user'] = $username;
            header('Location: profile.php');
            exit;
        } else {
            // Failure: Increment attempts
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            
            if ($_SESSION['login_attempts'] >= $maxAttempts) {
                $_SESSION['lockout_time'] = time();
                $errorMsg = "Account locked due to multiple failed attempts. Try again in {$lockoutDuration} seconds.";
            } else {
                $attemptsLeft = $maxAttempts - $_SESSION['login_attempts'];
                $errorMsg = "Invalid credentials. Attempts remaining: {$attemptsLeft}";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Profile System</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: #1e1e1e; padding: 30px; border-radius: 8px; border: 1px solid #333; width: 100%; max-width: 400px; }
        h2 { margin-top: 0; border-bottom: 1px solid #333; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #b3b3b3; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; background: #2d2d2d; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box; }
        button { background: #198754; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #157347; }
        .error-alert { background: rgba(220, 53, 69, 0.2); border: 1px solid #dc3545; color: #ea868f; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .links { margin-top: 15px; text-align: center; font-size: 0.9em; }
        .links a { color: #0dcaf0; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Secure Login</h2>
        <?php if ($errorMsg): ?>
            <div class="error-alert"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Log In</button>
        </form>
        <div class="links"><a href="register.php">Create an account</a></div>
    </div>
</body>
</html>