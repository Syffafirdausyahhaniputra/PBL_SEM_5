@extends('layouts.template') <!-- Menggunakan template layout AdminLTE -->
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Input Data Pelatihan</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pelatihan.store') }}" class="form-horizontal">
                @csrf
                <!-- Nama Pelatihan -->
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nama Pelatihan</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="nama_pelatihan" name="nama_pelatihan"
                            value="{{ old('nama_pelatihan') }}" required>
                        @error('nama_pelatihan')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Nomor Sertifikat -->
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nomor Sertifikat</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="nomor_sertifikat" name="nomor_sertifikat"
                            value="{{ old('nomor_sertifikat') }}">
                        @error('nomor_sertifikat')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Jenis -->
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Jenis</label>
                    <div class="col-10">
                        <select class="form-control" id="level_id" name="level_id" required>
                            <option value="">Pilih Jenis</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->level_id }}" {{ old('level_id') == $level->level_id ? 'selected' : '' }}>
                                    {{ $level->nama_level }}
                                </option>
                            @endforeach
                        </select>
                        @error('level_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Tanggal</label>
                    <div class="col-10">
                        <input type="date" class="form-control" id="tanggal" name="tanggal"
                            value="{{ old('tanggal') }}" required>
                        @error('tanggal')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Vendor -->
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Vendor</label>
                    <div class="col-10">
                        <select class="form-control" id="vendor_id" name="vendor_id" required>
                            <option value="">Pilih Vendor</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->vendor_id }}" {{ old('vendor_id') == $vendor->vendor_id ? 'selected' : '' }}>
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Masa Berlaku -->
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Masa Berlaku</label>
                    <div class="col-10">
                        <input type="date" class="form-control" id="masa_berlaku" name="masa_berlaku"
                            value="{{ old('masa_berlaku') }}" required>
                        @error('masa_berlaku')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Bidang -->
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Bidang</label>
                    <div class="col-10">
                        <select class="form-control" id="bidang_id" name="bidang_id" required>
                            <option value="">Pilih Bidang</option>
                            @foreach ($bidangs as $bidang)
                                <option value="{{ $bidang->bidang_id }}" {{ old('bidang_id') == $bidang->bidang_id ? 'selected' : '' }}>
                                    {{ $bidang->nama_bidang }}
                                </option>
                            @endforeach
                        </select>
                        @error('bidang_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Mata Kuliah -->
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Mata Kuliah</label>
                    <div class="col-10">
                        <select class="form-control" id="mk_id" name="mk_id" required>
                            <option value="">Pilih Mata Kuliah</option>
                            @foreach ($matkuls as $matkul)
                                <option value="{{ $matkul->mk_id }}" {{ old('mk_id') == $matkul->mk_id ? 'selected' : '' }}>
                                    {{ $matkul->nama_mk }}
                                </option>
                            @endforeach
                        </select>
                        @error('mk_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Periode -->
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Periode</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="periode" name="periode"
                            value="{{ old('periode') }}" required>
                        @error('periode')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="form-group row">
                    <div class="col-10 offset-2">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ route('pelatihan.index') }}">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
<!-- Tambahkan custom CSS jika diperlukan -->
@endpush

@push('js')
<!-- Tambahkan custom JavaScript jika diperlukan -->
@endpush
