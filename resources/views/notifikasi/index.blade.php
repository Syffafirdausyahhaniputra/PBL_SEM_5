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

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
                    <thead>
                        <tr class="table-primary">
                            <th>Nama</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
    <style>
        /* Styling label status */
        .badge-status {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
        }
        .badge-diterima { background-color: #28a745; } /* Hijau */
        .badge-proses { background-color: #6c757d; }   /* Abu-abu */
        .badge-ditolak { background-color: #dc3545; }   /* Merah */
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('#table_level').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('notifikasi/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [
                    { data: "nama", searchable: true },
                    { data: "keterangan", searchable: true },
                    {
                        data: "status",
                        searchable: true,
                        render: function(data, type, row) {
                            // Tentukan kelas warna berdasarkan status
                            var statusClass = '';
                            if (data === 'Diterima') {
                                statusClass = 'badge-diterima';
                            } else if (data === 'Proses') {
                                statusClass = 'badge-proses';
                            } else if (data === 'Ditolak') {
                                statusClass = 'badge-ditolak';
                            }
                            // Kembalikan badge dengan warna yang sesuai
                            return '<span class="badge-status ' + statusClass + '">' + data + '</span>';
                        }
                    }
                ]
            });
        });
    </script>
@endpush
