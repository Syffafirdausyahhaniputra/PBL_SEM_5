<div id="modal-master" class="modal-dialog modal-lg" role="document"> 
    <div class="modal-content"> 
        <div class="modal-header"> 
            <h5 class="modal-title">Detail Data Kompetensi Prodi</h5> 
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> 
        </div> 
        <div class="modal-body"> 
            <div class="alert alert-info"> 
                <h5><i class="icon fas fa-info-circle"></i> Informasi !!!</h5> 
                Berikut adalah detail data kompetensi prodi:
            </div> 
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">Prodi :</th>
                    <td class="col-9">{{ $prodi->prodi_nama }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Bidang :</th>
                    <td class="col-9">
                        <ul>
                            @foreach ($bidangList as $item)
                                <li>{{ $item->bidang->bidang_nama }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            </table> 
        </div> 
        <div class="modal-footer"> 
            <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button> 
        </div> 
    </div> 
</div>
