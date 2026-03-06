<?php
declare(strict_types=1);
$query = $_GET['q'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <form method="GET">
        <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" required>
        <button type="submit">Search</button>
    </form>
    <?php if ($query !== ''): ?>
        <p>You searched for: <?= htmlspecialchars($query) ?></p>
    <?php endif; ?>
</body>
</html>