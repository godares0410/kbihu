<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        
        try {
            $this->conn = new PDO(
                "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}",
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            if (php_sapi_name() !== 'cli') {
                http_response_code(500);
                echo "<!DOCTYPE html><html><head><title>Database Error</title></head><body>";
                echo "<h1>Database Connection Error</h1>";
                echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<h2>Langkah-langkah:</h2>";
                echo "<ol>";
                echo "<li>Pastikan MySQL sudah running di XAMPP</li>";
                echo "<li>Buat database 'absensi' di phpMyAdmin</li>";
                echo "<li>Import file absensidb.sql ke database</li>";
                echo "<li>Cek konfigurasi di config/database.php</li>";
                echo "</ol>";
                echo "<p><a href='http://localhost/phpmyadmin'>Buka phpMyAdmin</a></p>";
                echo "</body></html>";
            } else {
                die("Database connection failed: " . $e->getMessage());
            }
            exit;
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    public function insert($table, $data) {
        $fields = array_keys($data);
        $placeholders = ':' . implode(', :', $fields);
        $fieldsStr = implode(', ', $fields);
        
        $sql = "INSERT INTO {$table} ({$fieldsStr}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        return $this->conn->lastInsertId();
    }

    public function update($table, $data, $where, $whereParams = []) {
        $set = [];
        foreach (array_keys($data) as $field) {
            $set[] = "{$field} = :{$field}";
        }
        $setStr = implode(', ', $set);
        
        $sql = "UPDATE {$table} SET {$setStr} WHERE {$where}";
        $params = array_merge($data, $whereParams);
        return $this->query($sql, $params);
    }

    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($sql, $params);
    }
}
