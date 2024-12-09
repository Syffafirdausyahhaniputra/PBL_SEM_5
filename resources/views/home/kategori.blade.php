<div class="judul" id="dosen">Daftar Dosen</div>

<div class="container-kat">
    <div class="row">
        @foreach($dosenList as $dosen)
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card">
                <img src="{{ $dosen->user->avatar ? asset('avatars/' . $dosen->user->avatar) : asset('avatars/user.jpg') }}" 
                     alt="{{ $dosen->user->nama }}" 
                     class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">{{ $dosen->user->nama }}</h5>
                    <p class="card-text">
                        Bidang: 
                        @if($dosen->dosenBidang && $dosen->dosenBidang->bidang)
                            {{ $dosen->dosenBidang->bidang->bidang_nama }}
                        @else
                            Tidak ada bidang
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>    
</div>
