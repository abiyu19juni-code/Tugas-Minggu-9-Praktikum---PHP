<?php
// Halaman hasil menerima data dari formulir melalui POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form_data_diri.html');
    exit;
}

$nama = $_POST['nama'] ?? '';
$tanggalInput = $_POST['tanggal_lahir'] ?? '';
$hobi = $_POST['hobi'] ?? [];
$pesanError = '';
$tanggalLahir = '';
$hobiTeks = '';

// Validasi juga dilakukan di server untuk permintaan langsung.
if (!is_string($nama) || trim($nama) === '') {
    $pesanError = 'Nama lengkap wajib diisi.';
} elseif (!is_string($tanggalInput)
    || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $tanggalInput)) {
    $pesanError = 'Tanggal lahir wajib diisi dengan format yang valid.';
} else {
    $nama = trim($nama);
    [$tahun, $bulan, $tanggal] = explode('-', $tanggalInput);
    if (!checkdate((int) $bulan, (int) $tanggal, (int) $tahun)) {
        $pesanError = 'Tanggal lahir tidak valid.';
    } else {
        $namaBulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April',
            'Mei', 'Juni', 'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember'
        ];
        $tanggalLahir = (int) $tanggal . ' '
            . $namaBulan[(int) $bulan] . ' ' . $tahun;
    }
}

$pilihanHobi = ['Membaca', 'Olahraga', 'Musik', 'Lainnya'];
if (!is_array($hobi)) {
    $pesanError = 'Pilihan hobi tidak valid.';
} else {
    foreach ($hobi as $item) {
        if (!in_array($item, $pilihanHobi, true)) {
            $pesanError = 'Pilihan hobi tidak valid.';
            break;
        }
    }
    if ($pesanError === '') {
        $hobiTeks = count($hobi) > 0
            ? implode(', ', array_unique($hobi))
            : 'Tidak ada hobi yang dipilih';
    }
}

if ($pesanError !== '') {
    http_response_code(400);
}

// Escape teks sebelum ditampilkan agar input diperlakukan sebagai teks.
function aman($teks) {
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan Data Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            margin: 0;
            padding: 32px 16px;
        }
        main {
            max-width: 620px;
            margin: auto;
            padding: 28px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px #0002;
        }
        h1 { margin: 0 0 24px; font-size: 28px; }
        table { width: 100%; border-top: 1px solid #ddd; }
        th, td { text-align: left; padding: 16px 0; vertical-align: top; }
        th { width: 150px; padding-right: 12px; }
        td { overflow-wrap: anywhere; }
        a { display: inline-block; margin-top: 24px; color: #006fe6; }
    </style>
</head>
<body>
    <main>
        <h1>Tampilan Data Anda</h1>
        <?php if ($pesanError !== ''): ?>
            <p role="alert"><?= aman($pesanError) ?></p>
        <?php else: ?>
            <table>
                <tr><th>Nama Lengkap:</th><td><?= aman($nama) ?></td></tr>
                <tr><th>Tanggal Lahir:</th><td><?= aman($tanggalLahir) ?></td></tr>
                <tr><th>Hobi:</th><td><?= aman($hobiTeks) ?></td></tr>
            </table>
        <?php endif; ?>
        <a href="form_data_diri.html">&laquo; Kembali ke Form</a>
    </main>
</body>
</html>
