@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('/jenis/export_excel') }}" class="btn btn-primary"><i
                        class="fa fa-file-excel"></i> Export Jenis Sertifikasi</a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/jenis/export_pdf') }}" class="btn btn-warning"><i
                        class="fa fa-file-pdf"></i> Export Jenis Sertifikasi</a>
                <button onclick="modalAction('{{ url('/jenis/import') }}')" class="btn btn-sm btn-info mt-1"><i class="fa fa-upload"> Import Jenis Sertifikasi</i></button>
                <button onclick="modalAction('{{ url('/jenis/create_ajax') }}')" class="btn btn-sm btn-success mt-1"><i class="fa fa-plus"> Tambah Jenis Sertifikasi</i></button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class= "table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_jenis">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" jenis="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var datajenis;
        $(document).ready(function() {
            datajenis = $('#table_jenis').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('jenis/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [{
                    // nomor urut dari laravel datatable addIndexColumn() 
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "jenis_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }]
            });
        });
    </script>
@endpush
