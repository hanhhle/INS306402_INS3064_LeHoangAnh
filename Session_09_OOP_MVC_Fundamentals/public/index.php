<?php
// 1. Automatically load classes when instantiated
spl_autoload_register(function ($class) {
    $paths = [
        '../app/Controllers/' . $class . '.php',
        '../app/Models/' . $class . '.php',
        '../core/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// 2. ERROR HANDLING & ROUTING
try {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/'); 
    
    $controller = new ProductController();

    if ($uri === '/products' || $uri === '/project-root/public/products') {
        $controller->index();
    } 
    elseif ($uri === '/products/create' || $uri === '/project-root/public/products/create') {
        $controller->create();
    } 
    elseif (strpos($uri, 'edit') !== false) {
        $id = $_GET['id'] ?? 1; 
        $controller->edit($id);
    } 
    elseif (strpos($uri, 'delete') !== false) {
        $id = $_GET['id'] ?? 1;
        $controller->delete($id);
    } 
    else {
        echo "<h1>404 Not Found</h1>";
        echo "<p>Please navigate to /products</p>";
    }

} catch (PDOException $e) {
    echo "<h1>Database Error!</h1>";
    echo "<p>Could not connect to the database. Please try again later.</p>";
    // Optional for debugging: echo $e->getMessage();
} catch (Exception $e) {
    echo "<h1>System Error!</h1>";
    echo "<p>Something went wrong: " . $e->getMessage() . "</p>";
}