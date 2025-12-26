<section class="content">
    <?php if (hasErrors()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php 
            $errors = errors();
            if (is_array($errors)) {
                foreach ($errors as $error) {
                    echo '<li>' . htmlspecialchars($error) . '</li>';
                }
            } else {
                echo '<li>' . htmlspecialchars($errors) . '</li>';
            }
            ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <?php if ($success = flash('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Data <?= htmlspecialchars($title) ?></h3>
            <div class="pull-right">
                <button type="button" class="btn btn-danger hidden" id="bulkDeleteBtn" disabled>
                    Hapus Terpilih <i class="fa fa-trash"></i>
                </button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
                    Import Data <i class="fa fa-upload"></i>
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahSiswa">
                    Tambah <i class="fa fa-plus-circle"></i>
                </button>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped" id="pesertaTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll" style="cursor: pointer;"></th>
                        <th style="width: 50px">No</th>
                        <th>Nomor Peserta</th>
                        <th style="max-width: 200px;">Nama Peserta</th>
                        <th>Kecamatan</th>
                        <th>Rombongan</th>
                        <th>Regu</th>
                        <th>Keterangan</th>
                        <th>Embarkasi</th>
                        <th>Kloter</th>
                        <th style="width: 130px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($peserta as $index => $data): ?>
                    <tr>
                        <td style="text-align: center;"><input type="checkbox" class="checkbox-item" value="<?= $data['id_peserta'] ?>" style="cursor: pointer;"></td>
                        <td style="text-align: center;"><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($data['nomor_peserta']) ?></td>
                        <td style="max-width: 200px;">
                            <?php 
                            $fotoUrl = !empty($data['foto']) 
                                ? asset('image/peserta/' . $data['foto']) 
                                : asset('image/icon.png');
                            ?>
                            <img src="<?= $fotoUrl ?>" alt="Foto Peserta" class="img-circle peserta-photo" onerror="this.src='<?= asset('image/icon.png') ?>'">
                            <strong class="nama-peserta"><?= htmlspecialchars($data['nama_peserta']) ?></strong>
                        </td>
                        <td><?= htmlspecialchars($data['kecamatan'] ?? '-') ?></td>
                        <td><span class="label label-info"><?= htmlspecialchars($data['rombongan'] ?? '-') ?></span></td>
                        <td><span class="label label-success"><?= htmlspecialchars($data['regu'] ?? '-') ?></span></td>
                        <td><?= htmlspecialchars($data['keterangan'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($data['embarkasi'] ?? '-') ?></td>
                        <td><span class="label label-primary"><?= htmlspecialchars($data['kloter'] ?? '-') ?></span></td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <button type="button" class="btn btn-icon btn-edit" data-toggle="modal" data-target="#modalEditPeserta<?= $data['id_peserta'] ?>" title="Edit Data">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-detail" data-toggle="modal" data-target="#modalDetailPeserta<?= $data['id_peserta'] ?>" title="Lihat Detail">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-delete btn-delete-peserta" data-id="<?= $data['id_peserta'] ?>" title="Hapus Data">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/modal.php'; ?>

<?php
// Set variabel untuk layout
$title = 'Data Peserta';
$styles = '<link rel="stylesheet" href="' . asset('AdminLTE-2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') . '">
<style>
    #pesertaTable {
        border-collapse: separate;
        border-spacing: 0;
    }
    #pesertaTable thead th {
        background: linear-gradient(to bottom, #3c8dbc 0%, #357ca5 100%);
        color: white;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #2e6da4;
        padding: 10px 6px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    #pesertaTable thead th:first-child {
        width: 40px;
        text-align: center;
        border-left: 1px solid #2e6da4;
    }
    #pesertaTable thead th:last-child {
        border-right: 1px solid #2e6da4;
    }
    #pesertaTable tbody td {
        vertical-align: middle;
        padding: 8px 6px;
        border-bottom: 1px solid #ddd;
        font-size: 11px;
    }
    #pesertaTable tbody tr {
        transition: background-color 0.2s ease;
    }
    #pesertaTable tbody tr:hover {
        background-color: #e8f4f8;
    }
    #pesertaTable tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    #pesertaTable tbody tr:nth-child(even):hover {
        background-color: #e8f4f8;
    }
    .table-responsive {
        border-radius: 4px;
        overflow-x: auto;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .table-bordered {
        border: 1px solid #ddd;
    }
    .table-bordered > tbody > tr > td,
    .table-bordered > tbody > tr > th,
    .table-bordered > tfoot > tr > td,
    .table-bordered > tfoot > tr > th,
    .table-bordered > thead > tr > td,
    .table-bordered > thead > tr > th {
        border: 1px solid #ddd;
    }
    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
        align-items: center;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s ease;
        border: none;
    }
    .btn-icon:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .btn-icon.btn-edit {
        background-color: #00a65a;
        color: white;
    }
    .btn-icon.btn-edit:hover {
        background-color: #008d4c;
    }
    .btn-icon.btn-detail {
        background-color: #00c0ef;
        color: white;
    }
    .btn-icon.btn-detail:hover {
        background-color: #00a7d0;
    }
    .btn-icon.btn-delete {
        background-color: #dd4b39;
        color: white;
    }
    .btn-icon.btn-delete:hover {
        background-color: #c23321;
    }
    .label {
        display: inline-block;
        padding: 3px 6px;
        font-size: 10px;
        font-weight: 600;
        border-radius: 3px;
    }
    .table-striped > tbody > tr:nth-child(odd) > td,
    .table-striped > tbody > tr:nth-child(odd) > th {
        background-color: transparent;
    }
    #pesertaTable tbody td strong {
        color: #333;
        font-weight: 600;
        vertical-align: middle;
    }
    .peserta-photo {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 8px;
        vertical-align: middle;
        border: 2px solid #ddd;
        display: inline-block;
    }
    .nama-peserta {
        display: inline-block;
        max-width: calc(100% - 40px);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: middle;
    }
</style>';
$scripts = '
<script src="' . asset('AdminLTE-2/bower_components/datatables.net/js/jquery.dataTables.min.js') . '"></script>
<script src="' . asset('AdminLTE-2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') . '"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    var table = $("#pesertaTable").DataTable({
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        "pageLength": 25,
        "order": [[1, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": [0, 10] },
            { "className": "text-center", "targets": [0, 1, 10] }
        ],
        "responsive": true,
        "dom": "<\"row\"<\"col-sm-6\"l><\"col-sm-6\"f>>" +
               "<\"row\"<\"col-sm-12\"tr>>" +
               "<\"row\"<\"col-sm-5\"i><\"col-sm-7\"p>>"
    });
    
    $(document).on("click", ".btn-delete-peserta", function() {
        const id = $(this).data("id");
        const nama = $(this).closest("tr").find("td:eq(3) strong").text() || $(this).closest("tr").find("td:eq(3)").text().trim();
        
        $("#formHapusPeserta").attr("action", "/absensi/peserta/" + id);
        $("#checkSetujuHapus").prop("checked", false);
        $("#btnHapusPeserta").prop("disabled", true);
        $("#modalHapusPeserta .modal-body p").html(
            "Apakah Anda yakin ingin menghapus data peserta <strong>" + nama + "</strong>?<br>" +
            "<strong>Seluruh riwayat absensi terkait juga akan dihapus secara permanen.</strong>"
        );
        $("#modalHapusPeserta").modal("show");
    });
    
    $("#checkSetujuHapus").on("change", function() {
        $("#btnHapusPeserta").prop("disabled", !this.checked);
    });
    
    $("#checkAll").on("click", function() {
        $(".checkbox-item").prop("checked", this.checked);
        toggleBulkDeleteBtn();
    });
    
    $(document).on("change", ".checkbox-item", function() {
        toggleBulkDeleteBtn();
    });
    
    function toggleBulkDeleteBtn() {
        const anyChecked = $(".checkbox-item:checked").length > 0;
        $("#bulkDeleteBtn").prop("disabled", !anyChecked);
        if (anyChecked) {
            $("#bulkDeleteBtn").removeClass("hidden");
        } else {
            $("#bulkDeleteBtn").addClass("hidden");
        }
    }
    
    $("#bulkDeleteBtn").on("click", function() {
        const selectedIds = $(".checkbox-item:checked").map(function() {
            return $(this).val();
        }).get();
        
        $("#modalBulkHapusText").text("Anda akan menghapus " + selectedIds.length + " peserta. Tindakan ini tidak dapat dibatalkan.");
        $("#checkSetujuBulkHapus").prop("checked", false);
        $("#btnBulkHapusPeserta").prop("disabled", true);
        $("#modalBulkHapusPeserta").modal("show");
    });
    
    $("#checkSetujuBulkHapus").on("change", function() {
        $("#btnBulkHapusPeserta").prop("disabled", !this.checked);
    });
    
    $("#formBulkHapusPeserta").on("submit", function(e) {
        e.preventDefault();
        const selectedIds = $(".checkbox-item:checked").map(function() {
            return $(this).val();
        }).get();
        
        $.ajax({
            url: "/absensi/peserta/bulk-delete",
            type: "POST",
            data: {
                _token: "' . View::csrf() . '",
                ids: selectedIds
            },
            success: function(res) {
                if (res.success) {
                    Swal.fire("Dihapus!", res.message, "success").then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire("Gagal!", "Terjadi kesalahan saat menghapus data.", "error");
                }
            },
            error: function() {
                Swal.fire("Gagal!", "Terjadi kesalahan pada server.", "error");
            }
        });
    });
});
</script>';
// Layout akan di-handle oleh View::render()
?>
