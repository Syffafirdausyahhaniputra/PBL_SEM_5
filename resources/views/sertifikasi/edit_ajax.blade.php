@empty($sertifikasi)
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
                <a href="{{ url('/sertifikasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/sertifikasi/' . $sertifikasi->sertif_id.'/update_ajax') }}" method="POST" id="form-edit-sertifikasi">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Sertifikasi</label>
                    <input value="{{ $sertifikasi->nama_sertif }}" type="text" name="nama_sertif" id="nama_sertif"
                        class="form-control" required>
                    <small id="error-nama_sertif" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="bidang_id">Bidang</label>
                    <select name="bidang_id" id="bidang_id" class="form-control" required>
                        <option value="">- Pilih Bidang -</option>
                        @foreach($bidang as $b)
                            <option value="{{ $b->bidang_id }}" {{ $sertifikasi->bidang_id == $b->bidang_id ? 'selected' : '' }}>
                                {{ $b->bidang_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-bidang_id" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="mk_id">Mata Kuliah</label>
                    <select name="mk_id" id="mk_id" class="form-control" required>
                        <option value="">- Pilih matkul -</option>
                        @foreach($matkul as $m)
                            <option value="{{ $m->mk_id }}" {{ $sertifikasi->mk_id == $m->mk_id ? 'selected' : '' }}>
                                {{ $m->mk_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-mk_id" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="vendor_id">Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-control" required>
                        <option value="">- Pilih Vendor -</option>
                        @foreach($vendor as $v) <!-- Assuming $vendors is passed from the controller -->
                            <option value="{{ $v->vendor_id }}" {{ $sertifikasi->vendor_id == $v->vendor_id ? 'selected' : '' }}>
                                {{ $v->vendor_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-vendor_id" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="jenis_id">Jenis Sertifikasi</label>
                    <select name="jenis_id" id="jenis_id" class="form-control" required>
                        <option value="">- Pilih Jenis Sertifikasi -</option>
                        @foreach($jenis as $j) <!-- Assuming $levels is passed from the controller -->
                            <option value="{{ $j->jenis_id }}" {{ $sertifikasi->jenis_id == $j->jenis_id ? 'selected' : '' }}>
                                {{ $j->jenis_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-jenis_id" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input value="{{ $sertifikasi->tanggal }}" type="date" name="tanggal" id="tanggal"
                        class="form-control" required>
                    <small id="error-tanggal" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Masa Berlaku</label>
                    <input value="{{ $sertifikasi->masa_berlaku }}" type="date" name="masa_berlaku" id="masa_berlaku"
                        class="form-control" required>
                    <small id="error-masa_berlaku" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Periode</label>
                    <input value="{{ $sertifikasi->periode }}" type="number" name="periode" id="periode"
                        class="form-control" required>
                    <small id="error-periode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <input value="{{ $sertifikasi->status }}" type="text" name="status" id="status"
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
        $("#form-edit-sertifikasi").validate({
            rules: {
            nama_sertif: { 
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
        jenis_nama: { 
            required: true, 
            minlength: 3, 
            maxlength: 100 
        },
        tanggal: { 
            required: true, 
            date: true 
        },
        masa_berlaku: { 
            required: true, 
            date: true 
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
                        Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 2000, // Notifikasi ditampilkan selama 2 detik
                        showConfirmButton: false,
                    }).then(() => {
                        location.reload(); // Reload halaman setelah notifikasi
                    });

                    $('#modal-master').modal('hide');
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