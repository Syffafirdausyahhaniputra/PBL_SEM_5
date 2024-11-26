<div class="card">
    <div class="card-header">
        <h5>{{ $nama }}</h5>
    </div>
    <div class="card-body">
        <p><strong>Bidang:</strong> {{ $bidang }}</p>
        
        @if (!empty($matkul))
            <p><strong>Mata Kuliah:</strong></p>
            <ul>
                @foreach ($matkul as $mk)
                    <li>{{ $mk->nama }}</li>
                @endforeach
            </ul>
        @endif
        
        <p><strong>Vendor:</strong> {{ $vendor }}</p>

        {{-- Jika data merupakan sertifikasi, tampilkan jenis --}}
        @if (isset($jenis))
            <p><strong>Jenis:</strong> {{ $jenis }}</p>
        @endif

        {{-- Jika data merupakan pelatihan, tampilkan level --}}
        @if (isset($level))
            <p><strong>Level:</strong> {{ $level }}</p>
        @endif
        
        <p><strong>Tanggal Acara:</strong> {{ $tanggal_acara }}</p>
        @if (isset($berlaku_hingga))
            <p><strong>Level:</strong> {{ $berlaku_hingga }}</p>
        @endif
        <p><strong>Periode:</strong> {{ $periode }}</p>
    </div>
    <div class="card-footer">
        <a href="{{ url('notifikasi') }}" class="btn btn-secondary">Back</a>


    </div>
</div>
