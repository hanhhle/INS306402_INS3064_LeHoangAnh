<?php
declare(strict_types=1);

function sanitize(string $data): string {
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

function validateEmail(string $email): mixed {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateLength(string $str, int $min, int $max): bool {
    $len = strlen($str);
    return $len >= $min && $len <= $max;
}

function validatePassword(string $password): bool {
    // Requires at least 8 characters, uppercase, lowercase, number, and special character
    return (bool)preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}
?>