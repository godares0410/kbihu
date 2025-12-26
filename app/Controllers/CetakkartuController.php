<?php

class CetakkartuController extends Controller {
    private $pesertaModel;

    public function __construct() {
        $this->pesertaModel = new Peserta();
    }

    public function index() {
        $user = Auth::user();
        $totalPeserta = $this->pesertaModel->count();
        
        $scanModel = new Scan();
        $totalScan = $scanModel->countByStatus(0);
        
        $rombonganList = $this->pesertaModel->getDistinctRombongan();
        $totalRombongan = count($rombonganList);
        
        $reguList = $this->pesertaModel->getDistinctRegu();
        $totalRegu = count($reguList);

        $this->view('data_kartu.index', [
            'user' => $user,
            'userName' => $user['name'] ?? 'Admin',
            'totalPeserta' => $totalPeserta,
            'totalScan' => $totalScan,
            'totalRombongan' => $totalRombongan,
            'totalRegu' => $totalRegu,
            'rombonganList' => $rombonganList,
            'reguList' => $reguList
        ]);
    }

    public function show() {
        $rombongan = Request::get('rombongan', 'semua');
        $regu = Request::get('regu', 'semua');

        $query = "SELECT * FROM peserta WHERE 1=1";
        $params = [];

        if ($rombongan !== 'semua') {
            $query .= " AND rombongan = :rombongan";
            $params['rombongan'] = $rombongan;
        }

        if ($regu !== 'semua') {
            $query .= " AND regu = :regu";
            $params['regu'] = $regu;
        }

        $db = Database::getInstance();
        $pesertas = $db->fetchAll($query, $params);

        // Use noLayout for print page
        $this->view('peserta.cetak', ['pesertas' => $pesertas, 'noLayout' => true]);
    }

    public function getRegu($rombongan) {
        $db = Database::getInstance();
        $regus = $db->fetchAll(
            "SELECT DISTINCT regu FROM peserta WHERE rombongan = :rombongan ORDER BY regu",
            ['rombongan' => $rombongan]
        );
        
        $reguList = array_column($regus, 'regu');
        $this->json($reguList);
    }
}
