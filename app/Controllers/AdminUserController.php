<?php

class AdminUserController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        $adminUsers = $this->userModel->all();
        $this->view('admin_user.index', [
            'adminUsers' => $adminUsers,
            'title' => 'Admin User'
        ]);
    }

    public function store() {
        Request::validateCsrf();
        
        $data = [
            'name' => Request::post('name'),
            'email' => Request::post('email'),
            'password' => Request::post('password'),
        ];

        // Validate
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            Response::with('error', 'Semua field harus diisi');
            $this->redirect('/absensi/admin-users');
            return;
        }

        // Check if email exists
        $existing = $this->userModel->findByEmail($data['email']);
        if ($existing) {
            Response::with('error', 'Email sudah terdaftar');
            $this->redirect('/absensi/admin-users');
            return;
        }

        // Handle file upload - check $_FILES directly
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['foto'];
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file['type'], $allowedTypes) || !in_array($extension, $allowedExtensions)) {
                Response::with('error', 'Format file tidak didukung. Gunakan JPEG, PNG, atau GIF');
                $this->redirect('/absensi/admin-users');
                return;
            }
            
            // Validate file size (max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                Response::with('error', 'Ukuran file terlalu besar. Maksimal 2MB');
                $this->redirect('/absensi/admin-users');
                return;
            }
            
            // Sanitize filename
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $uploadPath = __DIR__ . '/../../public/image/users/';
            
            // Create directory if not exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
                @chmod($uploadPath, 0777);
            }
            
            // Ensure directory is writable - try multiple permission levels
            if (!is_writable($uploadPath)) {
                @chmod($uploadPath, 0777);
                if (!is_writable($uploadPath)) {
                    @chmod($uploadPath, 0755);
                    if (!is_writable($uploadPath)) {
                        error_log("ERROR: Cannot write to upload path: " . $uploadPath);
                        error_log("Current permissions: " . substr(sprintf('%o', fileperms($uploadPath)), -4));
                        Response::with('error', 'Folder upload tidak dapat ditulis. Path: ' . $uploadPath);
                        $this->redirect('/absensi/admin-users');
                        return;
                    }
                }
            }
            
            $fullPath = $uploadPath . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $fullPath)) {
                // Verify file was created
                if (file_exists($fullPath)) {
                    $data['foto'] = $fileName;
                } else {
                    Response::with('error', 'File ter-upload tapi tidak ditemukan di server');
                    $this->redirect('/absensi/admin-users');
                    return;
                }
            } else {
                Response::with('error', 'Gagal mengupload foto. Periksa permission folder dan ukuran file');
                $this->redirect('/absensi/admin-users');
                return;
            }
        }

        $this->userModel->create($data);
        Response::with('success', 'Admin User berhasil ditambahkan');
        $this->redirect('/absensi/admin-users');
    }

    public function update($id) {
        Request::validateCsrf();
        
        // Debug: Log request method and files
        error_log("=== UPDATE USER DEBUG ===");
        error_log("Request Method: " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A'));
        error_log("_POST _method: " . ($_POST['_method'] ?? 'N/A'));
        error_log("_FILES present: " . (isset($_FILES['foto']) ? 'YES' : 'NO'));
        if (isset($_FILES['foto'])) {
            error_log("_FILES['foto']: " . print_r($_FILES['foto'], true));
        }
        
        $adminUser = $this->userModel->find($id);
        if (!$adminUser) {
            Response::with('error', 'User tidak ditemukan');
            $this->redirect('/absensi/admin-users');
            return;
        }

        $data = [
            'name' => Request::post('name'),
            'email' => Request::post('email'),
        ];

        // Check if email exists (excluding current)
        $existing = $this->userModel->findByEmail($data['email']);
        if ($existing && $existing['id'] != $id) {
            Response::with('error', 'Email sudah terdaftar');
            $this->redirect('/absensi/admin-users');
            return;
        }

        // Update password if provided
        $password = Request::post('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        // Handle file upload - check $_FILES directly for better compatibility with method override
        error_log("Checking for file upload...");
        error_log("_FILES dump: " . print_r($_FILES, true));
        if (isset($_FILES['foto']) && !empty($_FILES['foto']['name'])) {
            error_log("File found in _FILES");
            $file = $_FILES['foto'];
            error_log("File details - name: " . $file['name'] . ", size: " . $file['size'] . ", error: " . $file['error']);
            
            // Check upload error
            if ($file['error'] !== UPLOAD_ERR_OK) {
                error_log("Upload error detected: " . $file['error']);
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
                $this->redirect('/absensi/admin-users');
                return;
            }
            
            // Process file upload
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file['type'], $allowedTypes) || !in_array($extension, $allowedExtensions)) {
                Response::with('error', 'Format file tidak didukung. Gunakan JPEG, PNG, atau GIF');
                $this->redirect('/absensi/admin-users');
                return;
            }
            
            // Validate file size (max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                Response::with('error', 'Ukuran file terlalu besar. Maksimal 2MB');
                $this->redirect('/absensi/admin-users');
                return;
            }
            
            // Delete old photo
            if (!empty($adminUser['foto'])) {
                // Try both old location and new location
                $oldPath = __DIR__ . '/../../public/image/' . $adminUser['foto'];
                $oldPathUsers = __DIR__ . '/../../public/image/users/' . $adminUser['foto'];
                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
                if (file_exists($oldPathUsers) && is_file($oldPathUsers)) {
                    @unlink($oldPathUsers);
                }
            }

            // Sanitize filename - remove special characters
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $uploadPath = __DIR__ . '/../../public/image/users/';
            
            // Create directory if not exists
            if (!is_dir($uploadPath)) {
                error_log("Creating upload directory: " . $uploadPath);
                $created = @mkdir($uploadPath, 0777, true);
                @chmod($uploadPath, 0777);
                error_log("Directory created: " . ($created ? 'YES' : 'NO'));
            }
            
            // Check and log current permissions
            $currentPerms = substr(sprintf('%o', fileperms($uploadPath)), -4);
            error_log("Upload path: " . $uploadPath);
            error_log("Current permissions: " . $currentPerms);
            error_log("Is writable check: " . (is_writable($uploadPath) ? 'YES' : 'NO'));
            
            // Ensure directory is writable - try multiple permission levels
            if (!is_writable($uploadPath)) {
                error_log("Path not writable, attempting to fix permissions...");
                @chmod($uploadPath, 0777);
                $newPerms = substr(sprintf('%o', fileperms($uploadPath)), -4);
                error_log("After chmod 777, permissions: " . $newPerms);
                error_log("After chmod 777, is writable: " . (is_writable($uploadPath) ? 'YES' : 'NO'));
                
                if (!is_writable($uploadPath)) {
                    error_log("Still not writable after 777, trying 755...");
                    @chmod($uploadPath, 0755);
                    if (!is_writable($uploadPath)) {
                        error_log("ERROR: Cannot write to upload path: " . $uploadPath);
                        error_log("Final permissions: " . substr(sprintf('%o', fileperms($uploadPath)), -4));
                        Response::with('error', 'Folder upload tidak dapat ditulis. Path: ' . $uploadPath . ' (Permissions: ' . $currentPerms . ')');
                        $this->redirect('/absensi/admin-users');
                        return;
                    }
                }
            }
            
            $fullPath = $uploadPath . $fileName;
            
            error_log("Attempting to move uploaded file from: " . $file['tmp_name'] . " to: " . $fullPath);
            if (move_uploaded_file($file['tmp_name'], $fullPath)) {
                error_log("File moved successfully");
                // Verify file was created
                if (file_exists($fullPath)) {
                    error_log("File exists at: " . $fullPath);
                    $data['foto'] = $fileName;
                    error_log("Foto filename set to: " . $fileName);
                } else {
                    error_log("ERROR: File moved but does not exist at: " . $fullPath);
                    Response::with('error', 'File ter-upload tapi tidak ditemukan di server');
                    $this->redirect('/absensi/admin-users');
                    return;
                }
            } else {
                error_log("ERROR: move_uploaded_file failed. tmp_name: " . $file['tmp_name'] . ", fullPath: " . $fullPath);
                Response::with('error', 'Gagal mengupload foto. Periksa permission folder dan ukuran file');
                $this->redirect('/absensi/admin-users');
                return;
            }
        } else {
            error_log("No file uploaded - _FILES['foto'] not set or empty");
        } // End if isset $_FILES

        error_log("Data to update: " . print_r($data, true));
        // Update database
        try {
            $this->userModel->update($id, $data);
            error_log("Database update successful");
            Response::with('success', 'Admin User berhasil diupdate');
        } catch (Exception $e) {
            error_log("Database update failed: " . $e->getMessage());
            Response::with('error', 'Gagal update: ' . $e->getMessage());
        }
        error_log("=== END UPDATE USER DEBUG ===");
        $this->redirect('/absensi/admin-users');
    }

    public function destroy($id) {
        Request::validateCsrf();
        
        $user = $this->userModel->find($id);
        if (!$user) {
            Response::with('error', 'User tidak ditemukan');
            $this->redirect('/absensi/admin-users');
            return;
        }

        // Delete photo if exists
        if ($user['foto']) {
            // Try both old location and new location
            $filePath = __DIR__ . '/../../public/image/users/' . $user['foto'];
            $filePathOld = __DIR__ . '/../../public/image/' . $user['foto'];
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
            if (file_exists($filePathOld) && is_file($filePathOld)) {
                @unlink($filePathOld);
            }
        }

        $this->userModel->delete($id);
        Response::with('success', 'Admin User berhasil dihapus');
        $this->redirect('/absensi/admin-users');
    }
}
