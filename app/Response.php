<?php

class Response {
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function redirect($url, $statusCode = 302) {
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }

    public static function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/home');
        self::redirect($referer);
    }

    public static function with($key, $value) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'][$key] = $value;
        return new static();
    }

    public static function withErrors($errors) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash']['errors'] = $errors;
        return new static();
    }

    public static function withInput($data) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['old'] = $data;
        return new static();
    }
}
