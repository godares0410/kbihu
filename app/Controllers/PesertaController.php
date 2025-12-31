<?php

class PesertaController extends Controller {
    private $pesertaModel;

    public function __construct() {
        $this->pesertaModel = new Peserta();
    }

    public function index() {
        $peserta = $this->pesertaModel->all();
        $this->view('data_umum.peserta.index', [
            'peserta' => $peserta,
            'title' => 'Data Peserta'
        ]);
    }

    public function store() {
        Request::validateCsrf();
        
        $data = [
            'nomor_peserta' => Request::post('nomor_peserta'),
            'nama_peserta' => Request::post('nama_peserta'),
            'alamat' => Request::post('alamat', ''),
            'kecamatan' => Request::post('kecamatan', ''),
            'rombongan' => Request::post('rombongan', 0),
            'regu' => Request::post('regu', 0),
            'keterangan' => Request::post('keterangan', ''),
            'embarkasi' => Request::post('embarkasi', ''),
            'kloter' => Request::post('kloter', ''),
        ];

        // Handle file upload - check $_FILES directly
        if (isset($_FILES['foto']) && !empty($_FILES['foto']['name'])) {
            $file = $_FILES['foto'];
            
            // Check upload error
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi upload_max_filesize)',
                    UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi MAX_FILE_SIZE)',
                    UPLOAD_ERR_PARTIAL => 'File hanya ter-upload sebagian',
                    UPLOAD_ERR_NO_FILE => 'Tidak ada file yang di-upload',
                    UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
                    UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
                    UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension'
                ];
                $errorMsg = $errorMessages[$file['error']] ?? 'Error upload tidak diketahui: ' . $file['error'];
                Response::with('error', $errorMsg);
                $this->redirect('/absensi/data-peserta');
                return;
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file['type'], $allowedTypes) || !in_array($extension, $allowedExtensions)) {
                Response::with('error', 'Format file tidak didukung. Gunakan JPEG, PNG, atau GIF');
                $this->redirect('/absensi/data-peserta');
                return;
            }
            
            // Validate file size (max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                Response::with('error', 'Ukuran file terlalu besar. Maksimal 2MB');
                $this->redirect('/absensi/data-peserta');
                return;
            }
            
            // Sanitize filename
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $uploadPath = __DIR__ . '/../../public/image/peserta/';
            
            // Create directory if not exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
                @chmod($uploadPath, 0777);
            }
            
            // Ensure directory is writable
            if (!is_writable($uploadPath)) {
                @chmod($uploadPath, 0777);
                if (!is_writable($uploadPath)) {
                    Response::with('error', 'Folder upload tidak dapat ditulis. Periksa permission folder');
                    $this->redirect('/absensi/data-peserta');
                    return;
                }
            }
            
            $fullPath = $uploadPath . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $fullPath)) {
                // Verify file was created
                if (file_exists($fullPath)) {
                    $data['foto'] = $fileName;
                } else {
                    Response::with('error', 'File ter-upload tapi tidak ditemukan di server');
                    $this->redirect('/absensi/data-peserta');
                    return;
                }
            } else {
                Response::with('error', 'Gagal mengupload foto. Periksa permission folder dan ukuran file');
                $this->redirect('/absensi/data-peserta');
                return;
            }
        }

        // Check if nomor_peserta already exists
        $existing = $this->pesertaModel->findByNomor($data['nomor_peserta']);
        if ($existing) {
            Response::with('error', 'Nomor peserta sudah ada');
            $this->redirect('/absensi/data-peserta');
            return;
        }

        $this->pesertaModel->create($data);
        Response::with('success', 'Data peserta berhasil ditambahkan.');
        $this->redirect('/absensi/data-peserta');
    }

    public function update($id) {
        Request::validateCsrf();
        
        $peserta = $this->pesertaModel->find($id);
        if (!$peserta) {
            Response::with('error', 'Data tidak ditemukan');
            $this->redirect('/absensi/data-peserta');
            return;
        }

        $data = [
            'nomor_peserta' => Request::post('nomor_peserta'),
            'nama_peserta' => Request::post('nama_peserta'),
            'alamat' => Request::post('alamat', ''),
            'kecamatan' => Request::post('kecamatan', ''),
            'rombongan' => Request::post('rombongan', 0),
            'regu' => Request::post('regu', 0),
            'keterangan' => Request::post('keterangan', ''),
            'embarkasi' => Request::post('embarkasi', ''),
            'kloter' => Request::post('kloter', ''),
        ];

        // Check if nomor_peserta already exists (excluding current)
        $existing = $this->pesertaModel->findByNomor($data['nomor_peserta']);
        if ($existing && $existing['id_peserta'] != $id) {
            Response::with('error', 'Nomor peserta sudah ada');
            $this->redirect('/absensi/data-peserta');
            return;
        }

        // Handle file upload - check $_FILES directly
        if (isset($_FILES['foto']) && !empty($_FILES['foto']['name'])) {
            $file = $_FILES['foto'];
            
            // Check upload error
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi upload_max_filesize)',
                    UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi MAX_FILE_SIZE)',
                    UPLOAD_ERR_PARTIAL => 'File hanya ter-upload sebagian',
                    UPLOAD_ERR_NO_FILE => 'Tidak ada file yang di-upload',
                    UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
                    UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
                    UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension'
                ];
                $errorMsg = $errorMessages[$file['error']] ?? 'Error upload tidak diketahui: ' . $file['error'];
                Response::with('error', $errorMsg);
                $this->redirect('/absensi/data-peserta');
                return;
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file['type'], $allowedTypes) || !in_array($extension, $allowedExtensions)) {
                Response::with('error', 'Format file tidak didukung. Gunakan JPEG, PNG, atau GIF');
                $this->redirect('/absensi/data-peserta');
                return;
            }
            
            // Validate file size (max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                Response::with('error', 'Ukuran file terlalu besar. Maksimal 2MB');
                $this->redirect('/absensi/data-peserta');
                return;
            }
            
            // Delete old photo
            if (!empty($peserta['foto'])) {
                // Try both old location and new location
                $oldPath = __DIR__ . '/../../public/image/peserta/' . $peserta['foto'];
                $oldPathLegacy = __DIR__ . '/../../public/image/' . $peserta['foto'];
                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
                if (file_exists($oldPathLegacy) && is_file($oldPathLegacy)) {
                    @unlink($oldPathLegacy);
                }
            }

            // Sanitize filename
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $uploadPath = __DIR__ . '/../../public/image/peserta/';
            
            // Create directory if not exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
                @chmod($uploadPath, 0777);
            }
            
            // Ensure directory is writable
            if (!is_writable($uploadPath)) {
                @chmod($uploadPath, 0777);
                if (!is_writable($uploadPath)) {
                    Response::with('error', 'Folder upload tidak dapat ditulis. Periksa permission folder');
                    $this->redirect('/absensi/data-peserta');
                    return;
                }
            }
            
            $fullPath = $uploadPath . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $fullPath)) {
                // Verify file was created
                if (file_exists($fullPath)) {
                    $data['foto'] = $fileName;
                } else {
                    Response::with('error', 'File ter-upload tapi tidak ditemukan di server');
                    $this->redirect('/absensi/data-peserta');
                    return;
                }
            } else {
                Response::with('error', 'Gagal mengupload foto. Periksa permission folder dan ukuran file');
                $this->redirect('/absensi/data-peserta');
                return;
            }
        }

        $this->pesertaModel->update($id, $data);
        Response::with('success', 'Data berhasil diperbarui.');
        $this->redirect('/absensi/data-peserta');
    }

    public function destroy($id) {
        Request::validateCsrf();
        
        $peserta = $this->pesertaModel->find($id);
        if (!$peserta) {
            $this->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            return;
        }

        // Delete photo if exists
        if (!empty($peserta['foto'])) {
            // Try both new location and legacy location
            $filePath = __DIR__ . '/../../public/image/peserta/' . $peserta['foto'];
            $filePathLegacy = __DIR__ . '/../../public/image/' . $peserta['foto'];
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
            if (file_exists($filePathLegacy) && is_file($filePathLegacy)) {
                @unlink($filePathLegacy);
            }
        }

        $this->pesertaModel->delete($id);
        Response::with('success', 'Data berhasil dihapus.');
        $this->redirect('/absensi/data-peserta');
    }

    public function bulkDelete() {
        // Log immediately to confirm method is called
        error_log("=== bulkDelete() METHOD CALLED ===");
        error_log("PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'N/A'));
        error_log("REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
        error_log("REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A'));
        
        // Clear any output buffers immediately
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Read JSON from request body
        $jsonInput = file_get_contents('php://input');
        
        error_log("=== bulkDelete() CONTINUING ===");
        error_log("JSON Input: " . $jsonInput);
        error_log("IS_AJAX: " . (defined('IS_AJAX') && IS_AJAX ? 'YES' : 'NO'));
        error_log("Session ID: " . session_id());
        error_log("Session data: " . print_r($_SESSION, true));
        
        // Decode JSON
        $data = json_decode($jsonInput, true);
        
        // Validate JSON data
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg());
            $this->json(['success' => false, 'message' => 'Invalid JSON data: ' . json_last_error_msg()], 400);
            return; // Extra safety
        }
        
        // Validate CSRF token
        $token = $data['_token'] ?? null;
        $sessionToken = $_SESSION['csrf_token'] ?? null;
        
        error_log("CSRF Token from JSON: " . ($token ?? 'NULL'));
        error_log("CSRF Token from SESSION: " . ($sessionToken ?? 'NULL'));
        error_log("Tokens match: " . ($token === $sessionToken ? 'YES' : 'NO'));
        
        if (!$token || !$sessionToken || $token !== $sessionToken) {
            error_log("CSRF token mismatch! JSON: '$token', SESSION: '$sessionToken'");
            error_log("About to call json() with CSRF error");
            error_log("Output buffer level before json(): " . ob_get_level());
            
            // Clear any output buffers before calling json()
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            
            $this->json(['success' => false, 'message' => 'CSRF token mismatch'], 403);
            return; // Extra safety (though json() should exit)
        }
        
        // Get IDs from JSON
        $ids = $data['ids'] ?? [];
        error_log("IDs from JSON: " . print_r($ids, true));
        
        if (empty($ids) || !is_array($ids)) {
            error_log("No IDs provided");
            $this->json(['success' => false, 'message' => 'Tidak ada data yang dipilih'], 400);
        }

        // Validate IDs are numeric
        $ids = array_values(array_filter(array_map('intval', $ids), function($id) {
            return $id > 0;
        }));
        
        error_log("Validated IDs: " . print_r($ids, true));
        
        if (empty($ids)) {
            error_log("No valid IDs");
            $this->json(['success' => false, 'message' => 'ID tidak valid'], 400);
        }

        // Get peserta to delete photos
        foreach ($ids as $id) {
            $peserta = $this->pesertaModel->find($id);
            if ($peserta && !empty($peserta['foto'])) {
                $filePath = __DIR__ . '/../../public/image/peserta/' . $peserta['foto'];
                if (file_exists($filePath) && is_file($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        error_log("Calling bulkDelete on model with IDs: " . print_r($ids, true));
        $result = $this->pesertaModel->bulkDelete($ids);
        error_log("bulkDelete result: " . ($result ? 'SUCCESS' : 'FAILED'));
        
        if ($result) {
            error_log("Returning success JSON");
            $this->json([
                'success' => true,
                'message' => count($ids) . ' data peserta berhasil dihapus.'
            ]);
        } else {
            error_log("Returning error JSON");
            $this->json(['success' => false, 'message' => 'Gagal menghapus data peserta.'], 500);
        }
    }
    

    public function cetakKartu() {
        $pesertas = $this->pesertaModel->all();
        $this->view('peserta.cetak', ['pesertas' => $pesertas]);
    }
}
