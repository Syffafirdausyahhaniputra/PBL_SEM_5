<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
<form action="{{ url('/pelatihan/store_ajax') }}" method="POST" id="form-tambah-pelatihan">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Pelatihan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body">
        <!-- Nama Pelatihan -->
        <div class="form-group">
            <label for="nama_pelatihan">Nama Pelatihan</label>
            <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control" required>
        </div>

        <!-- Bidang -->
        <div class="form-group">
            <label for="bidang_id">Bidang</label>
            <select name="bidang_id" id="bidang_id" class="form-control" required>
                <option value="">Pilih Bidang</option>
                @foreach ($bidangs as $bidang)
                    <option value="{{ $bidang->id }}">{{ $bidang->bidang_nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Mata Kuliah -->
        <div class="form-group">
            <label for="mk_id">Mata Kuliah</label>
            <select name="mk_id" id="mk_id" class="form-control" required>
                <option value="">Pilih Mata Kuliah</option>
                @foreach ($matkuls as $matkul)
                    <option value="{{ $matkul->id }}">{{ $matkul->mk_nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Vendor -->
        <div class="mb-3">
            <label for="vendor_id" class="form-label">Vendor</label>
            <select class="form-control" id="vendor_id" name="vendor_id" required>
                <option value="">Pilih Vendor</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Level -->
        <div class="mb-3">
            <label for="level_id" class="form-label">Level</label>
            <select class="form-control" id="level_id" name="level_id" required>
                <option value="">Pilih Level</option>
                @foreach($levels as $level)
                    <option value="{{ $level->level_id }}">{{ $level->level_nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tanggal -->
        <div class="form-group">
            <label for="tanggal">Tanggal Mulai</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="tanggal_akhir">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required>
        </div>

         <!-- Kuota -->
         <div class="form-group">
            <label for="kuota">Kuota</label>
            <input type="number" name="kuota" id="kuota" class="form-control" required>
        </div>

         <!-- Lokasi -->
         <div class="form-group">
            <label for="lokasi">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" required>
        </div>

        <!-- Biaya -->
        <div class="form-group">
            <label for="biaya">Biaya</label>
            <input type="number" name="biaya" id="biaya" class="form-control" required>
        </div>

         <!-- Periode -->
        <div class="form-group">
            <label for="biaya">Periode</label>
            <input type="number" name="periode" id="periode" class="form-control" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form-tambah-pelatihan').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
                        Swal.fire('Berhasil', response.message, 'success');
                        $('#modal-container').modal('hide');
                        $('#table-pelatihan').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error');
                }
            });
        });

        $("#form-tambah-pelatihan").validate({
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
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#modal-master').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataPelatihan.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
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
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
