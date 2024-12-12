<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
<form action="{{ url('/pelatihan/create_ajax2') }}" method="POST" id="form-tambah-pelatihan">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Pelatihan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    </div>
    {{-- <div class="modal-body">
        <!-- Dosen -->
        <div class="form-group">
            <label>Dosen</label>
            <select name="dosen_id" class="form-control" id="dosen_id" required>
                <option value="">- Pilih Dosen -</option>
                @foreach($dosens as $dosen)
                    <option value="{{ $dosen->dosen_id }}">{{ $dosen->nama }}</option>
                @endforeach
            </select>
            <small id="error-dosen_id" class="error-text form-text text-danger"></small>
        </div> --}}

        <!-- Nama Pelatihan -->
        <div class="form-group">
            <label for="nama_pelatihan">Nama Pelatihan</label>
            <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control"
                required>
            <small id="error-nama_pelatihan" class="error-text form-text text-danger"></small>
        </div>

        <!-- Bidang -->
        <div class="form-group">
            <label for="bidang_id">Bidang</label>
            <select name="bidang_id" id="bidang_id" class="form-control" required>
                <option value="">Pilih Bidang</option>
                @foreach ($bidangs as $bidang)
                    <option value="{{ $bidang->bidang_id }}">{{ $bidang->bidang_nama }}</option>
                @endforeach
            </select>
            <small id="error-bidang_id" class="error-text form-text text-danger"></small>
        </div>

        <!-- Mata Kuliah -->
        <div class="form-group">
            <label for="mk_id">Mata Kuliah</label>
            <select name="mk_id" id="mk_id" class="form-control" required>
                <option value="">Pilih Mata Kuliah</option>
                @foreach ($matkuls as $matkul)
                    <option value="{{ $matkul->mk_id }}">{{ $matkul->mk_nama }}</option>
                @endforeach
            </select>
            <small id="error-mk_id" class="error-text form-text text-danger"></small>
        </div>

        <!-- Vendor -->
        <div class="form-group">
            <label for="vendor_id">Vendor</label>
            <select class="form-control" id="vendor_id" name="vendor_id" required>
                <option value="">Pilih Vendor</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_nama }}</option>
                @endforeach
            </select>
            <small id="error-vendor_id" class="error-text form-text text-danger"></small>
        </div>

        <!-- Level -->
        <div class="form-group">
            <label for="level_id">Level</label>
            <select class="form-control" id="level_id" name="level_id" required>
                <option value="">Pilih Level</option>
                @foreach ($levels as $level)
                    <option value="{{ $level->level_id }}">{{ $level->level_nama }}</option>
                @endforeach
            </select>
            <small id="error-level_id" class="error-text form-text text-danger"></small>
        </div>

        <!-- Tanggal Mulai -->
        <div class="form-group">
            <label for="tanggal">Tanggal Mulai</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            <small id="error-tanggal" class="error-text form-text text-danger"></small>
        </div>

        <!-- Tanggal Akhir -->
        <div class="form-group">
            <label for="tanggal_akhir">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required>
            <small id="error-tanggal_akhir" class="error-text form-text text-danger"></small>
        </div>

        <!-- Lokasi -->
        <div class="form-group">
            <label for="lokasi">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" required>
            <small id="error-lokasi" class="error-text form-text text-danger"></small>
        </div>

        <!-- Periode -->
        <div class="form-group">
            <label for="periode">Periode</label>
            <input type="text" name="periode" id="periode" class="form-control" required>
            <small id="error-periode" class="error-text form-text text-danger"></small>
                <!-- Formulir Upload File -->
                <div class="form-group">
                    <label for="file">Upload Dokumen:</label>
                    <input type="file" class="form-control" id="file" name="file" required>
                    <!-- Validasi error -->
                    @error('file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
    </div>
    </div>
</div>
</form>
<script>
$(document).ready(function () {
    $('#form-tambah-pelatihan').on('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: this.action,
            type: this.method,
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
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
                    $.each(response.msgField, function (prefix, val) {
                        $('#error-' + prefix).text(val[0]);
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: response.message,
                    });
                }
            },
            error: function (xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                if (xhr.status === 422) {
                    $('.error-text').text('');
                    $.each(xhr.responseJSON.errors, function (prefix, val) {
                        $('#error-' + prefix).text(val[0]);
                    });
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: errorMessage,
                });
            },
        });
    });
});

</script>