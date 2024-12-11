<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Sertifikasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }

        .card-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .text-muted {
            color: #6c757d;
            font-size: 14px;
        }

        p {
            margin: 10px 0;
            font-size: 16px;
            color: #444;
        }

        strong {
            font-weight: bold;
        }

        hr {
            border: 0;
            height: 1px;
            background: #e0e0e0;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{ $sertifikasi->nama_sertif }}</h3>
                <h6 class="text-muted">{{ $sertifikasi->bidang->bidang_nama ?? 'N/A' }}</h6>
                <hr>

                <p><strong>Jenis Sertifikasi:</strong> {{ $sertifikasi->jenis->jenis_nama ?? 'N/A' }}</p>
                <p><strong>Mata Kuliah Terkait:</strong> {{ $sertifikasi->matkul->mk_nama ?? 'N/A' }}</p>
                <p><strong>Vendor:</strong> {{ $sertifikasi->vendor->vendor_nama ?? 'N/A' }}</p>
                <p><strong>Tanggal:</strong> {{ $sertifikasi->tanggal ?? 'N/A' }}</p>
                <p><strong>Masa Berlaku:</strong> {{ $sertifikasi->masa_berlaku ?? 'N/A' }}</p>
                <p><strong>Tanggal Akhir:</strong> {{ $sertifikasi->tanggal_akhir ?? 'N/A' }}</p>
                <p><strong>Biaya:</strong> Rp{{ number_format($sertifikasi->biaya, 0, ',', '.') ?? 'N/A' }}</p>
                <p><strong>Periode:</strong> {{ $sertifikasi->periode ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
