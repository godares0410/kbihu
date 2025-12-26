<?php

class DashboardController extends Controller {
    public function index() {
        $user = Auth::user();
        $pesertaModel = new Peserta();
        $scanModel = new Scan();

        $totalPeserta = $pesertaModel->count();
        $totalScan = $scanModel->countByStatus(0);
        
        $rombonganList = $pesertaModel->getDistinctRombongan();
        $totalRombongan = count($rombonganList);
        
        $reguList = $pesertaModel->getDistinctRegu();
        $totalRegu = count($reguList);

        $this->view('admin.dashboard', [
            'user' => $user,
            'userName' => $user['name'] ?? 'Admin',
            'totalPeserta' => $totalPeserta,
            'totalScan' => $totalScan,
            'totalRombongan' => $totalRombongan,
            'totalRegu' => $totalRegu,
            'title' => 'Dashboard',
        ]);
    }
}
