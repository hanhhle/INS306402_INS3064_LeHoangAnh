<?php
declare(strict_types=1);

$show_form = true;
$errors = [];
$submitted_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $message  = trim($_POST['message'] ?? '');

    if (empty($fullName)) $errors[] = "Full Name is missing.";
    if (empty($email))    $errors[] = "Email is missing.";
    if (empty($phone))    $errors[] = "Phone Number is missing.";
    if (empty($message))  $errors[] = "Message is missing.";

    if (empty($errors)) {
        $show_form = false;
        $submitted_data = [
            'Full Name' => $fullName,
            'Email'    => $email,
            'Phone'    => $phone,
            'Message'  => $message
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Self-Processing Contact Form</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding-top: 30px; background-color: #f8f9fa; }
        .container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 400px; }
        .test-info { background: #e7f3ff; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.85em; border-left: 4px solid #17a2b8; }
        .error-box { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .success-box { background: #d4edda; color: #155724; padding: 20px; border-radius: 4px; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        ul { padding-left: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Contact Form (Self-Processing)</h2>

    <?php if ($show_form): ?>
        <div class="test-info">
            <strong>Testing Note:</strong> Try submitting an empty form to see "Missing Data" errors.
        </div>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <strong>Missing Data:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="contact_self.php" method="POST">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($fullName ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Phone Number:</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Message:</label>
                <textarea name="message" rows="4"><?= htmlspecialchars($message ?? '') ?></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>

    <?php else: ?>
        <div class="success-box">
            <h3>Thank You!</h3>
            <p>Your message has been processed successfully.</p>
        </div>

        <h4>Submitted Details:</h4>
        <ul>
            <?php foreach ($submitted_data as $label => $value): ?>
                <li><strong><?= $label ?>:</strong> <?= htmlspecialchars($value) ?></li>
            <?php endforeach; ?>
        </ul>
        <p><a href="contact_self.php">Send another message</a></p>
    <?php endif; ?>
</div>

</body>
</html>