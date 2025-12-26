<?php

class Scan {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function all() {
        return $this->db->fetchAll("SELECT * FROM scan ORDER BY created_at DESC");
    }

    public function find($id) {
        return $this->db->fetchOne(
            "SELECT * FROM scan WHERE id_scan = :id",
            ['id' => $id]
        );
    }

    public function findByPeserta($idPeserta, $status = 0) {
        return $this->db->fetchOne(
            "SELECT * FROM scan WHERE id_peserta = :id_peserta AND status = :status",
            ['id_peserta' => $idPeserta, 'status' => $status]
        );
    }

    public function create($data) {
        return $this->db->insert('scan', $data);
    }

    public function update($id, $data) {
        return $this->db->update('scan', $data, 'id_scan = :id', ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete('scan', 'id_scan = :id', ['id' => $id]);
    }

    public function getSudahScan($status = 0) {
        return $this->db->fetchAll(
            "SELECT s.*, p.nama_peserta, p.foto, p.nomor_peserta, p.rombongan, p.regu, p.kecamatan, p.kloter 
             FROM scan s 
             JOIN peserta p ON s.id_peserta = p.id_peserta 
             WHERE s.status = :status 
             ORDER BY s.created_at DESC",
            ['status' => $status]
        );
    }

    public function getBelumScan() {
        return $this->db->fetchAll(
            "SELECT p.* 
             FROM peserta p 
             WHERE p.id_peserta NOT IN (
                 SELECT id_peserta FROM scan WHERE status = 0
             )"
        );
    }

    public function getByNama($nama) {
        return $this->db->fetchAll(
            "SELECT s.*, p.nama_peserta, p.foto, p.nomor_peserta, p.rombongan, p.regu, p.kecamatan, p.kloter 
             FROM scan s 
             JOIN peserta p ON s.id_peserta = p.id_peserta 
             WHERE s.nama = :nama 
             ORDER BY s.created_at DESC",
            ['nama' => $nama]
        );
    }

    public function getSudahScanByNama($nama) {
        return $this->db->fetchAll(
            "SELECT s.*, p.nama_peserta, p.foto, p.nomor_peserta, p.rombongan, p.regu, p.kecamatan, p.kloter 
             FROM scan s 
             JOIN peserta p ON s.id_peserta = p.id_peserta 
             WHERE s.nama = :nama AND s.scan = 1 
             ORDER BY s.created_at DESC",
            ['nama' => $nama]
        );
    }

    public function getBelumScanByNama($nama) {
        return $this->db->fetchAll(
            "SELECT s.*, p.nama_peserta, p.foto, p.nomor_peserta, p.rombongan, p.regu, p.kecamatan, p.kloter 
             FROM scan s 
             JOIN peserta p ON s.id_peserta = p.id_peserta 
             WHERE s.nama = :nama AND s.scan = 0 
             ORDER BY s.created_at DESC",
            ['nama' => $nama]
        );
    }

    public function countByStatus($status = 0) {
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM scan WHERE status = :status",
            ['status' => $status]
        );
        return $result['total'] ?? 0;
    }

    public function groupByNama() {
        return $this->db->fetchAll(
            "SELECT nama, COUNT(*) as total FROM scan GROUP BY nama ORDER BY nama DESC"
        );
    }

    public function rekap($namaAbsensi) {
        $db = $this->db->getConnection();
        
        try {
            $db->beginTransaction();
            
            // Get all active scanned participants
            $activeScanned = $this->db->fetchAll(
                "SELECT id_peserta FROM scan WHERE status = 0"
            );
            $activeScannedIds = array_column($activeScanned, 'id_peserta');
            
            // Update active scans to status=1
            if (!empty($activeScannedIds)) {
                $placeholders = implode(',', array_fill(0, count($activeScannedIds), '?'));
                $this->db->query(
                    "UPDATE scan SET status = 1, nama = ?, updated_at = NOW() WHERE status = 0",
                    array_merge([$namaAbsensi], $activeScannedIds)
                );
            }
            
            // Find missing participants
            $missing = $this->db->fetchAll(
                "SELECT id_peserta FROM peserta WHERE id_peserta NOT IN (" . 
                (empty($activeScannedIds) ? '0' : implode(',', $activeScannedIds)) . ")"
            );
            
            // Insert records for missing participants
            foreach ($missing as $participant) {
                $this->db->insert('scan', [
                    'id_peserta' => $participant['id_peserta'],
                    'nama' => $namaAbsensi,
                    'status' => 1,
                    'scan' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function deleteByNama($nama) {
        return $this->db->delete('scan', 'nama = :nama', ['nama' => $nama]);
    }
}
