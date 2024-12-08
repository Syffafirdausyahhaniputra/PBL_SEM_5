<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form action="{{ url('/pelatihan/tunjuk/store') }}" method="POST" id="form-tambah-pelatihan">
            @csrf
            <div id="modal-master" class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Penunjukkan Pelatihan</h5>
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
                                    <option value="{{ $bidang->id }}">{{ $bidang->bidang_nama }}</option>
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
                                    <option value="{{ $matkul->id }}">{{ $matkul->mk_nama }}</option>
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
            submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        dataType: 'json', // Tambahkan ini untuk parsing JSON
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
                            // Tambahkan penanganan error yang lebih detail
                            let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            // Tampilkan pesan error validasi
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
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
            });
        </script>
    </div>
</div>