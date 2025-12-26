<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" href="<?= asset('image/doc/kbih.png') ?>">
  <title>Login | KBIHU App</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
  <link rel="stylesheet" href="<?= asset('AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('AdminLTE-2/dist/css/AdminLTE.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('AdminLTE-2/plugins/iCheck/square/blue.css') ?>">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>KBIHU</b>App</a>
  </div>
  <div class="login-box-body">
    <p class="login-box-msg">Login untuk memulai sesi</p>

    <?php if (hasErrors()): ?>
      <div class="alert alert-danger">
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

    <form action="<?= url('/login') ?>" method="POST">
      <input type="hidden" name="_token" value="<?= View::csrf() ?>">
      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" placeholder="Email" value="<?= old('email') ?>">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember"> Ingat Saya
            </label>
          </div>
        </div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="<?= asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
<script src="<?= asset('AdminLTE-2/plugins/iCheck/icheck.min.js') ?>"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
  });
</script>
</body>
</html>
