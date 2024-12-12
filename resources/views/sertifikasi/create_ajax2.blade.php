<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
<form action="{{ url('/sertifikasi/create_ajax2') }}" method="POST" id="form-tambah-sertifikasi">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Data Sertifikasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body">
        {{-- <!-- Dosen -->
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
            <label for="nama_sertif">Nama Sertifikasi</label>
            <input type="text" name="nama_sertif" id="nama_sertif" class="form-control"
                required>
            <small id="error-nama_sertif" class="error-text form-text text-danger"></small>
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

        <!-- Jenis -->
        <div class="form-group">
            <label for="jenis_id">Jenis Sertifikasi</label>
            <select class="form-control" id="jenis_id" name="jenis_id" required>
                <option value="">Pilih Jenis Sertifikasi</option>
                @foreach ($jeniss as $jenis)
                    <option value="{{ $jenis->jenis_id }}">{{ $jenis->jenis_nama }}</option>
                @endforeach
            </select>
            <small id="error-jenis_id" class="error-text form-text text-danger"></small>
        </div>

        <!-- Tanggal Mulai -->
        <div class="form-group">
            <label for="tanggal">Tanggal Mulai</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            <small id="error-tanggal" class="error-text form-text text-danger"></small>
        </div>

        <!-- Tanggal Akhir -->
        <div class="form-group">
            <label for="masa_berlaku">Masa Berlaku</label>
            <input type="date" name="masa_berlaku" id="masa_berlaku" class="form-control" required>
            <small id="error-masa_berlaku" class="error-text form-text text-danger"></small>
        </div>

        {{-- <!-- Lokasi -->
        <div class="form-group">
            <label for="lokasi">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" required>
            <small id="error-lokasi" class="error-text form-text text-danger"></small>
        </div> --}}

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
    $('#form-tambah-sertifikasi').on('submit', function (event) {
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