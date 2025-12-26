<?php

class PesertaImportController extends Controller {
    private $pesertaModel;

    public function __construct() {
        $this->pesertaModel = new Peserta();
    }

    public function preview() {
        if (!Request::hasFile('file')) {
            Response::with('error', 'File tidak ditemukan');
            $this->redirect(url('/data-peserta'));
            return;
        }

        $file = Request::file('file');
        $filePath = $file['tmp_name'];
        $fileName = $file['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Validate file extension
        $allowedExtensions = ['xlsx', 'xls', 'csv'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            Response::with('error', 'Format file tidak didukung. Gunakan file Excel (.xlsx, .xls) atau CSV (.csv)');
            $this->redirect(url('/data-peserta'));
            return;
        }
        
        // If CSV, use CSV parser
        if ($fileExtension === 'csv') {
            $this->previewCSV($filePath);
            return;
        }
        
        // Try PhpSpreadsheet first
        if (class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
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
                        $value = $cell->getValue();
                        // Handle formula cells
                        if ($cell->getDataType() === 'f') {
                            $value = $cell->getCalculatedValue();
                        }
                        $rowData[] = $value;
                    }

                    // Skip if all columns are empty
                    if (empty(array_filter($rowData, function($v) { return $v !== null && $v !== ''; }))) {
                        continue;
                    }

                    // Mapping data (assuming Excel format: A=index, B=nomor, C=nama, etc)
                    $data = [
                        'nomor_peserta' => $this->cleanValue($rowData[1] ?? null),
                        'nama_peserta'  => $this->cleanValue($rowData[2] ?? null),
                        'alamat'        => $this->cleanValue($rowData[3] ?? null),
                        'kecamatan'     => $this->cleanValue($rowData[4] ?? null),
                        'rombongan'     => $this->cleanValue($rowData[5] ?? null),
                        'regu'          => $this->cleanValue($rowData[6] ?? null),
                        'keterangan'    => $this->cleanValue($rowData[7] ?? null),
                        'embarkasi'     => $this->cleanValue($rowData[8] ?? null),
                        'kloter'        => $this->cleanValue($rowData[9] ?? null),
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
                return;
            } catch (Exception $e) {
                error_log("PhpSpreadsheet error: " . $e->getMessage());
                // Fall through to SimpleXLSX
            }
        }
        
        // Try SimpleXLSX as fallback
        if (file_exists(__DIR__ . '/../lib/SimpleXLSX.php')) {
            require_once __DIR__ . '/../lib/SimpleXLSX.php';
            
            if (class_exists('Shuchkin\SimpleXLSX')) {
                try {
                    $xlsx = \Shuchkin\SimpleXLSX::parse($filePath);
                    if (!$xlsx) {
                        throw new Exception('Gagal membaca file Excel: ' . \Shuchkin\SimpleXLSX::parseError());
                    }
                    
                    $rows = $xlsx->rows();
                    $validData = [];
                    $invalidData = [];
                    
                    // Skip header row (index 0)
                    for ($i = 1; $i < count($rows); $i++) {
                        $rowData = $rows[$i];
                        
                        // Skip if all columns are empty
                        if (empty(array_filter($rowData, function($v) { return $v !== null && $v !== ''; }))) {
                            continue;
                        }
                        
                        // Mapping data
                        $data = [
                            'nomor_peserta' => $this->cleanValue($rowData[1] ?? null),
                            'nama_peserta'  => $this->cleanValue($rowData[2] ?? null),
                            'alamat'        => $this->cleanValue($rowData[3] ?? null),
                            'kecamatan'     => $this->cleanValue($rowData[4] ?? null),
                            'rombongan'     => $this->cleanValue($rowData[5] ?? null),
                            'regu'          => $this->cleanValue($rowData[6] ?? null),
                            'keterangan'    => $this->cleanValue($rowData[7] ?? null),
                            'embarkasi'     => $this->cleanValue($rowData[8] ?? null),
                            'kloter'        => $this->cleanValue($rowData[9] ?? null),
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
                    return;
                } catch (Exception $e) {
                    error_log("SimpleXLSX error: " . $e->getMessage());
                    Response::with('error', 'Error membaca file Excel: ' . $e->getMessage());
                    $this->redirect(url('/data-peserta'));
                    return;
                }
            }
        }
        
        // If we get here, no Excel parser is available
        Response::with('error', 'Library untuk membaca file Excel tidak tersedia. Silakan install PhpSpreadsheet atau gunakan format CSV.');
        $this->redirect('/absensi/data-peserta');
    }
    
    private function cleanValue($value) {
        if ($value === null) {
            return null;
        }
        
        // Convert to string and trim
        $value = trim((string)$value);
        
        // Remove null bytes and other control characters (except newlines and tabs)
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);
        
        return $value === '' ? null : $value;
    }

    private function previewCSV($filePath) {
        $validData = [];
        $invalidData = [];
        $rowNumber = 1;

        // Try to detect encoding
        $content = file_get_contents($filePath);
        $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
            file_put_contents($filePath . '_utf8', $content);
            $filePath = $filePath . '_utf8';
        }

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            // Detect delimiter
            $firstLine = fgets($handle);
            rewind($handle);
            
            $delimiter = ',';
            if (strpos($firstLine, ';') !== false && substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                $delimiter = ';';
            } elseif (strpos($firstLine, "\t") !== false) {
                $delimiter = "\t";
            }
            
            // Skip header
            fgetcsv($handle, 0, $delimiter);
            
            while (($rowData = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                $rowNumber++;
                
                // Clean row data
                $rowData = array_map(function($v) {
                    return $this->cleanValue($v);
                }, $rowData);
                
                if (empty(array_filter($rowData, function($v) { return $v !== null && $v !== ''; }))) {
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
            
            // Clean up temporary file
            if (file_exists($filePath . '_utf8')) {
                @unlink($filePath . '_utf8');
            }
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
            $this->redirect(url('/data-peserta'));
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
