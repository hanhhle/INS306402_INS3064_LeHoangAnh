<?php
declare(strict_types=1);

$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

if (isset($_POST['bio'])) {
    $bio = htmlspecialchars($_POST['bio']);
    $userSafe = htmlspecialchars($user);
    echo "<p>Final Output -> User: $userSafe, Bio: $bio</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <form method="POST">
        <input type="hidden" name="username" value="<?= htmlspecialchars($user) ?>">
        <input type="hidden" name="password" value="<?= htmlspecialchars($pass) ?>">
        <textarea name="bio" required placeholder="Profile Bio"></textarea>
        <button type="submit">Finish Registration</button>
    </form>
</body>
</html>