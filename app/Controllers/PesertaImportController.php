<?php

class PesertaImportController extends Controller {
    private $pesertaModel;

    public function __construct() {
        $this->pesertaModel = new Peserta();
    }

    public function preview() {
        if (!Request::hasFile('file')) {
            Response::with('error', 'File tidak ditemukan');
            $this->redirect('/absensi/data-peserta');
            return;
        }

        $file = Request::file('file');
        $filePath = $file['tmp_name'];
        
        // Check if PhpSpreadsheet is available
        if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
            // Fallback: try to read as CSV
            $this->previewCSV($filePath);
            return;
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            
            $validData = [];
            $invalidData = [];
            $rowNumber = 1;

            foreach ($sheet->getRowIterator(2) as $row) {
                $rowNumber++;
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                // Skip if all columns are empty
                if (empty(array_filter($rowData))) {
                    continue;
                }

                // Mapping data (assuming Excel format: A=index, B=nomor, C=nama, etc)
                $data = [
                    'nomor_peserta' => $rowData[1] ?? null,
                    'nama_peserta'  => $rowData[2] ?? null,
                    'alamat'        => $rowData[3] ?? null,
                    'kecamatan'     => $rowData[4] ?? null,
                    'rombongan'     => $rowData[5] ?? null,
                    'regu'          => $rowData[6] ?? null,
                    'keterangan'    => $rowData[7] ?? null,
                    'embarkasi'     => $rowData[8] ?? null,
                    'kloter'        => $rowData[9] ?? null,
                ];

                // Validate
                if (!empty($data['nomor_peserta']) && !empty($data['nama_peserta'])) {
                    $validData[] = $data;
                } else {
                    $data['error'] = 'Nomor peserta atau nama peserta kosong';
                    $invalidData[] = $data;
                }
            }

            // Save to session
            $_SESSION['valid_import_data'] = $validData;

            $this->view('peserta.preview', [
                'validData' => $validData,
                'invalidData' => $invalidData
            ]);
        } catch (Exception $e) {
            Response::with('error', 'Error membaca file: ' . $e->getMessage());
            $this->redirect('/absensi/data-peserta');
        }
    }

    private function previewCSV($filePath) {
        $validData = [];
        $invalidData = [];
        $rowNumber = 1;

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            // Skip header
            fgetcsv($handle);
            
            while (($rowData = fgetcsv($handle)) !== FALSE) {
                $rowNumber++;
                
                if (empty(array_filter($rowData))) {
                    continue;
                }

                $data = [
                    'nomor_peserta' => $rowData[1] ?? null,
                    'nama_peserta'  => $rowData[2] ?? null,
                    'alamat'        => $rowData[3] ?? null,
                    'kecamatan'     => $rowData[4] ?? null,
                    'rombongan'     => $rowData[5] ?? null,
                    'regu'          => $rowData[6] ?? null,
                    'keterangan'    => $rowData[7] ?? null,
                    'embarkasi'     => $rowData[8] ?? null,
                    'kloter'        => $rowData[9] ?? null,
                ];

                if (!empty($data['nomor_peserta']) && !empty($data['nama_peserta'])) {
                    $validData[] = $data;
                } else {
                    $data['error'] = 'Nomor peserta atau nama peserta kosong';
                    $invalidData[] = $data;
                }
            }
            fclose($handle);
        }

        $_SESSION['valid_import_data'] = $validData;
        $this->view('peserta.preview', [
            'validData' => $validData,
            'invalidData' => $invalidData
        ]);
    }

    public function processImport() {
        Request::validateCsrf();
        
        $data = $_SESSION['valid_import_data'] ?? [];

        if (empty($data)) {
            Response::with('error', 'Tidak ada data untuk diimport');
            $this->redirect('/absensi/data-peserta');
            return;
        }

        $inserted = [];
        $duplicateData = [];

        foreach ($data as $row) {
            // Check if nomor_peserta already exists
            if ($this->pesertaModel->findByNomor($row['nomor_peserta'])) {
                $duplicateData[] = $row;
            } else {
                $this->pesertaModel->create($row);
                $inserted[] = $row;
            }
        }

        // Clear session
        unset($_SESSION['valid_import_data']);

        $this->view('peserta.duplicate', [
            'insertedData' => $inserted,
            'duplicateData' => $duplicateData
        ]);
    }
}
