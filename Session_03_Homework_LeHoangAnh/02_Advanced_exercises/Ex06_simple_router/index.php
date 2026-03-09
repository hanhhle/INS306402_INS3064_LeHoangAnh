<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 1. Retrieve the requested page parameter (default to 'home')
$requestedPage = $_GET['page'] ?? 'home';

// 2. Security: Define a strict whitelist of allowed routes
// This prevents Local File Inclusion (LFI) vulnerabilities.
$allowedRoutes = ['home', 'contact'];

// 3. Routing Logic
if (in_array($requestedPage, $allowedRoutes, true)) {
    $viewFile = __DIR__ . '/pages/' . $requestedPage . '.php';
} else {
    // Handle 404 Not Found
    http_response_code(404);
    $viewFile = __DIR__ . '/pages/404.php';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Router MVC</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; display: flex; flex-direction: column; min-height: 100vh; }
        header { background: #1e1e1e; padding: 20px; border-bottom: 1px solid #333; display: flex; justify-content: space-between; align-items: center; }
        header h1 { margin: 0; font-size: 1.5em; color: #fff; }
        nav a { color: #0dcaf0; text-decoration: none; margin-left: 20px; font-weight: bold; }
        nav a:hover { color: #fff; }
        main { flex: 1; padding: 40px; display: flex; justify-content: center; }
        .content-box { background: #1e1e1e; padding: 30px; border-radius: 8px; border: 1px solid #333; width: 100%; max-width: 600px; }
        footer { background: #1e1e1e; text-align: center; padding: 15px; border-top: 1px solid #333; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>

    <header>
        <h1>System Architecture Prototype</h1>
        <nav>
            <a href="index.php?page=home">Home</a>
            <a href="index.php?page=contact">Contact</a>
            <a href="index.php?page=invalid">Trigger 404</a>
        </nav>
    </header>

    <main>
        <div class="content-box">
            <?php
            // 4. Inject the dynamically determined view
            if (file_exists($viewFile)) {
                require_once $viewFile;
            } else {
                echo "<h2>System Error</h2><p>View file is missing from the server.</p>";
            }
            ?>
        </div>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Front Controller Example.
    </footer>

</body>
</html>