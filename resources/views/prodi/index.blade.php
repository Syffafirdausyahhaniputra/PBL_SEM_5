@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('/prodi/export_excel') }}" class="btn btn-primary"><i
                        class="fa fa-file-excel"></i> Export Program Studi</a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/prodi/export_pdf') }}" class="btn btn-warning"><i
                        class="fa fa-file-pdf"></i> Export Program Studi</a>
                <button onclick="modalAction('{{ url('/prodi/import') }}')" class="btn btn-sm btn-info mt-1"><i class="fa fa-upload"> Import Program Studi</i></button>
                <button onclick="modalAction('{{ url('/prodi/create_ajax') }}')" class="btn btn-sm btn-success mt-1"><i class="fa fa-plus"> Tambah Program Studi</i></button>
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
                <table class="table table-bordered table-striped table-hover table-sm" id="table_prodi">
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
    <div id="myModal" class="modal fade animate shake" tabindex="-1" prodi="dialog" data-backdrop="static"
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
        var dataprodi;
        $(document).ready(function() {
            dataprodi = $('#table_prodi').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('prodi/list') }}",
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
                    data: "prodi_nama",
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
