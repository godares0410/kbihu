<?php

class ScanController extends Controller {
    private $pesertaModel;
    private $scanModel;

    public function __construct() {
        $this->pesertaModel = new Peserta();
        $this->scanModel = new Scan();
    }

    public function index() {
        $totalPeserta = $this->pesertaModel->count();
        
        // Get sudah scan
        $sudahScan = $this->scanModel->getSudahScan(0);
        
        // Convert timezone
        foreach ($sudahScan as &$scan) {
            $date = new DateTime($scan['created_at'], new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Riyadh'));
            $scan['waktu_scan'] = $date->format('H:i:s');
        }
        
        $idSudahScan = array_column($sudahScan, 'id_peserta');
        
        // Get belum scan
        $belumScan = $this->scanModel->getBelumScan();
        
        // Statistics
        $rombonganStats = [];
        $reguStats = [];
        
        foreach ($belumScan as $peserta) {
            $rombongan = $peserta['rombongan'] ?? 0;
            $regu = $peserta['regu'] ?? 0;
            
            if (!isset($rombonganStats[$rombongan])) {
                $rombonganStats[$rombongan] = 0;
            }
            $rombonganStats[$rombongan]++;
            
            if (!isset($reguStats[$regu])) {
                $reguStats[$regu] = 0;
            }
            $reguStats[$regu]++;
        }
        
        $totalSudahScan = count($idSudahScan);
        $totalBelumScan = $totalPeserta - $totalSudahScan;

        $this->view('scan.index', [
            'noLayout' => true,
            'totalPeserta' => $totalPeserta,
            'sudahScan' => $sudahScan,
            'belumScan' => $belumScan,
            'totalSudahScan' => $totalSudahScan,
            'totalBelumScan' => $totalBelumScan,
            'rombonganStats' => $rombonganStats,
            'reguStats' => $reguStats
        ]);
    }

    public function store() {
        $kode = Request::post('kode');
        $localTime = Request::post('local_time');

        if (empty($kode) || empty($localTime)) {
            $this->json([
                'success' => false,
                'message' => 'Kode dan waktu harus diisi!'
            ], 400);
            return;
        }

        $peserta = $this->pesertaModel->findByNomor($kode);
        
        if (!$peserta) {
            $this->json([
                'success' => false,
                'message' => 'Peserta tidak ditemukan!'
            ], 404);
            return;
        }

        // Check if already scanned
        $alreadyScanned = $this->scanModel->findByPeserta($peserta['id_peserta'], 0);
        if ($alreadyScanned) {
            $this->json([
                'success' => false,
                'message' => 'Peserta sudah melakukan scan sebelumnya!'
            ], 400);
            return;
        }

        // Create scan record
        $this->scanModel->create([
            'id_peserta' => $peserta['id_peserta'],
            'status' => 0,
            'scan' => 1,
            'created_at' => $localTime,
            'updated_at' => $localTime
        ]);

        $date = new DateTime($localTime);
        $this->json([
            'success' => true,
            'message' => 'Scan berhasil!',
            'data' => [
                'nama_peserta' => $peserta['nama_peserta'],
                'waktu_scan' => $date->format('H:i:s')
            ]
        ]);
    }

    public function getBelumScan() {
        $rombongan = Request::get('rombongan');
        $regu = Request::get('regu');

        $db = Database::getInstance();
        $query = "SELECT p.id_peserta, p.nama_peserta, p.nomor_peserta, p.foto, p.rombongan, p.regu 
                  FROM peserta p 
                  WHERE p.id_peserta NOT IN (SELECT id_peserta FROM scan WHERE status = 0)";
        
        $params = [];
        
        if (!empty($rombongan)) {
            $query .= " AND p.rombongan = :rombongan";
            $params['rombongan'] = $rombongan;
        }
        
        if (!empty($regu)) {
            $query .= " AND p.regu = :regu";
            $params['regu'] = $regu;
        }

        $pesertas = $db->fetchAll($query, $params);

        $this->json([
            'success' => true,
            'data' => $pesertas
        ]);
    }
}
