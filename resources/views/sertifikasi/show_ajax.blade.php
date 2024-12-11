@empty($dataSertifikasi)
<div id="modal-master" class="modal-dialog modal-lg" role="document"> 
    <div class="modal-content"> 
        <div class="modal-header"> 
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5> 
            <button type="button" class="close" data-dismiss="modal" 
            aria-label="Close"><span aria-hidden="true">&times;</span></button> 
        </div> 
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Sertifikat tidak ditemukan
                </div>
                <a href="{{ url('/sertifikasi/') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info-circle"></i> Informasi !!!</h5>
                    Berikut adalah detail data sertifikasi:
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Nama Sertifikasi :</th>
                        <td class="col-9">{{ $dataSertifikasi->sertifikasi->nama_sertif }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bidang :</th>
                        <td class="col-9">{{ $dataSertifikasi->sertifikasi->bidang->bidang_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mata Kuliah :</th>
                        <td class="col-9">{{ $dataSertifikasi->sertifikasi->matkul->mk_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Vendor :</th>
                        <td class="col-9">{{ $dataSertifikasi->sertifikasi->vendor->vendor_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Sertifikasi :</th>
                        <td class="col-9">{{ $dataSertifikasi->sertifikasi->jenis->jenis_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal :</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($dataSertifikasi->sertifikasi->tanggal)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Masa Berlaku :</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($dataSertifikasi->sertifikasi->masa_berlaku)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Periode :</th>
                        <td class="col-9">{{ $dataSertifikasi->sertifikasi->periode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Status :</th>
                        <td class="col-9">{{ $dataSertifikasi->status }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-body"> 
                <div class="alert alert-info"> 
                    <h5><i class="icon fas fa-info-circle"></i> Dokumen Kegiatan</h5> 
                    Dokumen terkait kegiatan
            </div>
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Nama Dokumen</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">{{ $dataSertifikasi->sertifikat }}</td>
                        <td class="text-center">
                            <a href="{{ route('sertifikasi.downloadSertifikat', $dataSertifikasi->sertif_id) }}"
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer"> 
            <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button> 
        </div> 
    </div>
</div>
@endforelse
