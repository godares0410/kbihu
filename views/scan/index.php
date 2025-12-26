<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Peserta</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
        .container { margin-top: 30px; margin-bottom: 30px; }
        .form-container { background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .info-box { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .list-group-item { border: none; margin-bottom: 10px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .media img { border-radius: 50%; border: 2px solid #007bff; width: 50px; height: 50px; object-fit: cover; }
        #qr-reader { width: 100%; display: none; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="form-container">
                    <h2 class="mb-4">Masukkan Kode</h2>
                    <div class="form-group mb-3">
                        <label>Waktu Sekarang (Arab Saudi):</label>
                        <div class="alert alert-info py-2" id="saudiTime" style="font-weight: bold;">Loading...</div>
                    </div>
                    <form action="<?= url('/scan/store') ?>" method="POST" id="scanForm">
                        <input type="hidden" name="_token" value="<?= View::csrf() ?>">
                        <div class="form-group">
                            <label for="kode">Kode Peserta</label>
                            <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukkan kode peserta" required>
                            <input type="hidden" name="local_time" id="localTime">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit</button>
                            <button type="button" class="btn btn-outline-primary" id="openCamera"><i class="fas fa-camera"></i> Scan QR</button>
                        </div>
                    </form>
                    <div id="qr-reader"></div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="form-container">
                    <h2 class="mb-4">Status Scan</h2>
                    <div class="info-box">
                        <h5 class="text-center mb-4">Informasi Scan</h5>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border rounded py-2">
                                    <p class="mb-1 text-muted">Total</p>
                                    <h6 class="mb-0"><?= $totalPeserta ?></h6>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded py-2">
                                    <p class="mb-1 text-muted">Sudah Scan</p>
                                    <h6 class="mb-0 text-success"><?= $totalSudahScan ?></h6>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded py-2">
                                    <p class="mb-1 text-muted">Belum Scan</p>
                                    <h6 class="mb-0 text-danger"><?= $totalBelumScan ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#sudah-scan">Sudah Scan</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#belum-scan">Belum Scan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="sudah-scan">
                            <div class="list-group mt-3" style="max-height: 400px; overflow-y: auto;">
                                <?php foreach ($sudahScan as $scan): ?>
                                <div class="list-group-item">
                                    <div class="media">
                                        <img src="<?= asset('image/' . ($scan['foto'] ?? 'icon.png')) ?>" class="mr-3">
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1"><?= htmlspecialchars($scan['nama_peserta']) ?></h5>
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="badge badge-info">Rombongan <?= htmlspecialchars($scan['rombongan'] ?? '-') ?></span>
                                                    <span class="badge badge-secondary">Regu <?= htmlspecialchars($scan['regu'] ?? '-') ?></span>
                                                </div>
                                                <div class="text-muted small"><?= htmlspecialchars($scan['waktu_scan'] ?? '-') ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="belum-scan">
                            <div class="list-group mt-3" style="max-height: 400px; overflow-y: auto;">
                                <?php foreach ($belumScan as $peserta): ?>
                                <div class="list-group-item">
                                    <div class="media">
                                        <img src="<?= asset(($peserta['foto'] ? 'image/peserta/' . $peserta['foto'] : 'image/icon.png')) ?>" class="mr-3" onerror="this.src='<?= asset('image/' . ($peserta['foto'] ?? 'icon.png')) ?>'">
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1"><?= htmlspecialchars($peserta['nama_peserta']) ?></h5>
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="badge badge-info">Rombongan <?= htmlspecialchars($peserta['rombongan'] ?? '-') ?></span>
                                                    <span class="badge badge-secondary">Regu <?= htmlspecialchars($peserta['regu'] ?? '-') ?></span>
                                                </div>
                                                <button class="btn btn-primary btn-sm" onclick="scanPeserta('<?= htmlspecialchars($peserta['nomor_peserta']) ?>')">
                                                    <i class="fas fa-check"></i> Scan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function updateSaudiTime() {
            const options = { timeZone: 'Asia/Riyadh', hour12: false, year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const formatter = new Intl.DateTimeFormat('id-ID', options);
            const parts = formatter.formatToParts(new Date());
            let date = {}, time = {};
            parts.forEach(part => {
                if (part.type === 'day') date.day = part.value;
                else if (part.type === 'month') date.month = part.value;
                else if (part.type === 'year') date.year = part.value;
                else if (part.type === 'hour') time.hour = part.value;
                else if (part.type === 'minute') time.minute = part.value;
                else if (part.type === 'second') time.second = part.value;
            });
            document.getElementById('saudiTime').textContent = `${date.year}-${date.month}-${date.day} ${time.hour}:${time.minute}:${time.second}`;
        }
        updateSaudiTime();
        setInterval(updateSaudiTime, 1000);

        $('#scanForm').on('submit', function(e) {
            e.preventDefault();
            const now = new Date();
            $('#localTime').val(now.toISOString());
            const form = $(this);
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', html: `Scan berhasil untuk <b>${response.data.nama_peserta}</b><br>Waktu: ${response.data.waktu_scan}`, timer: 3000, willClose: () => location.reload() });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan';
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: message });
                }
            });
        });

        document.getElementById('openCamera').addEventListener('click', function() {
            const qrReader = document.getElementById('qr-reader');
            qrReader.style.display = 'block';
            const html5QrCode = new Html5Qrcode('qr-reader');
            html5QrCode.start({ facingMode: 'environment' }, { fps: 10, qrbox: 250 },
                qrCodeMessage => {
                    document.getElementById('kode').value = qrCodeMessage;
                    const now = new Date();
                    $('#localTime').val(now.toISOString());
                    html5QrCode.stop();
                    qrReader.style.display = 'none';
                    $('#scanForm').submit();
                },
                errorMessage => console.log('QR error: ' + errorMessage)
            ).catch(err => Swal.fire({ icon: 'error', title: 'Kamera Error', text: 'Gagal mengakses kamera: ' + err.message }));
        });

        function scanPeserta(nomorPeserta) {
            Swal.fire({ title: 'Konfirmasi Scan', text: 'Scan peserta ini?', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Scan' })
                .then((result) => {
                    if (result.isConfirmed) {
                        const now = new Date();
                        $.ajax({
                            url: '<?= url('/scan/store') ?>',
                            method: 'POST',
                            data: { kode: nomorPeserta, local_time: now.toISOString(), _token: '<?= View::csrf() ?>' },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({ icon: 'success', title: 'Berhasil!', html: `Scan berhasil untuk <b>${response.data.nama_peserta}</b>`, timer: 3000, willClose: () => location.reload() });
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                            }
                        });
                    }
                });
        }
    </script>
</body>
</html>
