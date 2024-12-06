@empty($pelatihan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/pelatihan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/pelatihan/' . $pelatihan->pelatihan_id.'/update_ajax') }}" method="POST" id="form-edit-pelatihan">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Pelatihan</label>
                    <input value="{{ $pelatihan->nama_pelatihan }}" type="text" name="nama_pelatihan" id="nama_pelatihan"
                        class="form-control" required>
                    <small id="error-nama_pelatihan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bidang</label>
                    <input value="{{ $pelatihan->bidang->bidang_nama }}" type="text" name="bidang_nama" id="bidang_nama"
                        class="form-control" required>
                    <small id="error-bidang_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Mata Kuliah</label>
                    <input value="{{ $pelatihan->matkul->mk_nama }}" type="text" name="mk_nama" id="mk_nama"
                        class="form-control" required>
                    <small id="error-mk_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Vendor</label>
                    <input value="{{ $pelatihan->vendor->vendor_nama }}" type="text" name="vendor" id="vendor"
                        class="form-control" required>
                    <small id="error-vendor" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Level Pelatihan</label>
                    <input value="{{ $pelatihan->level->level_nama}}" type="text" name="level_nama" id="level_nama"
                        class="form-control" required>
                    <small id="error-level_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input value="{{ $pelatihan->tanggal }}" type="date" name="tanggal" id="tanggal"
                        class="form-control" required>
                    <small id="error-tanggal" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Masa Berlaku</label>
                    <input value="{{ $pelatihan->tanggal_akhir }}" type="date" name="tanggal_akhir" id="tanggal_akhir"
                        class="form-control" required>
                    <small id="error-tanggal_akhir" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kuota</label>
                    <input value="{{ $pelatihan->kuota }}" type="number" name="kuota" id="kuota"
                        class="form-control" required>
                    <small id="error-kuota" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input value="{{ $pelatihan->lokasi }}" type="text" name="lokasi" id="lokasi"
                        class="form-control" required>
                    <small id="error-lokasi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Biaya</label>
                    <input value="{{ $pelatihan->biaya }}" type="number" name="biaya" id="biaya"
                        class="form-control" required>
                    <small id="error-biaya" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Periode</label>
                    <input value="{{ $pelatihan->periode }}" type="number" name="periode" id="periode"
                        class="form-control" required>
                    <small id="error-periode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <input value="{{ $pelatihan->status }}" type="text" name="status" id="status"
                        class="form-control" required>
                    <small id="error-status" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $("#form-edit-pelatihan").validate({
            rules: {
            nama_pelatihan: { 
            required: true, 
            minlength: 3, 
            maxlength: 255 
        },
        bidang_nama: { 
            required: true, 
            minlength: 3, 
            maxlength: 255 
        },
        mk_nama: { 
            required: true, 
            minlength: 3, 
            maxlength: 255 
        },
        vendor: { 
            required: true, 
            minlength: 3, 
            maxlength: 255 
        },
        level_nama: { 
            required: true, 
            minlength: 3, 
            maxlength: 100 
        },
        tanggal: { 
            required: true, 
            date: true 
        },
        tanggal_akhir: { 
            required: true, 
            date: true 
        },
        kuota: { 
            required: true, 
            min: 1, 
            number: true 
        },
        lokasi: { 
            required: true, 
            minlength: 3, 
            maxlength: 255 
        },
        biaya: { 
            required: true, 
            min: 0, 
            number: true 
        },
        periode: { 
            required: true, 
            minlength: 1, 
            maxlength: 50, 
            digits: true // Hanya angka yang diizinkan
        },
        status: { 
            required: true, 
            minlength: 3, 
            maxlength: 50 
        }
    },
            submitHandler: function (form) {
                let formData = new FormData(form); // Serialize form data
                $.ajax({
                    url: $(form).attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            datapelatihan.ajax.reload(); // Refresh datatable
                        } else {
                            $('.error-text').text('');
                            $.each(response.errors, function (key, value) {
                                $('#error-' + key).text(value);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal memproses data'
                        });
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endempty
