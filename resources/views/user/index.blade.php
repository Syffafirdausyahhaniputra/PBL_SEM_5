@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/user/import') }}')" class="btn btn-sm btn-info mt-1"><i class="fa fa-upload"> Import User</i></button>
                <button onclick="modalAction('{{ url('/user/create_ajax') }}')" class="btn btn-sm btn-success mt-1"><i class="fa fa-plus"> Tambah User</i></button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group row">
                        <label class="col-4 control-label col-form-label">Filter:</label>
                        <div class="col-7">
                            <select class="form-control" id="role_id" name="role_id" required>
                                <option value="">- Semua -</option>
                                @foreach ($role as $item)
                                    <option value="{{ $item->role_id }}">{{ $item->role_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Role</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class= "table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
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
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var datauser;
        $(document).ready(function() {
            dataUser = $('#table_user').DataTable({
                // serverSide: true, jika ingin menggunakan server side proses 
                serverSide: true,
                ajax: {
                    "url": "{{ url('user/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.role_id = $('#role_id').val();
                    }
                },
                columns: [{
                    // nomor urut dari laravel datatable addIndexColumn() 
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "username",
                    className: "",
                    // orderable: true, jika ingin kolom ini bisa diurutkan  
                    orderable: true,
                    // searchable: true, jika ingin kolom ini bisa dicari 
                    searchable: true
                }, {
                    data: "nama",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "nip",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    // mengambil data role hasil dari ORM berelasi 
                    data: "role.role_nama",
                    className: "",
                    orderable: false,
                    searchable: false
                }, {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }]
            });
            $('#role_id').on('change', function() {
                dataUser.ajax.reload();
            });
        });
    </script>
@endpush
