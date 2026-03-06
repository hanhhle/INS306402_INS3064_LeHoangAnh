<?php
declare(strict_types=1);

$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num1 = $_POST['num1'] ?? '';
    $num2 = $_POST['num2'] ?? '';
    $op = $_POST['operation'] ?? '';

    if (!is_numeric($num1) || !is_numeric($num2)) {
        $result = "Error: Inputs must be numeric.";
    } else {
        $n1 = (float)$num1;
        $n2 = (float)$num2;
        
        $result = match($op) {
            'add' => "$n1 + $n2 = " . ($n1 + $n2),
            'sub' => "$n1 - $n2 = " . ($n1 - $n2),
            'mul' => "$n1 * $n2 = " . ($n1 * $n2),
            'div' => $n2 == 0 ? "Error: Division by zero" : "$n1 / $n2 = " . ($n1 / $n2),
            default => "Invalid operation."
        };
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <form method="POST">
        <input type="text" name="num1" required>
        <select name="operation">
            <option value="add">+</option>
            <option value="sub">-</option>
            <option value="mul">*</option>
            <option value="div">/</option>
        </select>
        <input type="text" name="num2" required>
        <button type="submit">Calculate</button>
    </form>
    <?php if ($result !== ''): ?>
        <p><?= htmlspecialchars($result) ?></p>
    <?php endif; ?>
</body>
</html>