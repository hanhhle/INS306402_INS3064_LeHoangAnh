<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$currentMethod = $_SERVER['REQUEST_METHOD'];
$superglobalData = [];
$superglobalName = '';

// Kiểm tra xem dữ liệu đang được gửi qua array nào
if ($currentMethod === 'POST') {
    $superglobalData = $_POST;
    $superglobalName = '$_POST';
} elseif ($currentMethod === 'GET' && !empty($_GET)) {
    // Chỉ hiển thị $_GET nếu thực sự có query string (bỏ qua lần load đầu tiên)
    $superglobalData = $_GET;
    $superglobalName = '$_GET';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GET vs POST Toggle</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Segoe UI', sans-serif; padding: 40px; }
        .wrapper { display: flex; gap: 30px; max-width: 900px; margin: 0 auto; }
        .form-section, .result-section { background: #1e1e1e; padding: 25px; border-radius: 8px; flex: 1; border: 1px solid #333; }
        h2 { margin-top: 0; color: #fff; border-bottom: 1px solid #444; padding-bottom: 10px; font-size: 1.3em;}
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #aaa; }
        input[type="text"] { width: 100%; padding: 8px; background: #2d2d2d; border: 1px solid #555; color: #fff; border-radius: 4px; box-sizing: border-box; }
        
        /* Radio buttons styling */
        .radio-group { display: flex; gap: 15px; margin: 20px 0; background: #2a2a2a; padding: 15px; border-radius: 5px; }
        .radio-group label { display: inline-flex; align-items: center; margin: 0; cursor: pointer; color: #fff; }
        .radio-group input { margin-right: 8px; cursor: pointer; }
        
        button { background: #198754; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
        button:hover { background: #157347; }
        
        pre { background: #000; color: #0f0; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 0.9em; border: 1px solid #333; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 3px; font-weight: bold; font-size: 0.8em; }
        .badge-get { background: #0dcaf0; color: #000; }
        .badge-post { background: #fd7e14; color: #fff; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="form-section">
            <h2>Data Submission Form</h2>
            
            <form id="dynamicForm" action="" method="GET">
                <div class="form-group">
                    <label>Search Keyword (or Name):</label>
                    <input type="text" name="query" value="PHP 8 Strict Types" required>
                </div>
                
                <div class="radio-group">
                    <label>
                        <input type="radio" name="method_choice" value="GET" checked onchange="toggleMethod(this.value)"> 
                        Send via GET
                    </label>
                    <label>
                        <input type="radio" name="method_choice" value="POST" onchange="toggleMethod(this.value)"> 
                        Send via POST
                    </label>
                </div>
                
                <button type="submit">Submit Data</button>
            </form>
        </div>

        <div class="result-section">
            <h2>Server Output</h2>
            <p>Detected Request Method: 
                <span class="badge <?= $currentMethod === 'GET' ? 'badge-get' : 'badge-post' ?>">
                    <?= htmlspecialchars($currentMethod) ?>
                </span>
            </p>

            <?php if (!empty($superglobalData)): ?>
                <p>Array Contents (<strong><?= $superglobalName ?></strong>):</p>
                <pre><?= htmlspecialchars(print_r($superglobalData, true)) ?></pre>
                
                <?php if ($currentMethod === 'GET'): ?>
                    <p style="color: #ffc107; font-size: 0.85em;">
                        Notice: Check your browser's URL. The data is visible there!
                    </p>
                <?php else: ?>
                    <p style="color: #20c997; font-size: 0.85em;">
                        Notice: The URL is clean. Data was sent hidden inside the HTTP Request Body.
                    </p>
                <?php endif; ?>
            <?php else: ?>
                <p style="color: #888;">No data submitted yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleMethod(method) {
            const form = document.getElementById('dynamicForm');
            form.method = method;
            
            // Xóa name của thẻ input radio trước khi submit để rác không bị đẩy lên URL (nếu dùng GET)
            const radios = document.getElementsByName('method_choice');
            radios.forEach(r => r.removeAttribute('name'));
            
            // Restore name nếu user chưa submit mà click qua lại
            setTimeout(() => {
                radios.forEach(r => r.setAttribute('name', 'method_choice'));
            }, 100);
        }
    </script>
</body>
</html>