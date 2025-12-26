<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cetak Kartu Peserta</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
        }

        .page-wrapper {
            padding: 10mm;
            box-sizing: border-box;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .card {
            width: 53.98mm;
            height: 85.60mm;
            background-image: url('<?= asset('image/doc/idcard.jpg') ?>');
            background-size: cover;
            background-position: center;
            position: relative;
            box-sizing: border-box;
            padding: 0;
            page-break-inside: avoid;
            margin-bottom: 5mm;
        }

        .card-content {
            height: 100%;
            color: black;
            padding: 6mm 4mm 0mm 4mm;
            padding-bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            justify-content: space-between;
        }

        .card-content img.photo {
            width: 80px;
            height: 90px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 5px;
            border: 1px solid #000;
        }

        .name {
            font-size: 14px;
            font-weight: bold;
        }

        .info {
            font-size: 8px;
            margin: 1px 0;
        }

        .box-group {
            display: flex;
            justify-content: space-around;
            width: 100%;
            margin-top: 3px;
        }

        .box {
            width: 30%;
            background: #fff;
            border-radius: 3px;
            border: 1px solid #000;
            padding: 1px;
        }

        .box-title {
            font-size: 7px;
            font-weight: bold;
        }

        .box-value {
            font-size: 9px;
            font-weight: bold;
        }

        .qr-code {
            width: 15mm;
            margin: 0 auto 2px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 0;
        }

        .qr-code .qr-image {
            width: 13mm;
            height: 13mm;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: visible;
        }

        .qr-code .qr-image canvas {
            width: 13mm !important;
            height: 13mm !important;
            max-width: 13mm;
            max-height: 13mm;
        }

        .qr-code .qr-image img {
            width: 13mm;
            height: 13mm;
            max-width: 13mm;
            max-height: 13mm;
            object-fit: contain;
        }

        .qr-code .small-text {
            font-size: 7px;
            margin-top: 1mm;
            text-align: center;
        }

        .small-text {
            font-size: 6px;
        }

        .footer-box {
            border: 1px solid #000;
            border-radius: 3px;
            padding: 2px;
            background-color: #fff;
            margin-top: 0;
            width: 100%;
            box-sizing: border-box;
        }

        .footer-text {
            font-size: 6px;
            text-align: center;
        }

        @media print {
            body {
                padding: 0;
            }

            .card-content {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .card {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="container">
            <?php foreach ($pesertas as $peserta): ?>
            <div class="card">
                <div class="card-content">
                    <img class="photo" src="<?= asset(($peserta['foto'] ? 'image/peserta/' . $peserta['foto'] : 'image/icon.png')) ?>" alt="Foto Peserta" onerror="this.src='<?= asset('image/' . ($peserta['foto'] ?? 'icon.png')) ?>'">
                    <div class="name"><?= strtoupper(htmlspecialchars($peserta['nama_peserta'])) ?></div>
                    <div class="info">NO. PORSI <?= htmlspecialchars($peserta['nomor_peserta']) ?></div>

                    <div class="box-group">
                        <div class="box">
                            <div class="box-title">EMBARKASI</div>
                            <div class="box-value"><?= strtoupper(htmlspecialchars($peserta['embarkasi'] ?? '-')) ?></div>
                        </div>
                        <div class="box">
                            <div class="box-title">KLOTER</div>
                            <div class="box-value"><?= htmlspecialchars($peserta['kloter'] ?? '-') ?></div>
                        </div>
                        <div class="box">
                            <div class="box-title">ROMBONGAN</div>
                            <div class="box-value"><?= htmlspecialchars($peserta['rombongan'] ?? '-') ?></div>
                        </div>
                    </div>

                    <div style="margin-top: auto; position: relative; top: -35px;">
                        <div class="qr-code">
                            <div class="qr-image" id="qr-<?= htmlspecialchars($peserta['nomor_peserta']) ?>" data-code="<?= htmlspecialchars($peserta['nomor_peserta']) ?>">
                                <!-- QR Code will be generated by JavaScript -->
                            </div>
                            <div class="small-text">No: <?= htmlspecialchars($peserta['nomor_peserta']) ?></div>
                        </div>

                        <div class="footer-box" style="margin-top: 2px;">
                            <div class="footer-text">
                                JAMAAH HAJI KBIHU AL AMANAH 2025<br>
                                NO. TELP. PEMBIMBING: 081234477735
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="no-print" style="position: fixed; bottom: 10px; right: 10px;">
        <button onclick="window.print()">Cetak Manual</button>
    </div>

    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        // Generate QR Code for each participant
        function generateQRCodes() {
            const qrElements = document.querySelectorAll('.qr-image');
            let completed = 0;
            const total = qrElements.length;

            qrElements.forEach(function(element) {
                const code = element.getAttribute('data-code');
                if (code) {
                    // Use toDataURL to generate as image (more reliable)
                    QRCode.toDataURL(code, {
                        width: 60,
                        margin: 1,
                        color: {
                            dark: '#000000',
                            light: '#FFFFFF'
                        }
                    }, function (error, url) {
                        completed++;
                        if (error) {
                            console.error('QR Code generation error:', error);
                            // Fallback to API if generation fails
                            const img = document.createElement('img');
                            img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=' + encodeURIComponent(code);
                            img.alt = 'QR Code';
                            img.style.width = '100%';
                            img.style.height = 'auto';
                            element.innerHTML = '';
                            element.appendChild(img);
                        } else {
                            // Use generated data URL
                            const img = document.createElement('img');
                            img.src = url;
                            img.alt = 'QR Code';
                            img.style.width = '100%';
                            img.style.height = 'auto';
                            element.innerHTML = '';
                            element.appendChild(img);
                        }
                        
                        // When all QR codes are generated, trigger print
                        if (completed === total) {
                            setTimeout(function() {
                                window.print();
                            }, 500);
                        }
                    });
                } else {
                    completed++;
                    if (completed === total) {
                        setTimeout(function() {
                            window.print();
                        }, 500);
                    }
                }
            });

            // Fallback: if no QR codes to generate, print after 1 second
            if (total === 0) {
                setTimeout(function() {
                    window.print();
                }, 1000);
            }
        }

        // Wait for library to load
        if (typeof QRCode !== 'undefined') {
            document.addEventListener('DOMContentLoaded', generateQRCodes);
        } else {
            // If library fails to load, use fallback API
            window.onload = function() {
                const qrElements = document.querySelectorAll('.qr-image');
                qrElements.forEach(function(element) {
                    const code = element.getAttribute('data-code');
                    if (code) {
                        const img = document.createElement('img');
                        img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=' + encodeURIComponent(code);
                        img.alt = 'QR Code';
                        img.style.width = '100%';
                        img.style.height = 'auto';
                        element.appendChild(img);
                    }
                });
                setTimeout(function() {
                    window.print();
                }, 2000);
            };
        }
    </script>
</body>
</html>
