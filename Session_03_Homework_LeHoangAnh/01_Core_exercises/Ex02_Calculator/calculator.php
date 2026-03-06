<?php
declare(strict_types=1);

$result = '';
$num1_val = '';
$num2_val = '';
$operation = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num1 = $_POST['num1'] ?? '';
    $num2 = $_POST['num2'] ?? '';
    $operation = $_POST['operation'] ?? '';

    $num1_val = $num1;
    $num2_val = $num2;

    if (!is_numeric($num1) || !is_numeric($num2)) {
        $result = "Error: Inputs must be numeric.";
    } else {
        $n1 = (float)$num1;
        $n2 = (float)$num2;
        
        switch ($operation) {
            case 'add':
                $result = "$n1 + $n2 = " . ($n1 + $n2);
                break;
            case 'sub':
                $result = "$n1 - $n2 = " . ($n1 - $n2);
                break;
            case 'mul':
                $result = "$n1 * $n2 = " . ($n1 * $n2);
                break;
            case 'div':
                if ($n2 == 0) {
                    $result = "Error: Division by zero";
                } else {
                    $result = "$n1 / $n2 = " . ($n1 / $n2);
                }
                break;
            default:
                $result = "Error: Invalid operation";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arithmetic Calculator</title>
</head>
<body>
    <form action="calculator.php" method="POST">
        <input type="number" step="any" name="num1" value="<?= htmlspecialchars((string)$num1_val) ?>" required>
        
        <select name="operation" required>
            <option value="add" <?= $operation === 'add' ? 'selected' : '' ?>>+</option>
            <option value="sub" <?= $operation === 'sub' ? 'selected' : '' ?>>-</option>
            <option value="mul" <?= $operation === 'mul' ? 'selected' : '' ?>>*</option>
            <option value="div" <?= $operation === 'div' ? 'selected' : '' ?>>/</option>
        </select>
        
        <input type="number" step="any" name="num2" value="<?= htmlspecialchars((string)$num2_val) ?>" required>
        
        <button type="submit">Calculate</button>
    </form>

    <?php if ($result !== ''): ?>
        <p><strong>Result:</strong> <?= htmlspecialchars($result) ?></p>
    <?php endif; ?>
</body>
</html>