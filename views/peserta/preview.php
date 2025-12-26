<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Preview Data Import</h3>
        </div>

        <?php if (count($invalidData) > 0): ?>
        <div class="box-body">
            <div class="alert alert-danger">
                <h4><i class="icon fa fa-ban"></i> Sebanyak <?= count($invalidData) ?> data gagal diimport!</h4>
                <p>Data berikut mengandung kesalahan dan tidak dapat diproses.</p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nomor Peserta</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Kecamatan</th>
                            <th>Rombongan</th>
                            <th>Regu</th>
                            <th>Keterangan</th>
                            <th>Embarkasi</th>
                            <th>Kloter</th>
                            <th>Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invalidData as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nomor_peserta'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['nama_peserta'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['alamat'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['kecamatan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['rombongan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['regu'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['keterangan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['embarkasi'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['kloter'] ?? '-') ?></td>
                            <td class="text-danger"><?= htmlspecialchars($item['error'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if (count($validData) > 0): ?>
        <div class="box-body">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i> Data Valid Siap Import (<?= count($validData) ?>)</h4>
                <p>Data berikut akan diimport ke sistem.</p>
            </div>
            
            <form action="<?= url('/data-peserta/import') ?>" method="POST">
                <input type="hidden" name="_token" value="<?= View::csrf() ?>">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nomor Peserta</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Kecamatan</th>
                                <th>Rombongan</th>
                                <th>Regu</th>
                                <th>Keterangan</th>
                                <th>Embarkasi</th>
                                <th>Kloter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($validData as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nomor_peserta']) ?></td>
                                <td><?= htmlspecialchars($item['nama_peserta']) ?></td>
                                <td><?= htmlspecialchars($item['alamat'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['kecamatan'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['rombongan'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['regu'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['keterangan'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['embarkasi'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['kloter'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Proses Import
                    </button>
                    <a href="<?= url('/data-peserta') ?>" class="btn btn-default">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
$title = 'Preview Import';
$styles = '<link rel="stylesheet" href="' . asset('AdminLTE-2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') . '">';
$scripts = '
<script src="' . asset('AdminLTE-2/bower_components/datatables.net/js/jquery.dataTables.min.js') . '"></script>
<script src="' . asset('AdminLTE-2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') . '"></script>
<script>
$(document).ready(function() {
    $(".table").DataTable();
});
</script>';
?>
