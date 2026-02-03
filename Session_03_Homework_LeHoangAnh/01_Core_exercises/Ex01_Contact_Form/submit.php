<?php
declare(strict_types=1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Thu thập dữ liệu và làm sạch khoảng trắng thừa 
    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $message  = trim($_POST['message'] ?? '');

    $errors = [];

    // Kiểm tra dữ liệu trống (Validation) [cite: 20, 90]
    if (empty($fullName)) $errors[] = "Full Name is missing.";
    if (empty($email))    $errors[] = "Email is missing.";
    if (empty($phone))    $errors[] = "Phone Number is missing.";
    if (empty($message))  $errors[] = "Message is missing.";

    echo "<!DOCTYPE html><html><head><title>Submission Result</title></head><body>";

    if (!empty($errors)) {
        // Hiển thị lỗi Missing Data 
        echo "<h3 style='color: red;'>Missing Data Error</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo "<p><a href='contact.html'>Go Back</a></p>";
    } else {
        // Hiển thị dữ liệu theo danh sách có cấu trúc (Structured HTML list) [cite: 89]
        echo "<h3>Message Received Successfully</h3>";
        echo "<ul>";
        echo "<li><strong>Full Name:</strong> " . htmlspecialchars($fullName) . "</li>";
        echo "<li><strong>Email:</strong> " . htmlspecialchars($email) . "</li>";
        echo "<li><strong>Phone:</strong> " . htmlspecialchars($phone) . "</li>";
        echo "<li><strong>Message:</strong> " . nl2br(htmlspecialchars($message)) . "</li>";
        echo "</ul>";
        echo "<p><a href='contact.html'>Send another message</a></p>";
    }
    
    echo "</body></html>";
} else {
    // Nếu truy cập trực tiếp file này mà không qua POST, quay lại trang form [cite: 33]
    header("Location: contact.html");
    exit;
}