<?php

class View {
    public static function render($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewFile)) {
            die("View not found: {$view}");
        }
        
        // Check if layout is needed
        if (!isset($data['noLayout']) || !$data['noLayout']) {
            // Capture view output
            ob_start();
            include $viewFile;
            $content = ob_get_clean();
            
            // Include layout with content variable
            $layout = __DIR__ . '/../views/layouts/app.php';
            if (file_exists($layout)) {
                // Extract data again to make sure all variables are available in layout
                extract($data);
                include $layout;
            } else {
                echo $content;
            }
        } else {
            include $viewFile;
        }
    }

    public static function asset($path) {
        $basePath = self::getBasePath();
        return $basePath . '/public/' . ltrim($path, '/');
    }

    public static function url($path = '') {
        $basePath = self::getBasePath();
        return $basePath . ($path ? '/' . ltrim($path, '/') : '');
    }
    
    /**
     * Get base path of the application
     */
    private static function getBasePath() {
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
        
        // Auto-detect from SCRIPT_NAME
        if (isset($_SERVER['SCRIPT_NAME'])) {
            $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
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

    public static function csrf() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function old($key, $default = '') {
        return $_SESSION['old'][$key] ?? $default;
    }

    public static function flash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
}
