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
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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
            border-left: 8px solid #007bff;
            /* Warna biru di samping kiri */
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
            cursor: pointer;
        }

        .badge-selesai {
            background-color: #28a745;
        }

        /* Hijau untuk Selesai */
        .badge-proses {
            background-color: #ffc107;
            color: #000;
        }

        /* Kuning untuk Proses */
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Fetch data dari server dan render sebagai card
            $.ajax({
                url: "{{ url('notifikasi/list') }}",
                type: "POST",
                dataType: "json",
                success: function(data) {
                    var container = $('#card-container');
                    container.empty();

                    // Urutkan data berdasarkan waktu terbaru (updated_at)
                    var sortedData = data.data.sort(function(a, b) {
                        return new Date(b.updated_at) - new Date(a.updated_at);
                    });

                    sortedData.forEach(function(item) {
                        var statusClass = item.status === 'Selesai' ? 'badge-selesai' :
                            'badge-proses';

                        // Buat template card dengan event click pada status
                        var card = `
                <div class="data-card">
                    <div class="card-content">
                        <div class="card-title">${item.nama}</div>
                        <div class="card-subtitle">${item.keterangan}</div>
                    </div>
                    <span class="badge-status ${statusClass}" data-id="${item.id}" data-type="${item.type}">${item.status}</span>
                </div>
            `;

                        container.append(card);
                    });
                }
            });

            // Event click untuk badge status dengan event delegation
            $(document).on('click', '.badge-status', function() {
                var id = $(this).data('id');
                var type = $(this).data('type'); // Ambil type dari data-type

                // Tentukan URL berdasarkan type
                var url = type === 'sertifikasi' ?
                    "{{ url('notifikasi/sertifikasi') }}/" + id + "/show_ajax" :
                    "{{ url('notifikasi/pelatihan') }}/" + id + "/show_ajax";

                // Gunakan modalAction untuk membuka modal
                modalAction(url);
            });
        });

        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush
