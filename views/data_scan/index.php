<section class="content">
    <?php if ($success = flash('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <?php if ($error = flash('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Gagal!</h4>
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="box-title">Data Scan Absensi</h3>
                </div>
                <div class="col-md-6">
                    <div class="pull-right" style="display: flex; align-items: center; gap: 10px;">
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalRekapAbsensi">
                            <i class="fa fa-file-excel-o"></i> Rekap Absensi
                        </button>
                        <form action="<?= url('/data-scan') ?>" method="GET" class="form-inline">
                            <div class="form-group">
                                <label class="control-label">Status: </label>
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="1" <?= ($status == 1) ? 'selected' : '' ?>>Sudah Scan</option>
                                    <option value="0" <?= ($status == 0) ? 'selected' : '' ?>>Belum Scan</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped" id="scanTable">
                <thead>
                    <tr>
                        <th style="width: 20px">No</th>
                        <th>Nomor Peserta</th>
                        <th>Nama Peserta</th>
                        <th>Kecamatan</th>
                        <th>Rombongan</th>
                        <th>Regu</th>
                        <th>Kloter</th>
                        <?php if ($status == 1): ?>
                        <th>Waktu Scan</th>
                        <th class="text-center">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scans as $index => $scan): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($scan['nomor_peserta'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($scan['nama_peserta'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($scan['kecamatan'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($scan['rombongan'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($scan['regu'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($scan['kloter'] ?? '-') ?></td>
                        <?php if ($status == 1): ?>
                        <td><?= htmlspecialchars($scan['waktu_scan'] ?? '-') ?></td>
                        <td class="text-center">
                            <?php if (isset($scan['id_scan'])): ?>
                            <button type="button" class="btn btn-danger btn-sm btn-delete-scan" data-id="<?= $scan['id_scan'] ?>">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapusScan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formHapusScan" method="POST">
            <input type="hidden" name="_token" value="<?= View::csrf() ?>">
            <input type="hidden" name="_method" value="DELETE">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Konfirmasi Hapus Data Scan</h4>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data scan ini?</p>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="checkSetujuHapusScan"> Saya menyetujui penghapusan data ini.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger" id="btnHapusScan" disabled>Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Rekap Absensi -->
<div class="modal fade" id="modalRekapAbsensi" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?= url('/data-scan/rekap') ?>" method="POST">
            <input type="hidden" name="_token" value="<?= View::csrf() ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Rekap Absensi</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Beri Nama Absensi:</label>
                        <input type="text" name="nama_absensi" class="form-control" required placeholder="Contoh: Absensi Upacara 10 Mei">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Rekap</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
// Set variabel untuk layout
$title = 'Data Scan Absensi';
$styles = '<link rel="stylesheet" href="' . asset('AdminLTE-2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') . '">';
$scripts = '
<script src="' . asset('AdminLTE-2/bower_components/datatables.net/js/jquery.dataTables.min.js') . '"></script>
<script src="' . asset('AdminLTE-2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') . '"></script>
<script>
$(document).ready(function() {
    $("#scanTable").DataTable();
    
    $("#checkSetujuHapusScan").on("change", function() {
        $("#btnHapusScan").prop("disabled", !this.checked);
    });
    
    $("button.btn-delete-scan").on("click", function() {
        const id = $(this).data("id");
        $("#formHapusScan").attr("action", "/absensi/data-scan/" + id);
        $("#checkSetujuHapusScan").prop("checked", false);
        $("#btnHapusScan").prop("disabled", true);
        $("#modalHapusScan").modal("show");
    });
});
</script>';
// Layout akan di-handle oleh View::render()
?>
