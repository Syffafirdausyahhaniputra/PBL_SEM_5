@empty($kompetensi_prodi)
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
                <a href="{{ url('/kompetensi_prodi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/kompetensi_prodi/update_ajax/' . $kompetensi_prodi->prodi_id) }}" method="POST"
        id="form-edit-kompetensi-prodi">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Kompetensi Prodi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Prodi</label>
                        <select name="prodi_id" id="prodi_id" class="form-control" required>
                            <option value="">- Pilih Prodi -</option>
                            @foreach ($prodi as $p)
                                <option value="{{ $p->prodi_id }}"
                                    {{ $kompetensi_prodi->prodi_id == $p->prodi_id ? 'selected' : '' }}>
                                    {{ $p->prodi_nama }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-prodi_id" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Bidang</label>
                        <div id="bidang-container">
                            @foreach ($bidangList as $bidangId)
                                <div class="bidang-item" style="display: flex; align-items: center; margin-bottom: 10px;">
                                    <select name="bidang_id[]" class="form-control bidang-select"
                                        style="flex: 1; margin-right: 10px;" required>
                                        <option value="">- Pilih Bidang -</option>
                                        @foreach ($bidang as $b)
                                            <option value="{{ $b->bidang_id }}"
                                                {{ $b->bidang_id == $bidangId ? 'selected' : '' }}>
                                                {{ $b->bidang_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-sm btn-danger remove-bidang">Hapus</button>
                                </div>
                            @endforeach
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
                // Pastikan ada minimal satu bidang
                if ($(".bidang-item").length > 1) {
                    $(this).closest(".bidang-item").remove();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan",
                        text: "Setiap prodi harus memiliki minimal 1 bidang.",
                    });
                }
            });

            // Validasi sebelum submit
            $(document).ready(function() {
                $("#form-edit-kompetensi-prodi").on("submit", function(e) {
                    e.preventDefault();

                    const prodiSelected = $("#prodi_id").val();
                    const bidangSelected = [];
                    let hasDuplicate = false;

                    $(".bidang-select").each(function() {
                        const value = $(this).val();
                        if (value) {
                            if (bidangSelected.includes(value)) {
                                hasDuplicate = true;
                                Swal.fire({
                                    icon: "error",
                                    title: "Terjadi Kesalahan",
                                    text: "Bidang tidak boleh duplikat dalam prodi yang sama."
                                });
                                return false;
                            }
                            bidangSelected.push(value);
                        }
                    });

                    if (!prodiSelected || bidangSelected.length < 1) {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: "Prodi dan minimal satu bidang harus dipilih.",
                        });
                        return false;
                    }

                    if (!hasDuplicate) {
                        const form = this;
                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: $(form).serialize(),
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message
                                    }).then(() => {
                                        $('#myModal').modal('hide');
                                        if (typeof dataKompetensiProdi !==
                                            "undefined") {
                                            dataKompetensiProdi.ajax.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Terjadi Kesalahan',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: 'Terjadi kesalahan pada server.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endempty
