<?php

class Request {
    public static function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    public static function post($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    public static function input($key = null, $default = null) {
        $data = array_merge($_GET, $_POST);
        if ($key === null) {
            return $data;
        }
        return $data[$key] ?? $default;
    }

    public static function file($key = null) {
        if ($key === null) {
            return $_FILES;
        }
        return $_FILES[$key] ?? null;
    }

    public static function hasFile($key) {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }

    public static function method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function is($pattern) {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/absensi', '', $uri);
        $uri = $uri ?: '/';
        return fnmatch($pattern, $uri);
    }

    public static function validateCsrf() {
        $token = self::post('_token') ?? self::get('_token');
        if (!$token || $token !== ($_SESSION['csrf_token'] ?? '')) {
            die('CSRF token mismatch');
        }
    }
}
