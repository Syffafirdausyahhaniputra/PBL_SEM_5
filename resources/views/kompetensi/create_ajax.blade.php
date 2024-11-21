<form action="{{ url('/kompetensi_prodi/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kompetensi Prodi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Prodi</label>
                    <select name="prodi_id" id="prodi_id" class="form-control" required>
                        <option value="">- Pilih Prodi -</option>
                        @foreach ($prodi as $p)
                            <option value="{{ $p->prodi_id }}">{{ $p->prodi_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-prodi_id" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Bidang</label>
                    <div id="bidang-container">
                        <!-- Bidang select template -->
                        <div class="bidang-item" style="display: flex; align-items: center; margin-bottom: 10px;">
                            <select name="bidang_id[]" class="form-control" style="flex: 1; margin-right: 10px;"
                                required>
                                <option value="">- Pilih Bidang -</option>
                                @foreach ($bidang as $b)
                                    <option value="{{ $b->bidang_id }}">{{ $b->bidang_nama }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-sm btn-danger remove-bidang">Hapus</button>
                        </div>
                    </div>
                    <button type="button" id="add-bidang" class="btn btn-sm btn-primary mt-2">Tambah Bidang</button>
                    <small id="error-bidang_id" class="error-text form-text text-danger"></small>
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
        // Tambah bidang baru
        $("#add-bidang").on("click", function() {
            const newField = `
            <div class="bidang-item" style="display: flex; align-items: center; margin-bottom: 10px;">
                <select name="bidang_id[]" class="form-control bidang-select" style="flex: 1; margin-right: 10px;" required>
                    <option value="">- Pilih Bidang -</option>
                        @foreach ($bidang as $b)
                            <option value="{{ $b->bidang_id }}">{{ $b->bidang_nama }}</option>
                        @endforeach
                </select>
                <button type="button" class="btn btn-sm btn-danger remove-bidang">Hapus</button>
            </div>
            `;
            $("#bidang-container").append(newField);
        });

        // Hapus bidang
        $(document).on("click", ".remove-bidang", function() {
            $(this).closest(".bidang-item").remove();
        });

        // Validasi sebelum submit
        $("#form-tambah").on("submit", function(e) {
            e.preventDefault(); // Mencegah submit default

            let bidangSelected = [];
            let hasDuplicate = false;

            $(".bidang-select").each(function() {
                const value = $(this).val();
                if (value) {
                    if (bidangSelected.includes(value)) {
                        hasDuplicate = true;
                        return false; // Keluar dari loop
                    }
                    bidangSelected.push(value);
                }
            });

            if (hasDuplicate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Bidang tidak boleh duplikat.'
                });
                return false;
            }

            // Submit form via Ajax jika tidak ada duplikasi
            const form = this;
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        if (typeof dataKompetensi !== "undefined") {
                            dataKompetensi.ajax.reload();
                        }
                    } else {
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) { // Validasi gagal
                        const response = xhr.responseJSON;
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message || 'Validasi gagal, periksa input Anda.'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan pada inputan (terjadi duplikasi).'
                        });
                    }
                }
            });
        });
    });
</script>
