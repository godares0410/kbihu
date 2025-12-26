<?php

/**
 * Get base path of the application
 * Detects from REQUEST_URI or uses config
 */
function getBasePath() {
    static $basePath = null;
    
    if ($basePath !== null) {
        return $basePath;
    }
    
    // Try to get from config first
    if (file_exists(__DIR__ . '/../config/app.php')) {
        $config = require __DIR__ . '/../config/app.php';
        if (isset($config['url'])) {
            $url = parse_url($config['url'], PHP_URL_PATH);
            if ($url && $url !== '/') {
                $basePath = rtrim($url, '/');
                return $basePath;
            }
        }
    }
    
    // Auto-detect from REQUEST_URI
    if (isset($_SERVER['REQUEST_URI'])) {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // Get directory of index.php
        $scriptDir = dirname($scriptName);
        if ($scriptDir === '/' || $scriptDir === '\\') {
            $basePath = '';
        } else {
            $basePath = $scriptDir;
        }
    } else {
        // Fallback to /absensi for localhost
        $basePath = '/absensi';
    }
    
    return $basePath;
}

function asset($path) {
    // Remove leading slash if exists
    $path = ltrim($path, '/');
    $basePath = getBasePath();
    // Return full path to public directory
    return $basePath . '/public/' . $path;
}

function url($path = '') {
    $basePath = getBasePath();
    return $basePath . ($path ? '/' . ltrim($path, '/') : '');
}

function route($name, $params = []) {
    // Simple route helper - can be enhanced
    return url($name);
}

function auth() {
    return Auth::user();
}

function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

function old($key, $default = '') {
    return $_SESSION['old'][$key] ?? $default;
}

function flash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

function errors() {
    return $_SESSION['flash']['errors'] ?? [];
}

function hasErrors() {
    return !empty($_SESSION['flash']['errors'] ?? []);
}
