<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// PHẦN 1: LOGIC XỬ LÝ (CONTROLLER/LOGIC)

$message = '';
$messageType = ''; // 'success' hoặc 'error'

// Xử lý khi form được submit qua phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Kiểm tra xem file có được upload lên không và không có lỗi từ hệ thống
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        
        $tmpName = $_FILES['avatar']['tmp_name'];
        $fileSize = (int) $_FILES['avatar']['size'];
        
        // 1. Validate dung lượng (Tối đa 2MB = 2 * 1024 * 1024 bytes)
        $maxSize = 2 * 1024 * 1024;
        
        if ($fileSize > $maxSize) {
            $message = "Upload failed: File size exceeds the 2MB limit.";
            $messageType = "error";
        } else {
            // 2. Validate MIME type an toàn bằng finfo (Không dùng $_FILES['type'] vì dễ bị giả mạo)
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tmpName);
            finfo_close($finfo);
            
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            
            if (!in_array($mimeType, $allowedMimeTypes, true)) {
                $message = "Upload failed: Invalid file format. Only JPG and PNG are allowed.";
                $messageType = "error";
            } else {
                // 3. Đổi tên file để tránh bị ghi đè (Prevent overwrites)
                $extension = ($mimeType === 'image/jpeg') ? 'jpg' : 'png';
                // Tạo ID unique dựa trên thời gian microsecond
                $uniqueFileName = uniqid('avatar_', true) . '.' . $extension; 
                
                // 4. Di chuyển file vào thư mục uploads/
                $uploadDir = __DIR__ . '/uploads/';
                
                // Tự động tạo thư mục nếu chưa tồn tại
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $destination = $uploadDir . $uniqueFileName;
                
                if (move_uploaded_file($tmpName, $destination)) {
                    $message = "Success: Avatar uploaded securely! (File: $uniqueFileName)";
                    $messageType = "success";
                } else {
                    $message = "Upload failed: Could not move the file to the server.";
                    $messageType = "error";
                }
            }
        }
    } else {
        // Xử lý các lỗi cơ bản của $_FILES
        $uploadError = $_FILES['avatar']['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($uploadError === UPLOAD_ERR_NO_FILE) {
            $message = "Please select a file to upload.";
        } else {
            $message = "Upload failed with error code: " . $uploadError;
        }
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Avatar Upload</title>
    <style>
        /* Dark Theme CSS theo yêu cầu */
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .upload-container {
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 450px;
            border: 1px solid #333;
        }
        h2 {
            margin-top: 0;
            color: #ffffff;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.95em;
            color: #b3b3b3;
        }
        input[type="file"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #2d2d2d;
            border: 1px solid #444;
            border-radius: 5px;
            color: #e0e0e0;
            cursor: pointer;
        }
        input[type="file"]::file-selector-button {
            background-color: #3a3a3a;
            color: #e0e0e0;
            border: none;
            padding: 8px 12px;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 15px;
            transition: background 0.2s;
        }
        input[type="file"]::file-selector-button:hover {
            background-color: #505050;
        }
        button[type="submit"] {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
            font-weight: bold;
            transition: background 0.2s;
        }
        button[type="submit"]:hover {
            background-color: #0b5ed7;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9em;
            line-height: 1.4;
        }
        .alert-success {
            background-color: rgba(25, 135, 84, 0.2);
            color: #75b798;
            border: 1px solid #198754;
        }
        .alert-error {
            background-color: rgba(220, 53, 69, 0.2);
            color: #ea868f;
            border: 1px solid #dc3545;
        }
    </style>
</head>
<body>

    <div class="upload-container">
        <h2>Update Profile Avatar</h2>
        
        <?php if ($message !== ''): ?>
            <div class="alert alert-<?= htmlspecialchars($messageType) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="avatar">Select Image (JPG/PNG, Max 2MB)</label>
                <input type="file" name="avatar" id="avatar" accept="image/jpeg, image/png" required>
            </div>
            
            <button type="submit">Secure Upload</button>
        </form>
    </div>

</body>
</html>