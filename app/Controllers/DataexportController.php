<?php

class DataexportController extends Controller {
    private $scanModel;

    public function __construct() {
        $this->scanModel = new Scan();
    }

    public function index() {
        $dataGrouped = $this->scanModel->groupByNama();
        $this->view('data_export.index', ['dataGrouped' => $dataGrouped]);
    }

    public function destroy($nama) {
        Request::validateCsrf();
        
        $this->scanModel->deleteByNama($nama);
        Response::with('success', 'Data berhasil dihapus');
        $this->redirect('/absensi/data-export');
    }

    public function export($nama, $format) {
        // Get sudah scan
        $sudahScan = $this->scanModel->getSudahScanByNama($nama);
        foreach ($sudahScan as &$scan) {
            $scan['waktu_scan'] = date('d-m-Y H:i', strtotime($scan['created_at']));
        }
        $countSudahScan = count($sudahScan);

        // Get belum scan
        $belumScan = $this->scanModel->getBelumScanByNama($nama);
        foreach ($belumScan as &$scan) {
            $scan['waktu_scan'] = '-';
        }
        $countBelumScan = count($belumScan);

        if ($format === 'pdf') {
            $this->exportPDF($nama, $sudahScan, $belumScan, $countSudahScan, $countBelumScan);
        } elseif ($format === 'excel') {
            $this->exportExcel($nama, $sudahScan, $belumScan, $countSudahScan, $countBelumScan);
        } else {
            Response::with('error', 'Format tidak dikenali');
            $this->redirect('/absensi/data-export');
        }
    }

    private function exportPDF($nama, $sudahScan, $belumScan, $countSudahScan, $countBelumScan) {
        // Use TCPDF or FPDF if available, otherwise simple HTML
        $html = $this->generatePDFHTML($nama, $sudahScan, $belumScan, $countSudahScan, $countBelumScan);
        
        // Try to use dompdf if available
        if (class_exists('Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $dompdf->stream("scan_{$nama}.pdf");
        } else {
            // Fallback: output HTML
            header('Content-Type: text/html; charset=utf-8');
            echo $html;
        }
    }

    private function exportExcel($nama, $sudahScan, $belumScan, $countSudahScan, $countBelumScan) {
        // Try to use PhpSpreadsheet if available
        if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            
            // Sheet 1: Sudah Scan
            $sheet1 = $spreadsheet->getActiveSheet();
            $sheet1->setTitle('Sudah Scan');
            $sheet1->setCellValue('A1', 'Laporan Scan Peserta: ' . $nama);
            $sheet1->setCellValue('A2', 'Total Sudah Scan: ' . $countSudahScan);
            $sheet1->setCellValue('A4', 'No');
            $sheet1->setCellValue('B4', 'Nama Peserta');
            $sheet1->setCellValue('C4', 'Nomor Peserta');
            $sheet1->setCellValue('D4', 'Kecamatan');
            $sheet1->setCellValue('E4', 'Regu');
            $sheet1->setCellValue('F4', 'Rombongan');
            $sheet1->setCellValue('G4', 'Kloter');
            $sheet1->setCellValue('H4', 'Waktu Scan');
            
            $row = 5;
            $no = 1;
            foreach ($sudahScan as $scan) {
                $sheet1->setCellValue('A' . $row, $no++);
                $sheet1->setCellValue('B' . $row, $scan['nama_peserta']);
                $sheet1->setCellValue('C' . $row, $scan['nomor_peserta']);
                $sheet1->setCellValue('D' . $row, $scan['kecamatan'] ?? '-');
                $sheet1->setCellValue('E' . $row, $scan['regu'] ?? '-');
                $sheet1->setCellValue('F' . $row, $scan['rombongan'] ?? '-');
                $sheet1->setCellValue('G' . $row, $scan['kloter'] ?? '-');
                $sheet1->setCellValue('H' . $row, $scan['waktu_scan'] ?? '-');
                $row++;
            }
            
            // Sheet 2: Belum Scan
            $sheet2 = $spreadsheet->createSheet();
            $sheet2->setTitle('Belum Scan');
            $sheet2->setCellValue('A1', 'Laporan Scan Peserta: ' . $nama);
            $sheet2->setCellValue('A2', 'Total Belum Scan: ' . $countBelumScan);
            $sheet2->setCellValue('A4', 'No');
            $sheet2->setCellValue('B4', 'Nama Peserta');
            $sheet2->setCellValue('C4', 'Nomor Peserta');
            $sheet2->setCellValue('D4', 'Kecamatan');
            $sheet2->setCellValue('E4', 'Regu');
            $sheet2->setCellValue('F4', 'Rombongan');
            $sheet2->setCellValue('G4', 'Kloter');
            $sheet2->setCellValue('H4', 'Waktu Scan');
            
            $row = 5;
            $no = 1;
            foreach ($belumScan as $scan) {
                $sheet2->setCellValue('A' . $row, $no++);
                $sheet2->setCellValue('B' . $row, $scan['nama_peserta']);
                $sheet2->setCellValue('C' . $row, $scan['nomor_peserta']);
                $sheet2->setCellValue('D' . $row, $scan['kecamatan'] ?? '-');
                $sheet2->setCellValue('E' . $row, $scan['regu'] ?? '-');
                $sheet2->setCellValue('F' . $row, $scan['rombongan'] ?? '-');
                $sheet2->setCellValue('G' . $row, $scan['kloter'] ?? '-');
                $sheet2->setCellValue('H' . $row, '-');
                $row++;
            }
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="scan_' . $nama . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        } else {
            // Fallback: CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment;filename="scan_' . $nama . '.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['No', 'Nama Peserta', 'Nomor Peserta', 'Kecamatan', 'Regu', 'Rombongan', 'Kloter', 'Waktu Scan']);
            
            $no = 1;
            foreach ($sudahScan as $scan) {
                fputcsv($output, [
                    $no++,
                    $scan['nama_peserta'],
                    $scan['nomor_peserta'],
                    $scan['kecamatan'] ?? '-',
                    $scan['regu'] ?? '-',
                    $scan['rombongan'] ?? '-',
                    $scan['kloter'] ?? '-',
                    $scan['waktu_scan'] ?? '-'
                ]);
            }
            
            fclose($output);
            exit;
        }
    }

    private function generatePDFHTML($nama, $sudahScan, $belumScan, $countSudahScan, $countBelumScan) {
        ob_start();
        include __DIR__ . '/../../views/scan/export_pdf.php';
        return ob_get_clean();
    }
}
