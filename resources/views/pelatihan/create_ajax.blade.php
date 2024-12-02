<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
<form action="{{ url('/pelatihan/store_ajax') }}" method="POST" id="form-tambah-pelatihan">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Pelatihan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <!-- Nama Pelatihan -->
        <div class="form-group">
            <label for="nama_pelatihan">Nama Pelatihan</label>
            <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control" required>
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

        <!-- Vendor -->
        <div class="form-group">
            <label for="vendor_id">Vendor</label>
            <select name="vendor_id" id="vendor_id" class="form-control" required>
                <option value="">Pilih Vendor</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Kuota -->
        <div class="form-group">
            <label for="kuota">Kuota</label>
            <input type="number" name="kuota" id="kuota" class="form-control" required>
        </div>

        <!-- Biaya -->
        <div class="form-group">
            <label for="biaya">Biaya</label>
            <input type="number" name="biaya" id="biaya" class="form-control" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
            rules: {
                nama_pelatihan: { required: true, minlength: 3 },
                tanggal: { required: true, date: true },
                tanggal_akhir: { required: true, date: true },
                kuota: { required: true, number: true },
                lokasi: { required: true, minlength: 3 },
                biaya: { required: true, number: true },
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
