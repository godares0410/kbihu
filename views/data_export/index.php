<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Data Export</h3>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped" id="datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Absensi</th>
                        <th>Jumlah Scan</th>
                        <th>Jumlah Tidak Scan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($dataGrouped as $row): 
                        if (empty($row['nama'])) continue;
                        
                        // Count sudah scan dan belum scan
                        $scanModel = new Scan();
                        $sudahScan = $scanModel->getSudahScanByNama($row['nama']);
                        $belumScan = $scanModel->getBelumScanByNama($row['nama']);
                        $countSudahScan = count($sudahScan);
                        $countBelumScan = count($belumScan);
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= $countSudahScan ?></td>
                        <td><?= $countBelumScan ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Export <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= url('/data-export/export/' . urlencode($row['nama']) . '/pdf') ?>">PDF</a></li>
                                    <li><a href="<?= url('/data-export/export/' . urlencode($row['nama']) . '/excel') ?>">Excel</a></li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-nama="<?= htmlspecialchars($row['nama']) ?>">Hapus</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapusGrup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" id="formHapusGrup">
            <input type="hidden" name="_token" value="<?= View::csrf() ?>">
            <input type="hidden" name="_method" value="DELETE">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h4 class="modal-title">Konfirmasi Hapus Data</h4>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus semua data dengan nama <strong id="namaToDelete"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$title = 'Data Export';
$styles = '<link rel="stylesheet" href="' . asset('AdminLTE-2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') . '">';
$scripts = '
<script src="' . asset('AdminLTE-2/bower_components/datatables.net/js/jquery.dataTables.min.js') . '"></script>
<script src="' . asset('AdminLTE-2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') . '"></script>
<script>
$(function() {
    $("#datatable").DataTable();

    $(".btn-delete").on("click", function() {
        let nama = $(this).data("nama");
        $("#namaToDelete").text(nama);
        $("#formHapusGrup").attr("action", "/absensi/data-export/" + encodeURIComponent(nama));
        $("#modalHapusGrup").modal("show");
    });
});
</script>';
?>
