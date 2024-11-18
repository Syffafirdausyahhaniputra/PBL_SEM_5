@empty($matkul)
    <div id="modal-master" class="modal-dialog modal-lg" matkul="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan</div>
                <a href="{{ url('/matkul') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/matkul/' . $matkul->mk_id.'/update_ajax') }}" method="POST" id="form-edit-matkul">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" matkul="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Mata Kuliah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Mata Kuliah</label>
                    <input value="{{ $matkul->mk_kode }}" type="text" name="mk_kode" id="matkul_kode"
                        class="form-control" required>
                    <small id="error-mk_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Mata Kuliah</label>
                    <input value="{{ $matkul->mk_nama }}" type="text" name="mk_nama" id="mk_nama"
                        class="form-control" required>
                    <small id="error-mk_nama" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $("#form-edit-matkul").validate({
            rules: {
                mk_kode: { required: true, minlength: 2, maxlength: 20 },
                mk_nama: { required: true, minlength: 3, maxlength: 100 }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            datamatkul.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endempty