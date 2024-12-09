<form action="{{ url('/sertifikasi/tunjuk/store') }}" method="POST" id="form-tambah-pelatihan">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Rekomendasi Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nama_sertif" class="form-label">Nama Sertifikasi</label>
                    <input type="text" class="form-control" id="nama_sertif" name="nama_sertif" required>
                </div>
                <div class="mb-3">
                    <label for="bidang_id" class="form-label">Bidang</label>
                    <select class="form-control" id="bidang_id" name="bidang_id" required>
                        <option value="">Pilih Bidang</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->bidang_id }}">{{ $bidang->bidang_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jenis_id" class="form-label">Jenis Sertifikasi</label>
                    <select class="form-control" id="jenis_id" name="jenis_id" required>
                        <option value="">Pilih Jenis Sertifikasi</option>
                        @foreach($jenis as $j)
                            <option value="{{ $j->jenis_id }}">{{ $j->jenis_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="masa_berlaku" class="form-label">Masa Berlaku</label>
                    <input type="date" class="form-control" id="masa_berlaku" name="masa_berlaku">
                </div>
                <div class="mb-3">
                    <label for="mk_id" class="form-label">Mata Kuliah</label>
                    <select class="form-control" id="mk_id" name="mk_id" required>
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($matkuls as $matkul)
                            <option value="{{ $matkul->mk_id }}">{{ $matkul->mk_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="vendor_id" class="form-label">Vendor</label>
                    <select class="form-control" id="vendor_id" name="vendor_id" required>
                        <option value="">Pilih Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required>
                </div>
                <div class="mb-3">
                    <label for="masa_berlaku" class="form-label">Masa Berlaku</label>
                    <input type="date" class="form-control" id="masa_berlaku" name="masa_berlaku" required>
                </div>
                <div class="mb-3">
                    <label for="periode" class="form-label">Periode</label>
                    <input type="text" class="form-control" id="periode" name="periode" required>
                </div>                
 

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
                    <label for="jenis_id">Jenis Sertifikasi</label>
                    <select class="form-control" id="jenis_id" name="jenis_id" required>
                        <option value="">Pilih Level</option>
                        @foreach ($jenis as $jenis)
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
                    <label for="tanggal_akhir">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required>
                    <small id="error-tanggal_akhir" class="error-text form-text text-danger"></small>
                </div>

                <!-- Kuota -->
                <div class="form-group">
                    <label for="kuota">Kuota</label>
                    <input type="number" name="kuota" id="kuota" class="form-control" required>
                    <small id="error-kuota" class="error-text form-text text-danger"></small>
                </div>

                <!-- Anggota -->
                <div class="form-group">
                    <label for="dosen_id">Anggota</label>
                    <select class="form-control" id="dosen_id" name="dosen_id" required>
                        <option value="">Pilih Anggota</option>
                        @foreach ($dataP as $data)
                            <option value="{{ $data->dosen_id }}">
                                {{ $data->user ? $data->user->name : 'Nama tidak tersedia' }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-dosen_id" class="error-text form-text text-danger"></small>
                </div>


                <!-- Lokasi -->
                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control" required>
                    <small id="error-lokasi" class="error-text form-text text-danger"></small>
                </div>

                <!-- Biaya -->
                <div class="form-group">
                    <label for="biaya">Biaya</label>
                    <input type="number" name="biaya" id="biaya" class="form-control" required>
                    <small id="error-biaya" class="error-text form-text text-danger"></small>
                </div>

                <!-- Periode -->
                <div class="form-group">
                    <label for="periode">Periode</label>
                    <input type="text" name="periode" id="periode" class="form-control" required>
                    <small id="error-periode" class="error-text form-text text-danger"></small>
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
    $(document).ready(function() {
        $('#form-tambah-pelatihan').on('submit', function(event) {
            event.preventDefault(); // Mencegah form dikirim langsung
            $.ajax({
                url: this.action,
                type: this.method,
                data: $(this).serialize(),
                dataType: 'json', // Parsing JSON
                success: function(response) {
                    if (response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        $('#table-pelatihan').DataTable().ajax.reload();
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
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    if (xhr.status === 422) {
                        $('.error-text').text('');
                        $.each(xhr.responseJSON.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessage
                    });
                }
            });
        });
    });
</script>
