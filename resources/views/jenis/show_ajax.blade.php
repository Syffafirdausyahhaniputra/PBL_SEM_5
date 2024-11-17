@empty($jenis) 
    <div id="modal-master" class="modal-dialog modal-lg" jenis="document"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5> 
                <button type="button" class="close" data-dismiss="modal" 
                aria-label="Close"><span aria-hidden="true">&times;</span></button> 
            </div> 
            <div class="modal-body"> 
                <div class="alert alert-danger"> 
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5> 
                    Data yang anda cari tidak ditemukan
                </div> 
                <a href="{{ url('/jenis/') }}" class="btn btn-warning">Kembali</a> 
            </div> 
        </div> 
    </div> 
@else 
    <div id="modal-master" class="modal-dialog modal-lg" jenis="document"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <h5 class="modal-title" id="exampleModalLabel">Detail Jenis Sertifikasi</h5> 
                <button type="button" class="close" data-dismiss="modal" 
                aria-label="Close"><span aria-hidden="true">&times;</span></button> 
            </div> 
            <div class="modal-body"> 
                <div class="alert alert-info"> 
                    <h5><i class="icon fas fa-info-circle"></i> Informasi !!!</h5> 
                    Berikut adalah detail jenis sertifikasi:
                </div> 
                <table class="table table-sm table-bordered table-striped"> 
                    <tr><th class="text-right col-3">Kode Jenis Sertifikasi :</th><td class="col-9">{{ $jenis->jenis_kode }}</td></tr> 
                    <tr><th class="text-right col-3">Nama Jenis Sertifikasi :</th><td class="col-9">{{ $jenis->jenis_nama }}</td></tr> 
                </table> 
            </div> 
            <div class="modal-footer"> 
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button> 
            </div> 
        </div> 
    </div> 
@endempty