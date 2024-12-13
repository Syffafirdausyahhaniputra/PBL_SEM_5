@empty($dataPelatihan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
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
                    Sertifikat tidak ditemukan
                </div>
                <a href="{{ url('/pelatihan/') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info-circle"></i> Informasi !!!</h5>
                    Berikut adalah detail data pelatihan:
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Nama Pelatihan :</th>
                        <td class="col-9">{{ $dataPelatihan->pelatihan->nama_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bidang :</th>
                        <td class="col-9">{{ $dataPelatihan->pelatihan->bidang->bidang_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mata Kuliah :</th>
                        <td class="col-9">{{ $dataPelatihan->pelatihan->matkul->mk_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Vendor :</th>
                        <td class="col-9">{{ $dataPelatihan->pelatihan->vendor->vendor_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Level Pelatihan :</th>
                        <td class="col-9">{{ $dataPelatihan->pelatihan->level->level_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal :</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($dataPelatihan->pelatihan->tanggal)->format('d-m-Y') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Masa Berlaku :</th>
                        <td class="col-9">
                            {{ \Carbon\Carbon::parse($dataPelatihan->pelatihan->tanggal_akhir)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Lokasi :</th>
                        <td class="col-9">{{ $dataPelatihan->pelatihan->lokasi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Periode :</th>
                        <td class="col-9">{{ $dataPelatihan->pelatihan->periode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Status :</th>
                        <td class="col-9">{{ $dataPelatihan->status }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3"> Draft Surat Tugas : </th>
                        <td>
                            @if ($dataPelatihan->pelatihan->keterangan === 'sudah divalidasi')
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="window.location.href='{{ route('pelatihan.export_ajax', $dataPelatihan->pelatihan->pelatihan_id) }}'">
                                    Buat Draft Surat Tugas
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-secondary" disabled>
                                    Menunggu Validasi
                                </button>


                                <!-- Formulir Upload File -->
                                {{-- <form action="{{ route('pelatihan.upload', $dataPelatihan->pelatihan_id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf --}}
                                    <!-- Form fields akan ada di sini -->

                                    {{-- <div class="form-group">
                                        <label for="file">Upload Surat:</label>
                                        <input type="file" class="form-control" id="file" name="file" required> --}}
                                        {{-- <a href="{{ route('pelatihan.upload', $dataPelatihan->pelatihan_id) }}"> --}}

                                        <!-- Validasi error -->
                                        {{-- @error('file')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Kirim</button>
                                            <button type="button" data-dismiss="modal"
                                                class="btn btn-secondary">Batal</button>
                                        </div>
                                    </div> --}}
                                    <form action="{{ route('pelatihan.upload') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="pelatihan_id" value="{{ $pelatihan->id }}">
                                        <input type="hidden" name="dosen_id" value="{{ auth()->user()->dosen_id }}">
                                        
                                        <div class="form-group">
                                            <label for="file">Upload Surat Tugas</label>
                                            <input type="file" class="form-control" id="file" name="file" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Upload Surat Tugas</button>
                                    </form>
                                {{-- </form> --}}
                            @endif
                        </td>
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
                            <td class="text-center">{{ $dataPelatihan->sertifikat }}</td>
                            <td class="text-center">
                                <a href="{{ route('pelatihan.downloadSertifikat', $dataPelatihan->pelatihan_id) }}"
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
@endempty
