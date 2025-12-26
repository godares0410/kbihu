<?php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find($id) {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE id = :id",
            ['id' => $id]
        );
    }

    public function findByEmail($email) {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE email = :email",
            ['email' => $email]
        );
    }

    public function all() {
        return $this->db->fetchAll("SELECT * FROM users ORDER BY id DESC");
    }

    public function create($data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->db->insert('users', $data);
    }

    public function update($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return $this->db->update('users', $data, 'id = :id', ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete('users', 'id = :id', ['id' => $id]);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
