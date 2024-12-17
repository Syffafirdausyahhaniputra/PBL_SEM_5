@empty($sertifikasi)
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
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/pelatihan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/sertifikasi/' . $sertifikasi->sertif_id . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Sertifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                        Apakah Anda ingin menghapus data berikut?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Nama Sertifikasi</th>
                            <td class="col-9">{{ $sertifikasi->nama_sertif }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Bidang :</th>
                            <td class="col-9">{{ $pelatihan->bidang->bidang_nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Mata Kuliah :</th>
                            <td class="col-9">{{ $pelatihan->matkul->mk_nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Vendor :</th>
                            <td class="col-9">{{ $sertifikasi->vendor->vendor_nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jenis Sertifikasi :</th>
                            <td class="col-9">{{ $sertifikasi->jenis->jenis_nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal :</th>
                            <td class="col-9">{{ \Carbon\Carbon::parse($sertifikasi->tanggal)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Masa Berlaku :</th>
                            <td class="col-9">{{ \Carbon\Carbon::parse($sertifikasi->masa_berlaku)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Periode :</th>
                            <td class="col-9">{{ $sertifikasi->periode }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Status :</th>
                            <td class="col-9">{{ $sertifikasi->status }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>
@endif
<script>
    $(document).ready(function() {
         // Event submit form
         $("#form-delete").on('submit', function(event) {
             event.preventDefault(); // Prevent page reload
             var form = $(this);
 
             $.ajax({
                 url: form.attr('action'),
                 type: form.attr('method'),
                 data: form.serialize(),
                 success: function(response) {
                     if (response.status) {
                         $('#myModal').modal('hide');
                         $('#modal-master').modal('hide');
                         Swal.fire({
                             icon: 'success',
                             title: 'Berhasil',
                             text: response.message
                         }).then(() => {
                             // Reload the page directly
                             location.reload(); // This will reload the page immediately after success
                         });
                     } else {
                         // Show error message
                         Swal.fire({
                             icon: 'error',
                             title: 'Terjadi Kesalahan',
                             text: response.message
                         });
                     }
                 },
                 error: function(xhr, status, error) {
                     Swal.fire({
                         icon: 'error',
                         title: 'Terjadi Kesalahan',
                         text: 'Gagal menghapus data. Silakan coba lagi.'
                     });
                 }
             });
         });
     });
 </script>