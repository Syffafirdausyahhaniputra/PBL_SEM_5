@empty($vendor)
    <div id="modal-master" class="modal-dialog modal-lg" vendor="document">
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
                <a href="{{ url('/vendor') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/vendor/' . $vendor->vendor_id.'/update_ajax') }}" method="POST" id="form-edit-vendor">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" vendor="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Vendor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Vendor</label>
                    <input value="{{ $vendor->vendor_nama }}" type="text" name="vendor_nama" id="vendor_nama"
                        class="form-control" required>
                    <small id="error-vendor_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Vendor</label>
                    <input value="{{ $vendor->vendor_alamat }}" type="text" name="vendor_alamat" id="vendor_alamat"
                        class="form-control" required>
                    <small id="error-vendor_alamat" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kota Vendor</label>
                    <input value="{{ $vendor->vendor_kota }}" type="text" name="vendor_kota" id="vendor_kota"
                        class="form-control" required>
                    <small id="error-vendor_kota" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>No Telfon Vendor</label>
                    <input value="{{ $vendor->vendor_no_telf }}" type="text" name="vendor_no_telf" id="vendor_no_telf"
                        class="form-control" required>
                    <small id="error-vendor_no_telf" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Web Vendor</label>
                    <input value="{{ $vendor->vendor_alamat_web }}" type="text" name="vendor_alamat_web" id="vendor_alamat_web"
                        class="form-control" required>
                    <small id="error-vendor_alamat_web" class="error-text form-text text-danger"></small>
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
        $("#form-edit-vendor").validate({
            rules: {
                vendor_nama: {required: true, minlength: 3, maxlength: 100},
                vendor_alamat: {required: true, minlength: 3, maxlength: 100},
                vendor_kota: {required: true, minlength: 3, maxlength: 100},
                vendor_no_telf: {required: true, minlength: 3, maxlength: 100},
                vendor_alamat_web: {required: true, minlength: 3, maxlength: 200}
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
                            datavendor.ajax.reload();
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