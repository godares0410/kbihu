<!-- jQuery 3 -->
<script src="<?= asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= asset('AdminLTE-2/dist/js/adminlte.min.js') ?>"></script>

<?php if (isset($scripts)): ?>
    <?= $scripts ?>
<?php endif; ?>
