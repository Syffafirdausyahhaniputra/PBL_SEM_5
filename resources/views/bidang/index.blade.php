@extends('layouts.template')

@section('content')
    <div class="bidang-container">
        @foreach ($bidang as $b)
            <div class="bidang-card">
                <img src="{{ asset('img/IT.png') }}" alt="{{ $b->bidang_nama }}">
                <h5 class="card-title text-center">{{ $b->bidang_nama }}</h5>
            </div>
        @endforeach
    </div>

    <style>
        .bidang-container {
            background-color: #2C65C8;
            padding: 20px;
            border-radius: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .bidang-card {
            background-color: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 150px;
            height: 150px;
        }

        .bidang-card img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
    </style>
@endsection
