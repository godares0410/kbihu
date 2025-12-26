<?php

class LoginController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLoginForm() {
        if (Auth::check()) {
            $this->redirect('/absensi/home');
            return;
        }
        $this->view('login.index', ['noLayout' => true]);
    }

    public function login() {
        $email = Request::post('email');
        $password = Request::post('password');

        if (empty($email) || empty($password)) {
            Response::withErrors(['email' => 'Email dan password harus diisi'])
                ->withInput(['email' => $email]);
            $this->redirect('/absensi/login');
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            Response::withErrors(['email' => 'Email atau password salah']);
            $this->redirect('/absensi/login');
            return;
        }

        unset($user['password']);
        Auth::login($user);
        
        $this->redirect('/absensi/home');
    }

    public function logout() {
        Auth::logout();
        $this->redirect('/absensi/login');
    }
}
