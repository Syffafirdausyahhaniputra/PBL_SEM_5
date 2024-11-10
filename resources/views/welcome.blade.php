@extends('layouts.template')

@section('content')
    <!-- Data and Profile Section -->
    <div class="row">
        <!-- Chart Card -->
        <div class="col-md-8">
            <div class="card p-4">
                <h5>Data Sertifikasi dan Pelatihan Dosen</h5>
                <canvas id="sertifikasiPelatihanChart"></canvas>
                <div class="row mt-3">
                    <div class="col-6">
                        <p><strong>{{ $rataRataSertifikasiPerPeriode }}</strong><br>Rata-rata Sertifikasi per-periode</p>
                    </div>
                    <div class="col-6">
                        <p><strong>{{ $rataRataPelatihanPerPeriode }}</strong><br>Rata-rata Pelatihan per-periode</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="col-md-4">
            <a href="{{ url('/profile') }}" class="text-decoration-none">
                <div class="card p-4 text-center d-flex flex-column align-items-center" style="color: black;">
                    <h2><b>Profile</b></h2>
                    <img src="{{ Auth::user()->avatar ? asset('storage/avatar/' . Auth::user()->avatar) : asset('img/user.png') }}"
                        class="rounded-circle mb-3" width="100" height="100" alt="User Avatar">
                    <h5>{{ Auth::user()->nama }}</h5>
                </div>
            </a>
        </div>
    </div>

    <!-- Bidang Section -->
    <h2 class="mt-5">Bidang</h2>
    <div class="d-flex justify-content-start flex-wrap">
        @foreach ($bidang as $b)
            <div class="card bidang-card m-2">
                <img src="{{ asset('img/IT.png') }}" class="card-img-top" alt="{{ $b->bidang_nama }}">
                <div class="card-body">
                    <h5 class="card-title text-center">{{ $b->bidang_nama }}</h5>
                </div>
            </div>
        @endforeach
        <a href="#" class="btn btn-warning m-2 align-self-center">See More</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var labels = @json($labels);
        var sertifikasiData = @json($sertifikasiData);
        var pelatihanData = @json($pelatihanData);

        var ctx = document.getElementById('sertifikasiPelatihanChart').getContext('2d');
        var sertifikasiPelatihanChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Sertifikasi',
                        data: sertifikasiData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pelatihan',
                        data: pelatihanData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
