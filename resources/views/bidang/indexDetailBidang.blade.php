@extends('layouts.template')

@section('content_header')
    <h1>Detail Bidang</h1>
@endsection

@section('content')
    <!-- Bidang Section -->
    <div class="bidang-container mt-4">
        @forelse ($bidangs as $b)
            <div class="bidang-card">
                <!-- Menampilkan gambar bidang (fallback ke default jika tidak ada gambar) -->
                <img src="{{ $b->gambar ? asset('storage/' . $b->gambar) : asset('img/default.png') }}" 
                     alt="{{ $b->bidang_nama }}">
                <h5 class="card-title text-center">{{ $b->bidang_nama }}</h5>
            </div>
        @empty
            <p class="text-center text-white">Tidak ada data bidang untuk ditampilkan.</p>
        @endforelse
    </div>

    <!-- Tombol Kembali -->
    <div class="text-center mt-4">
        <a href="{{ route('index') }}" class="see-more-btn">Kembali ke Dashboard</a>
    </div>
@endsection

@section('css')
    <style>
        /* Container untuk kartu bidang */
        .bidang-container {
            background-color: #2C65C8;
            padding: 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* Kartu bidang */
        .bidang-card {
            background-color: white;
            border: none;
            width: 150px;
            height: 150px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            transition: transform 0.3s ease;
        }

        .bidang-card:hover {
            transform: scale(1.05);
        }

        /* Gambar bidang */
        .bidang-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        /* Tombol "Kembali" */
        .see-more-btn {
            background-color: #FFB400;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .see-more-btn:hover {
            background-color: #e0a600;
            color: white;
        }

        /* Responsivitas untuk layar kecil */
        @media (max-width: 576px) {
            .bidang-card {
                width: 120px;
                height: 120px;
            }

            .bidang-card img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
@endsection
