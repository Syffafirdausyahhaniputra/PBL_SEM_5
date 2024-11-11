@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div id="card-container">
                <!-- Data akan dimuat menggunakan JavaScript dan template di bawah -->
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        /* Styling card */
        #card-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .data-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border-left: 8px solid #007bff; /* Warna biru di samping kiri */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .card-content {
            display: flex;
            flex-direction: column;
        }
        
        .card-title {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .card-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        /* Styling label status */
        .badge-status {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
        }
        .badge-selesai { background-color: #28a745; } /* Hijau untuk Selesai */
        .badge-proses { background-color: #ffc107; color: #000; } /* Kuning untuk Proses */
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Fetch data dari server dan render sebagai card
            $.ajax({
                url: "{{ url('riwayat/list') }}",
                type: "POST",
                dataType: "json",
                success: function(data) {
                    var container = $('#card-container');
                    container.empty(); // Kosongkan container
                    
                    data.data.forEach(function(item) {
                        // Tentukan kelas warna berdasarkan status
                        var statusClass = '';
                        if (item.status === 'Selesai') {
                            statusClass = 'badge-selesai';
                        } else if (item.status === 'Proses') {
                            statusClass = 'badge-proses';
                        }
                        
                        // Buat template card
                        var card = `
                            <div class="data-card">
                                <div class="card-content">
                                    <div class="card-title">${item.nama}</div>
                                    <div class="card-subtitle">${item.keterangan}</div>
                                </div>
                                <span class="badge-status ${statusClass}">${item.status}</span>
                            </div>
                        `;
                        
                        // Tambahkan card ke container
                        container.append(card);
                    });
                }
            });
        });
    </script>
@endpush
