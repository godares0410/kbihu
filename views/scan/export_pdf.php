<!DOCTYPE html>
<html>
<head>
    <title>Data Scan - <?= htmlspecialchars($nama) ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid black; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Data Scan - <?= htmlspecialchars($nama) ?></h2>

    <h3>Sudah Scan <?= $countSudahScan ?> Peserta</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peserta</th>
                <th>Nomor Peserta</th>
                <th>Kecamatan</th>
                <th>Regu</th>
                <th>Rombongan</th>
                <th>Kloter</th>
                <th>Waktu Scan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sudahScan as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($row['nama_peserta']) ?></td>
                <td><?= htmlspecialchars($row['nomor_peserta']) ?></td>
                <td><?= htmlspecialchars($row['kecamatan'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['regu'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['rombongan'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['kloter'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['waktu_scan'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Belum Scan <?= $countBelumScan ?> Peserta</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peserta</th>
                <th>Nomor Peserta</th>
                <th>Kecamatan</th>
                <th>Regu</th>
                <th>Rombongan</th>
                <th>Kloter</th>
                <th>Waktu Scan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($belumScan as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($row['nama_peserta']) ?></td>
                <td><?= htmlspecialchars($row['nomor_peserta']) ?></td>
                <td><?= htmlspecialchars($row['kecamatan'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['regu'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['rombongan'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['kloter'] ?? '-') ?></td>
                <td>-</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
