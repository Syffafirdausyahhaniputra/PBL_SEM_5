@extends('layouts.template')

@section('title', $bidang->bidang_nama)

@section('content')
<div class="header">
    <h1>{{ $bidang->bidang_nama }}</h1>
    <small>List Dosen Bidang {{ $bidang->bidang_nama }}</small>
    <img src="{{ asset('images/user-placeholder.jpg') }}" alt="User">
</div>

<div class="card-list">
    @if ($bidang->dosenBidang->isNotEmpty())
        @foreach ($bidang->dosenBidang as $dosenBidang)
        <div class="card">
            <div>
                <img src="{{ asset('images/dosen-placeholder.jpg') }}" alt="Dosen">
            </div>
            <div>
                <p class="card-title">{{ $dosenBidang->dosen->user->name }}</p>
                <p class="card-subtitle">{{ $dosenBidang->dosen->user->email }}</p>
            </div>
        </div>
        @endforeach
    @else
        <p>Tidak ada dosen untuk bidang ini.</p>
    @endif
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    /* Sidebar */
    .sidebar {
        background-color: #003366;
        color: white;
    }

    .sidebar a {
        color: white;
        font-size: 16px;
        font-weight: bold;
    }

    .sidebar a:hover {
        background-color: #004080;
        color: white;
    }

    .sidebar .active {
        background-color: #cb8587;
        color: white;
    }

    /* Header */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #f4f6f9;
        border-bottom: 1px solid #ddd;
    }

    .header h1 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }

    .header img {
        border-radius: 50%;
        width: 40px;
        height: 40px;
    }

    /* Card List */
    .card-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }

    .card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        max-width: 400px;
        padding: 15px 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        transition: 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .card img {
        border-radius: 50%;
        width: 50px;
        height: 50px;
        margin-right: 20px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .card-subtitle {
        font-size: 14px;
        color: #888;
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        .card-list {
            flex-direction: column;
        }
    }

@endsection
