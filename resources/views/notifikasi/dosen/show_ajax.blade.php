@empty($nama)
    <div id="modal-master" class="modal-dialog modal-lg" jenis="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/notifikasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" jenis="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Notifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info-circle"></i> Informasi !!!</h5>
                    Berikut adalah detail notifikasi:
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Nama:</th>
                        <td class="col-9">{{ $nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bidang:</th>
                        <td class="col-9">{{ $bidang }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mata Kuliah:</th>
                        <td class="col-9">{{ $matkul }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Vendor:</th>
                        <td class="col-9">{{ $vendor }}</td>
                    </tr>
                    @if (isset($jenis))
                        <tr>
                            <th class="text-right col-3">Jenis:</th>
                            <td class="col-9">{{ $jenis }}</td>
                        </tr>
                    @endif
                    @if (isset($level))
                        <tr>
                            <th class="text-right col-3">Level:</th>
                            <td class="col-9">{{ $level }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th class="text-right col-3">Tanggal Acara:</th>
                        <td class="col-9">{{ $tanggal_acara }}</td>
                    </tr>
                    @if (isset($berlaku_hingga))
                        <tr>
                            <th class="text-right col-3">Berlaku Hingga:</th>
                            <td class="col-9">{{ $berlaku_hingga }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th class="text-right col-3">Periode:</th>
                        <td class="col-9">{{ $periode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Keterangan:</th>
                        <td class="col-9">{{ $keterangan }}</td>
                    </tr>
                    @if (isset($surat_tugas['file_url']))
                        <tr>
                            <th class="text-right col-3">Surat Tugas:</th>
                            <td class="col-9">
                                <a href="{{ $surat_tugas['file_url'] }}" target="_blank" class="btn btn-warning">
                                    Download Surat Tugas
                                </a>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <th class="text-right col-3">Surat Tugas:</th>
                            <td class="col-9">Tidak ada surat tugas terkait.</td>
                        </tr>
                    @endif
                    <tr>
                        <th class="text-right col-3">Upload Sertifikat:</th>
                        <td class="col-9">
                            <form id="form-upload-sertifikat" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ isset($type) && $type === 'sertifikasi' ? $data_sertif_id : $data_pelatihan_id }}">
                                <input type="hidden" name="type" value="{{ isset($type) ? 'sertifikasi' : 'pelatihan' }}">
                                <input type="file" name="file_sertifikat" class="form-control mb-2" required>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty

<script>
    $('#form-upload-sertifikat').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: '{{ url('/notifikasidosen/upload-sertifikat') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(xhr) {
                alert('Gagal mengunggah sertifikat: ' + xhr.responseJSON.message);
            }
        });
    });
</script>
