<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($username)) $errors['username'] = "Username is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format.";
    if (strlen($password) < 6) $errors['password'] = "Password must be at least 6 characters.";
    if ($password !== $confirm) $errors['confirm'] = "Passwords do not match.";

    if (empty($errors)) {
        $dataFile = __DIR__ . '/data/users.json';
        $users = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

        // Check if user already exists
        if (isset($users[$username])) {
            $errors['username'] = "Username already taken.";
        } else {
            // Save user data
            $users[$username] = [
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'bio'      => '',
                'avatar'   => 'default.png'
            ];
            file_put_contents($dataFile, json_encode($users, JSON_PRETTY_PRINT));
            $successMessage = "Registration successful! You may now log in.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Profile System</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: #1e1e1e; padding: 30px; border-radius: 8px; border: 1px solid #333; width: 100%; max-width: 400px; }
        h2 { margin-top: 0; border-bottom: 1px solid #333; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #b3b3b3; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 10px; background: #2d2d2d; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box; }
        button { background: #0d6efd; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #0b5ed7; }
        .error { color: #ea868f; font-size: 0.85em; margin-top: 5px; }
        .success { background: rgba(25, 135, 84, 0.2); color: #75b798; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .links { margin-top: 15px; text-align: center; font-size: 0.9em; }
        .links a { color: #0dcaf0; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register Account</h2>
        <?php if ($successMessage): ?>
            <div class="success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                <?php if (isset($errors['username'])) echo "<div class='error'>{$errors['username']}</div>"; ?>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <?php if (isset($errors['email'])) echo "<div class='error'>{$errors['email']}</div>"; ?>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password">
                <?php if (isset($errors['password'])) echo "<div class='error'>{$errors['password']}</div>"; ?>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password">
                <?php if (isset($errors['confirm'])) echo "<div class='error'>{$errors['confirm']}</div>"; ?>
            </div>
            <button type="submit">Create Account</button>
        </form>
        <div class="links"><a href="login.php">Already have an account? Log in here.</a></div>
    </div>
</body>
</html>