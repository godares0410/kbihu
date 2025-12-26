<?php

class Peserta {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function all() {
        return $this->db->fetchAll("SELECT * FROM peserta ORDER BY id_peserta DESC");
    }

    public function find($id) {
        return $this->db->fetchOne(
            "SELECT * FROM peserta WHERE id_peserta = :id",
            ['id' => $id]
        );
    }

    public function findByNomor($nomor) {
        return $this->db->fetchOne(
            "SELECT * FROM peserta WHERE nomor_peserta = :nomor",
            ['nomor' => $nomor]
        );
    }

    public function create($data) {
        return $this->db->insert('peserta', $data);
    }

    public function update($id, $data) {
        return $this->db->update('peserta', $data, 'id_peserta = :id', ['id' => $id]);
    }

    public function delete($id) {
        // Delete related scans first
        $this->db->delete('scan', 'id_peserta = :id', ['id' => $id]);
        return $this->db->delete('peserta', 'id_peserta = :id', ['id' => $id]);
    }

    public function bulkDelete($ids) {
        if (empty($ids) || !is_array($ids)) {
            return false;
        }
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        // Delete related scans
        $sql = "DELETE FROM scan WHERE id_peserta IN ({$placeholders})";
        $this->db->query($sql, $ids);
        
        // Delete peserta
        $sql = "DELETE FROM peserta WHERE id_peserta IN ({$placeholders})";
        return $this->db->query($sql, $ids);
    }

    public function count() {
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM peserta");
        return $result['total'] ?? 0;
    }

    public function getByRombongan($rombongan) {
        return $this->db->fetchAll(
            "SELECT * FROM peserta WHERE rombongan = :rombongan",
            ['rombongan' => $rombongan]
        );
    }

    public function getByRegu($regu) {
        return $this->db->fetchAll(
            "SELECT * FROM peserta WHERE regu = :regu",
            ['regu' => $regu]
        );
    }

    public function getByRombonganAndRegu($rombongan, $regu) {
        return $this->db->fetchAll(
            "SELECT * FROM peserta WHERE rombongan = :rombongan AND regu = :regu",
            ['rombongan' => $rombongan, 'regu' => $regu]
        );
    }

    public function getDistinctRombongan() {
        $result = $this->db->fetchAll("SELECT DISTINCT rombongan FROM peserta WHERE rombongan IS NOT NULL ORDER BY rombongan");
        return array_column($result, 'rombongan');
    }

    public function getDistinctRegu() {
        $result = $this->db->fetchAll("SELECT DISTINCT regu FROM peserta WHERE regu IS NOT NULL ORDER BY regu");
        return array_column($result, 'regu');
    }
}
