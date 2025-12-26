<?php

require_once __DIR__ . '/../app/autoload.php';

Auth::start();

$router = new Router();

// Middleware
$router->middleware('auth', function() {
    if (!Auth::check()) {
        // Check if this is an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            Response::json(['success' => false, 'message' => 'Unauthorized. Please login.'], 401);
            return false;
        }
        Response::redirect('/absensi/login');
        return false;
    }
    return true;
});

// Root redirect
$router->get('/', function() {
    Response::redirect('/absensi/login');
});

// Login routes (no auth required)
$router->get('/login', [
    'controller' => 'LoginController',
    'action' => 'showLoginForm'
]);

$router->post('/login', [
    'controller' => 'LoginController',
    'action' => 'login'
]);

// Protected routes
$router->post('/logout', [
    'controller' => 'LoginController',
    'action' => 'logout',
    'middleware' => ['auth']
]);

$router->get('/home', [
    'controller' => 'DashboardController',
    'action' => 'index',
    'middleware' => ['auth']
]);

// Peserta routes
$router->get('/data-peserta', [
    'controller' => 'PesertaController',
    'action' => 'index',
    'middleware' => ['auth']
]);

$router->post('/peserta', [
    'controller' => 'PesertaController',
    'action' => 'store',
    'middleware' => ['auth']
]);

$router->post('/peserta/{id}', [
    'controller' => 'PesertaController',
    'action' => 'update',
    'middleware' => ['auth']
]);

$router->put('/peserta/{id}', [
    'controller' => 'PesertaController',
    'action' => 'update',
    'middleware' => ['auth']
]);

$router->delete('/peserta/{id}', [
    'controller' => 'PesertaController',
    'action' => 'destroy',
    'middleware' => ['auth']
]);

$router->post('/peserta/bulk-delete', [
    'controller' => 'PesertaController',
    'action' => 'bulkDelete',
    'middleware' => ['auth']
]);

$router->get('/cetak-kartu', [
    'controller' => 'PesertaController',
    'action' => 'cetakKartu',
    'middleware' => ['auth']
]);

// Peserta Import
$router->post('/data-peserta/preview', [
    'controller' => 'PesertaImportController',
    'action' => 'preview',
    'middleware' => ['auth']
]);

$router->post('/data-peserta/import', [
    'controller' => 'PesertaImportController',
    'action' => 'processImport',
    'middleware' => ['auth']
]);

// Peserta Print
$router->get('/peserta/print/{id}', [
    'controller' => 'PesertaPrintController',
    'action' => 'print',
    'middleware' => ['auth']
]);

// Scan routes (public access)
$router->get('/scan', [
    'controller' => 'ScanController',
    'action' => 'index'
]);

$router->post('/scan/store', [
    'controller' => 'ScanController',
    'action' => 'store'
]);

$router->get('/scan/belum-scan', [
    'controller' => 'ScanController',
    'action' => 'getBelumScan'
]);

// Data Scan routes
$router->get('/data-scan', [
    'controller' => 'DatascanController',
    'action' => 'index',
    'middleware' => ['auth']
]);

$router->delete('/data-scan/{id}', [
    'controller' => 'DatascanController',
    'action' => 'destroy',
    'middleware' => ['auth']
]);

$router->post('/data-scan/rekap', [
    'controller' => 'DatascanController',
    'action' => 'rekap',
    'middleware' => ['auth']
]);

// Data Export routes
$router->get('/data-export', [
    'controller' => 'DataexportController',
    'action' => 'index',
    'middleware' => ['auth']
]);

$router->delete('/data-export/{nama}', [
    'controller' => 'DataexportController',
    'action' => 'destroy',
    'middleware' => ['auth']
]);

$router->get('/data-export/export/{nama}/{format}', [
    'controller' => 'DataexportController',
    'action' => 'export',
    'middleware' => ['auth']
]);

// Cetak Kartu routes
$router->get('/data-cetak', [
    'controller' => 'CetakkartuController',
    'action' => 'index',
    'middleware' => ['auth']
]);

$router->get('/data-cetak/cetak', [
    'controller' => 'CetakkartuController',
    'action' => 'show',
    'middleware' => ['auth']
]);

$router->get('/get-regu/{rombongan}', [
    'controller' => 'CetakkartuController',
    'action' => 'getRegu',
    'middleware' => ['auth']
]);

// Admin Users routes
$router->get('/admin-users', [
    'controller' => 'AdminUserController',
    'action' => 'index',
    'middleware' => ['auth']
]);

$router->post('/admin-users', [
    'controller' => 'AdminUserController',
    'action' => 'store',
    'middleware' => ['auth']
]);

$router->post('/admin-users/{id}', [
    'controller' => 'AdminUserController',
    'action' => 'update',
    'middleware' => ['auth']
]);

$router->put('/admin-users/{id}', [
    'controller' => 'AdminUserController',
    'action' => 'update',
    'middleware' => ['auth']
]);

$router->delete('/admin-users/{id}', [
    'controller' => 'AdminUserController',
    'action' => 'destroy',
    'middleware' => ['auth']
]);

// Users routes
$router->get('/users', [
    'controller' => 'UsersController',
    'action' => 'index',
    'middleware' => ['auth']
]);

return $router;
