<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" href="<?= asset('image/doc/kbih.png') ?>">
  <title>Login | KBIHU App</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
  <link rel="stylesheet" href="<?= asset('AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css') ?>">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: absolute;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
      background-size: 50px 50px;
      animation: moveBackground 20s linear infinite;
      opacity: 0.3;
    }

    @keyframes moveBackground {
      0% { transform: translate(0, 0); }
      100% { transform: translate(50px, 50px); }
    }

    .login-container {
      width: 100%;
      max-width: 420px;
      position: relative;
      z-index: 1;
      animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      padding: 40px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 25px 70px rgba(0, 0, 0, 0.35);
    }

    .login-header {
      text-align: center;
      margin-bottom: 35px;
    }

    .login-logo {
      font-size: 32px;
      font-weight: 700;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 10px;
      letter-spacing: 1px;
    }

    .login-subtitle {
      color: #666;
      font-size: 14px;
      margin-top: 8px;
    }

    .form-group {
      margin-bottom: 25px;
      position: relative;
    }

    .input-wrapper {
      position: relative;
    }

    .form-control {
      width: 100%;
      padding: 15px 15px 15px 50px;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      font-size: 15px;
      transition: all 0.3s ease;
      background: #f8f9fa;
    }

    .form-control:focus {
      outline: none;
      border-color: #667eea;
      background: #fff;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
      font-size: 18px;
      transition: color 0.3s ease;
    }

    .form-control:focus + .input-icon {
      color: #667eea;
    }


    .btn-login {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 12px;
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
      position: relative;
      overflow: hidden;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .btn-login:hover::before {
      width: 300px;
      height: 300px;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .btn-login span {
      position: relative;
      z-index: 1;
    }

    .alert {
      padding: 12px 16px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 14px;
      animation: shake 0.5s ease;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-10px); }
      75% { transform: translateX(10px); }
    }

    .alert-danger {
      background: #fee;
      border: 1px solid #fcc;
      color: #c33;
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 30px 25px;
      }

      .login-logo {
        font-size: 28px;
      }
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #999;
      font-size: 18px;
      transition: color 0.3s ease;
    }

    .password-toggle:hover {
      color: #667eea;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="login-logo">KBIHU App</div>
        <p class="login-subtitle">Masuk ke akun Anda untuk melanjutkan</p>
      </div>

      <?php if (hasErrors()): ?>
        <div class="alert alert-danger">
          <i class="fa fa-exclamation-circle"></i>
          <?php 
          $errors = errors();
          if (is_array($errors)) {
              echo implode('<br>', $errors);
          } else {
              echo $errors;
          }
          ?>
        </div>
      <?php endif; ?>

      <form action="<?= url('/login') ?>" method="POST" id="loginForm">
        <input type="hidden" name="_token" value="<?= View::csrf() ?>">
        
        <div class="form-group">
          <div class="input-wrapper">
            <input 
              type="email" 
              name="email" 
              class="form-control" 
              placeholder="Alamat Email" 
              value="<?= old('email') ?>"
              required
              autocomplete="email"
            >
            <i class="fa fa-envelope input-icon"></i>
          </div>
        </div>

        <div class="form-group">
          <div class="input-wrapper">
            <input 
              type="password" 
              name="password" 
              id="password"
              class="form-control" 
              placeholder="Kata Sandi"
              required
              autocomplete="current-password"
            >
            <i class="fa fa-lock input-icon"></i>
            <i class="fa fa-eye password-toggle" id="togglePassword" title="Tampilkan/Sembunyikan Password"></i>
          </div>
        </div>

        <button type="submit" class="btn-login">
          <span>Masuk</span>
        </button>
      </form>
    </div>
  </div>

  <script src="<?= asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') ?>"></script>
  <script>
    $(document).ready(function() {
      // Toggle password visibility
      $('#togglePassword').on('click', function() {
        const passwordInput = $('#password');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        $(this).toggleClass('fa-eye fa-eye-slash');
      });

      // Form validation
      $('#loginForm').on('submit', function(e) {
        const email = $('input[name="email"]').val();
        const password = $('input[name="password"]').val();
        
        if (!email || !password) {
          e.preventDefault();
          alert('Harap isi semua field yang diperlukan');
          return false;
        }
      });

      // Add focus animation
      $('.form-control').on('focus', function() {
        $(this).parent().addClass('focused');
      }).on('blur', function() {
        if (!$(this).val()) {
          $(this).parent().removeClass('focused');
        }
      });
    });
  </script>
</body>
</html>
