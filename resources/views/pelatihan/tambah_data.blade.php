@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Form Tambah Pelatihan -->
            <form action="{{ route('pelatihan.dosen.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_pelatihan" class="form-label">Nama Pelatihan</label>
                    <input type="text" class="form-control" id="nama_pelatihan" name="nama_pelatihan" required>
                </div>
                <div class="mb-3">
                    <label for="level_id" class="form-label">Level Pelatihan</label>
                    <select class="form-control" id="level_id" name="level_id" required>
                        <option value="">Pilih Level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->level_id }}">{{ $level->level_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bidang_id" class="form-label">Bidang</label>
                    <select class="form-control" id="bidang_id" name="bidang_id" required>
                        <option value="">Pilih Bidang</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->bidang_id }}">{{ $bidang->bidang_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="mk_id" class="form-label">Mata Kuliah</label>
                    <select class="form-control" id="mk_id" name="mk_id" required>
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($matkuls as $matkul)
                            <option value="{{ $matkul->mk_id }}">{{ $matkul->mk_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="vendor_id" class="form-label">Vendor</label>
                    <select class="form-control" id="vendor_id" name="vendor_id" required>
                        <option value="">Pilih Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required>
                </div>    
                <div class="mb-3">
                    <label for="kuota" class="form-label">Kuota</label>
                    <input type="number" class="form-control" id="kuota" name="kuota" required>
                </div>
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                </div>
                <div class="mb-3">
                    <label for="biaya" class="form-label">Biaya</label>
                    <input type="number" class="form-control" id="biaya" name="biaya" required>
                </div> 
                <div class="mb-3">
                    <label for="periode" class="form-label">Periode</label>
                    <input type="text" class="form-control" id="periode" name="periode" required>
                </div>           
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('pelatihan.index') }}" class="btn btn-secondary">Kembali</a>
            </form>            
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#pelatihanForm').submit(function(e) {
            e.preventDefault(); // Mencegah form untuk reload page

            // Menampilkan alert error dan menyembunyikan yang sebelumnya
            $('#errorMessage').hide();
            $('#successMessage').hide();

            // Mengambil data dari form
            var formData = $(this).serialize(); // Mengambil semua data form sebagai string URL

            // Validasi untuk memastikan bidang_id dan jenis_id terisi
            if ($('#bidang_id').val() == "" || $('#jenis_id').val() == "") {
                alert("Bidang dan Jenis Pelatihan harus dipilih!");
                return;
            }

            // Mengirim data ke server menggunakan AJAX
            $.ajax({
                url: "{{ route('pelatihan.dosen.store') }}", // Ganti dengan URL untuk menyimpan data
                type: "POST",
                data: formData,
                success: function(response) {
                    // Jika berhasil, tampilkan success message
                    $('#successMessage').show();
                    // Reset form setelah berhasil
                    $('#pelatihanForm')[0].reset();
                },
                error: function(xhr, status, error) {
                    // Jika terjadi kesalahan, tampilkan error message
                    $('#errorMessage').show();
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endpush