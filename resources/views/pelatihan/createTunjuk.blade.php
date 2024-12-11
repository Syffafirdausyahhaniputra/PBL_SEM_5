        <form action="{{ url('/pelatihan/tunjuk/store') }}" method="POST" id="form-tambah-pelatihan">
            @csrf
            <div id="modal-master" class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Rekomendasi Pelatihan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
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

                        <!-- Kuota -->
                        <div class="form-group">
                            <label for="kuota">Kuota</label>
                            <input type="number" name="kuota" id="kuota" class="form-control" required>
                            <small id="error-kuota" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="anggota">Anggota</label>
                            <div id="anggota-container">
                                <select class="form-control anggota-select" name="dosen_id[]" required>
                                    <option value="">Pilih Anggota</option>
                                    @foreach ($dataP as $data)
                                        <option value="{{ $data->dosen_id }}">
                                            {{ $data->user ? $data->user->nama : 'Nama tidak tersedia' }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="form-group mt-2">
                                    <label for="golongan_id">Golongan</label>
                                    <input type="text" class="form-control" name="golongan_id[]"
                                        placeholder="Masukkan Golongan">
                                </div>

                                <div class="form-group mt-2">
                                    <label for="jabatan_id">Jabatan</label>
                                    <input type="text" class="form-control" name="jabatan_id[]"
                                        placeholder="Masukkan Jabatan">
                                </div>
                            </div>
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
                $("#form_tambah").validate({
                    rules: {
                        nama_pelatihan: {
                            required: true,
                            minlength: 3,
                            maxlength: 20
                        },
                        bidang_id: {
                            required: true,
                            number: true,

                        },
                        vendor_id: {
                            required: true,
                            number: true,

                        },
                        mk_id: {
                            required: true,
                            number: true,

                        },
                        level_id: {
                            required: true,
                            number: true,

                        },
                        tanggal: {
                            required: true,

                        },
                        tanggal_akhir: {
                            required: true,

                        },
                        kuota: {
                            required: true,
                            minlength: 3,
                            maxlength: 20
                        },
                        dosen_id: {
                            required: true,
                            number: true,

                        },
                        lokasi: {
                            required: true,
                            minlength: 3,
                            maxlength: 20
                        },
                        biaya: {
                            required: true,
                            minlength: 3,
                            maxlength: 20
                        },
                        periode: {
                            required: true,
                            minlength: 3,
                            maxlength: 20
                        },
                    }
                })
                // Ketika nilai kuota berubah
                $('#kuota').on('input', function() {
                    const kuota = parseInt($(this).val()) ||
                    1; // Ambil nilai kuota (default 0 jika kosong atau invalid)
                    const anggotaContainer = $('#anggota-container');

                    // Bersihkan container sebelum ditambahkan ulang
                    anggotaContainer.empty();

                    if (kuota > 0) {
                        // Tambahkan dropdown pemilihan anggota sebanyak kuota
                        for (let i = 1; i <= kuota; i++) {
                            const anggotaSelect = `
                                <div class="mt-2">
                                    <label>Anggota ${i}</label>
                                    <select class="form-control anggota-select" name="dosen_id[]" required>
                                        <option value="">Pilih Anggota</option>
                                        @foreach ($dataP as $data)
                                            <option value="{{ $data->dosen_id }}">
                                                {{ $data->user ? $data->user->nama : 'Nama tidak tersedia' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>`;
                            anggotaContainer.append(anggotaSelect);
                        }
                    }
                });

                // Handle pengiriman form
                $('#form-tambah-pelatihan').on('submit', function(event) {
                    event.preventDefault(); // Mencegah pengiriman langsung

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
                                }).then(()=>{
                                    window.location.href = "{{url('/pelatihan')}}";
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
