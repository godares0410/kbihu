<?php

class PesertaPrintController extends Controller {
    private $pesertaModel;

    public function __construct() {
        $this->pesertaModel = new Peserta();
    }

    public function print($id) {
        $peserta = $this->pesertaModel->find($id);
        
        if (!$peserta) {
            Response::with('error', 'Data peserta tidak ditemukan');
            $this->redirect('/absensi/data-peserta');
            return;
        }

        // Generate QR Code
        $qrCode = $this->generateQRCode($peserta['nomor_peserta']);

        // Try to use dompdf if available
        if (class_exists('Dompdf\Dompdf')) {
            ob_start();
            include __DIR__ . '/../../views/peserta/print.php';
            $html = ob_get_clean();
            
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('peserta_' . $peserta['nomor_peserta'] . '.pdf');
            exit;
        } else {
            // Fallback: show HTML directly
            include __DIR__ . '/../../views/peserta/print.php';
            exit;
        }
    }

    private function generateQRCode($text) {
        // Try to use SimpleSoftwareIO QR Code if available
        if (class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode')) {
            return \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($text);
        }
        
        // Fallback: use online QR code API
        $size = 100;
        $url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($text);
        return '<img src="' . $url . '" alt="QR Code">';
    }
}
