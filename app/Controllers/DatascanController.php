<?php

class DatascanController extends Controller {
    private $scanModel;

    public function __construct() {
        $this->scanModel = new Scan();
    }

    public function index() {
        $status = Request::get('status', 1); // 1 = Sudah Scan, 0 = Belum Scan

        if ($status == 1) {
            // Sudah Scan
            $scans = $this->scanModel->getSudahScan(0);
            foreach ($scans as &$scan) {
                $scan['waktu_scan'] = date('d-m-Y H:i', strtotime($scan['created_at']));
            }
        } else {
            // Belum Scan
            $pesertaModel = new Peserta();
            $belumScan = $pesertaModel->all();
            $sudahScanIds = array_column($this->scanModel->getSudahScan(0), 'id_peserta');
            
            $scans = [];
            foreach ($belumScan as $peserta) {
                if (!in_array($peserta['id_peserta'], $sudahScanIds)) {
                    $scans[] = [
                        'id_scan' => null,
                        'nama_peserta' => $peserta['nama_peserta'],
                        'created_at' => null,
                        'foto' => $peserta['foto'],
                        'nomor_peserta' => $peserta['nomor_peserta'],
                        'rombongan' => $peserta['rombongan'],
                        'regu' => $peserta['regu'],
                        'kecamatan' => $peserta['kecamatan'],
                        'kloter' => $peserta['kloter'],
                        'waktu_scan' => '-'
                    ];
                }
            }
        }

        $this->view('data_scan.index', [
            'scans' => $scans,
            'status' => $status
        ]);
    }

    public function destroy($id) {
        Request::validateCsrf();
        
        try {
            $this->scanModel->delete($id);
            Response::with('success', 'Data scan berhasil dihapus');
        } catch (Exception $e) {
            Response::with('error', 'Gagal menghapus data scan');
        }
        
        $this->redirect('/absensi/data-scan');
    }

    public function rekap() {
        Request::validateCsrf();
        
        $namaAbsensi = Request::post('nama_absensi');
        
        if (empty($namaAbsensi)) {
            Response::with('error', 'Nama absensi harus diisi');
            $this->redirect('/absensi/data-scan');
            return;
        }

        try {
            $this->scanModel->rekap($namaAbsensi);
            Response::with('success', 'Rekap absensi berhasil disimpan.');
        } catch (Exception $e) {
            Response::with('error', 'Gagal menyimpan rekap absensi: ' . $e->getMessage());
        }
        
        $this->redirect('/absensi/data-scan');
    }
}
