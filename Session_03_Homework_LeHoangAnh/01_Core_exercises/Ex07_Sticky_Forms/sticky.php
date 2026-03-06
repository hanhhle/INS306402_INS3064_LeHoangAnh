<?php
declare(strict_types=1);

$name = '';
$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    if (strlen($pass) < 6) {
        $error = "Password too short.";
    } else {
        $error = "Success!";
        $name = ''; 
        $email = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <?php if ($error !== '') echo "<p>" . htmlspecialchars($error) . "</p>"; ?>
    <form method="POST">
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required placeholder="Name">
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit">Submit</button>
    </form>
</body>
</html>