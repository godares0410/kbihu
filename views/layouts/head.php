<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="<?= asset('image/doc/kbih.png') ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>KBIHU | <?= $title ?? 'Dashboard' ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= asset('AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= asset('AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css') ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?= asset('AdminLTE-2/bower_components/Ionicons/css/ionicons.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= asset('AdminLTE-2/dist/css/AdminLTE.min.css') ?>">
    <!-- AdminLTE Skins -->
    <link rel="stylesheet" href="<?= asset('AdminLTE-2/dist/css/skins/_all-skins.min.css') ?>">
    
    <?php if (isset($styles)): ?>
        <?= $styles ?>
    <?php endif; ?>
</head>
