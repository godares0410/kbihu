<?php

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/';
    
    // Try direct file first (for classes like Router, Auth, etc.)
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
        return;
    }
    
    // Try Controllers folder
    $file = $baseDir . 'Controllers/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
        return;
    }
    
    // Try Models folder
    $file = $baseDir . 'Models/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
        return;
    }
});

// Load core classes first
require __DIR__ . '/Database.php';
require __DIR__ . '/Auth.php';
require __DIR__ . '/Router.php';
require __DIR__ . '/View.php';
require __DIR__ . '/Request.php';
require __DIR__ . '/Response.php';

// Load helpers
require __DIR__ . '/helpers.php';
