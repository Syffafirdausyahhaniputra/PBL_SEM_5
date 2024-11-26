@extends('layouts.template')

@section('content')
<div class="container">
    <form id="sertifikasiForm">
        @csrf
        <div class="mb-3">
            <label for="nama_sertif" class="form-label">Nama Sertifikasi</label>
            <input type="text" class="form-control" id="nama_sertif" name="nama_sertif" required>
        </div>
        <div class="mb-3">
            <label for="bidang_id" class="form-label">Bidang</label>
            <select class="form-control" id="bidang_id" name="bidang_id" required>
                <option value="">Pilih Bidang</option>
                @foreach($bidangs as $bidang)
                    <option value="{{ $bidang->id }}">{{ $bidang->bidang_nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="jenis_id" class="form-label">Jenis Sertifikasi</label>
            <select class="form-control" id="jenis_id" name="jenis_id" required>
                <option value="">Pilih Jenis Sertifikasi</option>
                @foreach($jenis as $j)
                    <option value="{{ $j->id }}">{{ $j->jenis_nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="masa_berlaku" class="form-label">Masa Berlaku</label>
            <input type="date" class="form-control" id="masa_berlaku" name="masa_berlaku">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('sertifikasi.dosen.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

    <!-- Alert untuk sukses -->
    <div id="successMessage" class="alert alert-success mt-3" style="display: none;">
        Sertifikasi berhasil disimpan!
    </div>

    <!-- Alert untuk error -->
    <div id="errorMessage" class="alert alert-danger mt-3" style="display: none;">
        Terjadi kesalahan! Periksa kembali data yang Anda masukkan.
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#sertifikasiForm').submit(function(e) {
            e.preventDefault(); // Mencegah form untuk reload page

            // Menampilkan alert error dan menyembunyikan yang sebelumnya
            $('#errorMessage').hide();
            $('#successMessage').hide();

            // Mengambil data dari form
            var formData = $(this).serialize(); // Mengambil semua data form sebagai string URL

            // Validasi untuk memastikan bidang_id dan jenis_id terisi
            if ($('#bidang_id').val() == "" || $('#jenis_id').val() == "") {
                alert("Bidang dan Jenis Sertifikasi harus dipilih!");
                return;
            }

            // Mengirim data ke server menggunakan AJAX
            $.ajax({
                url: "{{ route('sertifikasi.dosen.store') }}", // Ganti dengan URL untuk menyimpan data
                type: "POST",
                data: formData,
                success: function(response) {
                    // Jika berhasil, tampilkan success message
                    $('#successMessage').show();
                    // Reset form setelah berhasil
                    $('#sertifikasiForm')[0].reset();
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