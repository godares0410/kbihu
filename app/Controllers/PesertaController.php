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
        Request::validateCsrf();
        
        $ids = Request::post('ids', []);
        
        if (empty($ids) || !is_array($ids)) {
            $this->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
            return;
        }

        // Get peserta to delete photos
        $pesertas = [];
        foreach ($ids as $id) {
            $peserta = $this->pesertaModel->find($id);
            if ($peserta && !empty($peserta['foto'])) {
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
        }

        $this->pesertaModel->bulkDelete($ids);
        $this->json(['success' => true, 'message' => 'Data peserta berhasil dihapus.']);
    }

    public function cetakKartu() {
        $pesertas = $this->pesertaModel->all();
        $this->view('peserta.cetak', ['pesertas' => $pesertas]);
    }
}
