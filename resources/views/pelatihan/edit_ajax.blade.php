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
                    <label for="bidang_id">Bidang</label>
                    <select name="bidang_id" id="bidang_id" class="form-control" required>
                        <option value="">- Pilih Bidang -</option>
                        @foreach($bidang as $b)
                            <option value="{{ $b->bidang_id }}" {{ $pelatihan->bidang_id == $b->bidang_id ? 'selected' : '' }}>
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
                            <option value="{{ $m->mk_id }}" {{ $pelatihan->mk_id == $m->mk_id ? 'selected' : '' }}>
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
                            <option value="{{ $v->vendor_id }}" {{ $pelatihan->vendor_id == $v->vendor_id ? 'selected' : '' }}>
                                {{ $v->vendor_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-vendor_id" class="form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label for="level_id">Level Pelatihan</label>
                    <select name="level_id" id="level_id" class="form-control" required>
                        <option value="">- Pilih Level Pelatihan -</option>
                        @foreach($level as $l) <!-- Assuming $levels is passed from the controller -->
                            <option value="{{ $l->level_id }}" {{ $pelatihan->level_id == $l->level_id ? 'selected' : '' }}>
                                {{ $l->level_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-level_id" class="form-text text-danger"></small>
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
                    <label>Lokasi</label>
                    <input value="{{ $pelatihan->lokasi }}" type="text" name="lokasi" id="lokasi"
                        class="form-control" required>
                    <small id="error-lokasi" class="error-text form-text text-danger"></small>
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