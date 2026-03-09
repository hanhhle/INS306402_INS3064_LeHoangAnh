<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

// Access Control
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['user'];
$dataFile = __DIR__ . '/data/users.json';
$users = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
$userData = $users[$username] ?? null;

if (!$userData) {
    die("User record corrupted.");
}

$message = '';
$msgType = '';

// Handle Profile Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Bio Update with XSS Protection
    if (isset($_POST['bio'])) {
        // Sanitize the bio using htmlspecialchars to mitigate XSS vulnerabilities
        $users[$username]['bio'] = htmlspecialchars(trim($_POST['bio']), ENT_QUOTES, 'UTF-8');
        $message = "Profile updated successfully.";
        $msgType = "success";
    }

    // 2. Secure Avatar Upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['avatar']['tmp_name'];
        
        // Strictly block .exe and .pdf by evaluating the MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (!in_array($mimeType, $allowedTypes, true)) {
             $message = "Upload failed: Invalid file type. Executables and PDFs are strictly prohibited.";
             $msgType = "error";
        } else {
            $ext = explode('/', $mimeType)[1];
            $avatarName = uniqid($username . '_', true) . '.' . $ext;
            $destination = __DIR__ . '/uploads/' . $avatarName;

            if (!is_dir(__DIR__ . '/uploads/')) mkdir(__DIR__ . '/uploads/', 0755, true);

            if (move_uploaded_file($tmpName, $destination)) {
                $users[$username]['avatar'] = $avatarName;
                $message = "Avatar updated successfully.";
                $msgType = "success";
            }
        }
    }
    
    // Persist changes
    file_put_contents($dataFile, json_encode($users, JSON_PRETTY_PRINT));
    $userData = $users[$username]; // Refresh local data for the view
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Profile System</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Segoe UI', sans-serif; padding: 40px; }
        .dashboard-container { background: #1e1e1e; padding: 30px; border-radius: 8px; border: 1px solid #333; max-width: 600px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #fff; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .logout-btn:hover { background: #bb2d3b; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #b3b3b3; font-weight: bold; }
        textarea { width: 100%; height: 100px; padding: 10px; background: #2d2d2d; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box; resize: vertical; }
        input[type="file"] { background: #2d2d2d; padding: 10px; border-radius: 4px; border: 1px solid #444; width: 100%; box-sizing: border-box; }
        button[type="submit"] { background: #0d6efd; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button[type="submit"]:hover { background: #0b5ed7; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: rgba(25, 135, 84, 0.2); color: #75b798; border: 1px solid #198754; }
        .alert-error { background: rgba(220, 53, 69, 0.2); color: #ea868f; border: 1px solid #dc3545; }
        .profile-img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid #0dcaf0; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h2>Welcome, <?= htmlspecialchars($username) ?></h2>
            <a href="logout.php" class="logout-btn">Log Out</a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $msgType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Current Avatar</label>
                <?php if ($userData['avatar'] && $userData['avatar'] !== 'default.png'): ?>
                    <img src="uploads/<?= htmlspecialchars($userData['avatar']) ?>" alt="Avatar" class="profile-img">
                <?php else: ?>
                    <div style="width: 100px; height: 100px; background: #444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; color: #aaa;">No Avatar</div>
                <?php endif; ?>
                <input type="file" name="avatar" accept="image/jpeg, image/png, image/gif">
            </div>

            <div class="form-group">
                <label>Biography</label>
                <textarea name="bio" placeholder="Tell us about yourself..."><?= $userData['bio'] ?></textarea>
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>