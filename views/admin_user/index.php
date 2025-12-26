<section class="content">
    <?php if ($error = flash('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> <?= htmlspecialchars($error) ?>
    </div>
    <script>console.log('Flash error:', '<?= addslashes($error) ?>');</script>
    <?php endif; ?>
    
    <?php if ($success = flash('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Success!</strong> <?= htmlspecialchars($success) ?>
    </div>
    <script>console.log('Flash success:', '<?= addslashes($success) ?>');</script>
    <?php endif; ?>
    
    <?php if (hasErrors()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php 
            $errors = errors();
            if (is_array($errors)) {
                foreach ($errors as $err) {
                    echo '<li>' . htmlspecialchars($err) . '</li>';
                }
            }
            ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Data <?= htmlspecialchars(ucwords($title)) ?></h3>
            <div class="pull-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahUser">
                    Tambah User <i class="fa fa-plus-circle"></i>
                </button>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped" id="datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $count = 1;
                    foreach ($adminUsers as $index => $user): 
                        if ($user['id'] == 1) continue; // Skip user ID 1
                    ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditUser<?= $user['id'] ?>">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm btn-delete-user" data-id="<?= $user['id'] ?>" data-name="<?= htmlspecialchars($user['name']) ?>">
                                Hapus
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEditUser<?= $user['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="<?= url('/admin-users/' . $user['id']) ?>" enctype="multipart/form-data" id="editForm<?= $user['id'] ?>" onsubmit="console.log('=== FORM SUBMIT DEBUG ==='); console.log('Form ID:', '<?= $user['id'] ?>'); var formData = new FormData(this); console.log('FormData entries:'); for (var pair of formData.entries()) { console.log(pair[0] + ':', pair[1]); } var fileInput = this.querySelector('input[type=\"file\"][name=\"foto\"]'); if (fileInput && fileInput.files.length > 0) { console.log('File selected:', fileInput.files[0].name, 'Size:', fileInput.files[0].size, 'Type:', fileInput.files[0].type); } else { console.log('No file selected'); } console.log('=== END FORM SUBMIT DEBUG ==='); return true;">
                                <input type="hidden" name="_token" value="<?= View::csrf() ?>">
                                <input type="hidden" name="_method" value="PUT">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit User</h4>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="name" class="form-control mb-2" value="<?= htmlspecialchars($user['name']) ?>" required placeholder="Nama">
                                        <input type="email" name="email" class="form-control mb-2" value="<?= htmlspecialchars($user['email']) ?>" required placeholder="Email">
                                        <input type="password" name="password" class="form-control" placeholder="Ganti Password (opsional)">
                                        <div class="form-group">
                                            <label>Foto Profil</label>
                                            <?php if ($user['foto']): ?>
                                            <div class="mb-2">
                                                <img src="<?= asset('image/users/' . $user['foto']) ?>" width="100" class="img-thumbnail" onerror="this.src='<?= asset('image/' . $user['foto']) ?>'">
                                            </div>
                                            <?php endif; ?>
                                            <input type="file" name="foto" class="form-control">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" onclick="console.log('Form submit clicked'); console.log('Form data:', new FormData(this.form)); return true;">Simpan</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="<?= url('/admin-users') ?>" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="<?= View::csrf() ?>">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Admin User</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan alamat email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Profil</label>
                        <input type="file" name="foto" class="form-control" id="foto">
                        <small class="text-muted">Format: JPEG, PNG, JPG, GIF (Max 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus User -->
<div class="modal fade" id="modalHapusUser" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" id="formHapusUser">
            <input type="hidden" name="_token" value="<?= View::csrf() ?>">
            <input type="hidden" name="_method" value="DELETE">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus User</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus user <strong id="userNameToDelete"></strong>?</p>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="checkSetujuHapusUser">
                        <label class="form-check-label" for="checkSetujuHapusUser">Saya yakin ingin menghapus user ini</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" id="btnHapusUser" disabled>Hapus</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$title = 'Data Admin User';
$styles = '<link rel="stylesheet" href="' . asset('AdminLTE-2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') . '">';
$scripts = '
<script src="' . asset('AdminLTE-2/bower_components/datatables.net/js/jquery.dataTables.min.js') . '"></script>
<script src="' . asset('AdminLTE-2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') . '"></script>
<script>
$(function() {
    $("#datatable").DataTable();
});

$(document).ready(function() {
    // Debug: Log all form submissions
    $("form[enctype=\'multipart/form-data\']").on("submit", function(e) {
        console.log("=== FORM SUBMISSION DEBUG ===");
        console.log("Form action:", $(this).attr("action"));
        console.log("Form method:", $(this).attr("method"));
        
        var fileInput = $(this).find("input[type=\'file\'][name=\'foto\']");
        if (fileInput.length > 0) {
            if (fileInput[0].files.length > 0) {
                var file = fileInput[0].files[0];
                console.log("File selected:", {
                    name: file.name,
                    size: file.size,
                    type: file.type
                });
            } else {
                console.log("No file selected in input");
            }
        } else {
            console.log("File input not found");
        }
        
        var formData = new FormData(this);
        console.log("FormData entries:");
        for (var pair of formData.entries()) {
            console.log(pair[0] + ":", pair[1]);
        }
        console.log("=== END FORM SUBMISSION DEBUG ===");
    });
    
    $(".btn-delete-user").on("click", function() {
        const userId = $(this).data("id");
        const userName = $(this).data("name");

        $("#userNameToDelete").text(userName);
        $("#formHapusUser").attr("action", "/absensi/admin-users/" + userId);
        $("#checkSetujuHapusUser").prop("checked", false);
        $("#btnHapusUser").prop("disabled", true);
        $("#modalHapusUser").modal("show");
    });

    $("#checkSetujuHapusUser").on("change", function() {
        $("#btnHapusUser").prop("disabled", !this.checked);
    });
});
</script>';
?>
