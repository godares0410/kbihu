<?php
// Debug script to check server environment
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Server Environment Debug</h1>";

echo "<h2>Server Variables:</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>File Checks:</h2>";
$files = [
    'index.php',
    'app/autoload.php',
    'config/database.php',
    'config/app.php',
    'routes/web.php',
    'app/Controllers/LoginController.php',
    'views/login/index.php'
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    echo $file . ": " . (file_exists($path) ? "✓ EXISTS" : "✗ NOT FOUND") . "<br>";
}

echo "<h2>PHP Version:</h2>";
echo phpversion() . "<br>";

echo "<h2>Database Connection Test:</h2>";
try {
    $config = require __DIR__ . '/config/database.php';
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}",
        $config['username'],
        $config['password']
    );
    echo "✓ Database connection: OK<br>";
} catch (PDOException $e) {
    echo "✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "<br>";
}

echo "<h2>Autoload Test:</h2>";
try {
    require __DIR__ . '/app/autoload.php';
    echo "✓ Autoload: OK<br>";
    
    if (class_exists('Router')) {
        echo "✓ Router class: OK<br>";
    } else {
        echo "✗ Router class: NOT FOUND<br>";
    }
    
    if (class_exists('LoginController')) {
        echo "✓ LoginController class: OK<br>";
    } else {
        echo "✗ LoginController class: NOT FOUND<br>";
    }
} catch (Exception $e) {
    echo "✗ Autoload failed: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}

echo "<h2>Config Test:</h2>";
try {
    $config = require __DIR__ . '/config/app.php';
    echo "App URL: " . htmlspecialchars($config['url']) . "<br>";
    $urlPath = parse_url($config['url'], PHP_URL_PATH);
    echo "Parsed path: " . htmlspecialchars($urlPath) . "<br>";
} catch (Exception $e) {
    echo "✗ Config failed: " . htmlspecialchars($e->getMessage()) . "<br>";
}

