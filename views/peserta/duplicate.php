<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Hasil Import Data Peserta</h3>
        </div>

        <?php if (count($insertedData) > 0): ?>
        <div class="box-body">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i> Data Berhasil Diimport (<?= count($insertedData) ?>)</h4>
                <p>Data berikut telah berhasil ditambahkan ke sistem.</p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nomor Peserta</th>
                            <th>Nama Peserta</th>
                            <th>Alamat</th>
                            <th>Kecamatan</th>
                            <th>Rombongan</th>
                            <th>Regu</th>
                            <th>Keterangan</th>
                            <th>Kloter</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($insertedData as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nomor_peserta']) ?></td>
                            <td><?= htmlspecialchars($row['nama_peserta']) ?></td>
                            <td><?= htmlspecialchars($row['alamat'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['kecamatan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['rombongan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['regu'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['kloter'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if (count($duplicateData) > 0): ?>
        <div class="box-body">
            <div class="alert alert-danger">
                <h4><i class="icon fa fa-ban"></i> Data Gagal Diimport (<?= count($duplicateData) ?>)</h4>
                <p>Nomor peserta berikut sudah digunakan dalam sistem.</p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nomor Peserta</th>
                            <th>Nama Peserta</th>
                            <th>Alamat</th>
                            <th>Kecamatan</th>
                            <th>Rombongan</th>
                            <th>Regu</th>
                            <th>Keterangan</th>
                            <th>Kloter</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($duplicateData as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nomor_peserta']) ?></td>
                            <td><?= htmlspecialchars($row['nama_peserta']) ?></td>
                            <td><?= htmlspecialchars($row['alamat'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['kecamatan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['rombongan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['regu'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['kloter'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <div class="box-footer">
            <a href="<?= url('/data-peserta') ?>" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Kembali ke Halaman Awal
            </a>
        </div>
    </div>
</section>

<?php
$title = 'Hasil Import';
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
