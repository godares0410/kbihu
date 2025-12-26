<?php

class Auth {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login($user) {
        self::start();
        $_SESSION['user'] = $user;
        $_SESSION['logged_in'] = true;
    }

    public static function logout() {
        self::start();
        unset($_SESSION['user']);
        unset($_SESSION['logged_in']);
        session_destroy();
    }

    public static function check() {
        self::start();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public static function user() {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function id() {
        $user = self::user();
        return $user['id'] ?? null;
    }
}
