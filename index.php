<?php

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load autoloader
require_once __DIR__ . '/app/autoload.php';

// Load routes
$router = require __DIR__ . '/routes/web.php';

// Dispatch request
try {
    $router->dispatch();
} catch (Exception $e) {
    http_response_code(500);
    echo "<h1>500 Internal Server Error</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    error_log("Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
}
