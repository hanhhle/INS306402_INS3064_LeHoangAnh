<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$errors = [];
$successMessage = '';

// Maintain state for sticky forms
$formData = [
    'fullname' => '',
    'email'    => '',
    'age'      => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Retrieve and sanitize input
    $formData['fullname'] = trim($_POST['fullname'] ?? '');
    $formData['email']    = trim($_POST['email'] ?? '');
    $formData['age']      = trim($_POST['age'] ?? '');

    // 2. Centralized Validation Collection
    if (empty($formData['fullname'])) {
        $errors['fullname'] = "Full name is required.";
    } elseif (strlen($formData['fullname']) < 3) {
        $errors['fullname'] = "Full name must be at least 3 characters long.";
    }

    if (empty($formData['email'])) {
        $errors['email'] = "Email address is required.";
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please provide a valid email format.";
    }

    if (empty($formData['age'])) {
        $errors['age'] = "Age is required.";
    } elseif (!is_numeric($formData['age']) || (int)$formData['age'] < 18) {
        $errors['age'] = "You must be at least 18 years old.";
    }

    // 3. Process if no errors exist
    if (empty($errors)) {
        $successMessage = "Profile updated successfully!";
        // Reset form data upon success
        $formData = ['fullname' => '', 'email' => '', 'age' => ''];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error Summary Block</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: #1e1e1e; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); width: 100%; max-width: 450px; border: 1px solid #333; }
        h2 { border-bottom: 1px solid #333; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #b3b3b3; font-size: 0.9em; }
        
        /* Default Input Styling */
        input[type="text"], input[type="number"] { width: 100%; padding: 10px; background: #2d2d2d; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box; transition: border 0.3s; }
        
        /* Error Highlight Styling */
        input.input-error { border: 2px solid #dc3545; background: rgba(220, 53, 69, 0.05); }
        
        button { background: #0d6efd; color: white; border: none; padding: 12px 15px; border-radius: 4px; cursor: pointer; width: 100%; font-weight: bold; transition: background 0.2s; }
        button:hover { background: #0b5ed7; }
        
        /* Centralized Error Summary Box */
        .error-summary { background: rgba(220, 53, 69, 0.15); border-left: 4px solid #dc3545; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .error-summary h4 { margin: 0 0 10px 0; color: #ea868f; }
        .error-summary ul { margin: 0; padding-left: 20px; color: #e0e0e0; font-size: 0.9em; }
        .error-summary li { margin-bottom: 5px; }
        
        .success-box { background: rgba(25, 135, 84, 0.15); border-left: 4px solid #198754; color: #75b798; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Information</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-summary">
                <h4>Please correct the following errors:</h4>
                <ul>
                    <?php foreach ($errors as $errorMsg): ?>
                        <li><?= htmlspecialchars($errorMsg) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="success-box"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" 
                       value="<?= htmlspecialchars($formData['fullname']) ?>"
                       class="<?= isset($errors['fullname']) ? 'input-error' : '' ?>">
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="text" id="email" name="email" 
                       value="<?= htmlspecialchars($formData['email']) ?>"
                       class="<?= isset($errors['email']) ? 'input-error' : '' ?>">
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" 
                       value="<?= htmlspecialchars($formData['age']) ?>"
                       class="<?= isset($errors['age']) ? 'input-error' : '' ?>">
            </div>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>