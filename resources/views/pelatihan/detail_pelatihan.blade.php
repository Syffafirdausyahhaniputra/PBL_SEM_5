@extends('layouts.template')

@section('title', $pelatihan->nama_pelatihan)

@section('content_header')
    <h1>Detail Pelatihan</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">{{ $pelatihan->nama_pelatihan }}</h3>
            </div>
            <div class="card-body">
                <h6 class="text-muted">{{ $pelatihan->bidang->bidang_nama ?? 'N/A' }}</h6>
                <hr>
                <p><strong>Level Pelatihan:</strong> {{ $pelatihan->level->level_nama ?? 'N/A' }}</p>
                <p><strong>Mata Kuliah Terkait:</strong> {{ $pelatihan->matkul->mk_nama ?? 'N/A' }}</p>
                <p><strong>Vendor:</strong> {{ $pelatihan->vendor->vendor_nama ?? 'N/A' }}</p>
                <p><strong>Tanggal Mulai:</strong> {{ $pelatihan->tanggal ?? 'N/A' }}</p>
                <p><strong>Tanggal Akhir:</strong> {{ $pelatihan->tanggal_akhir ?? 'N/A' }}</p>
                <p><strong>Kuota:</strong> {{ $pelatihan->kuota ?? 'N/A' }}</p>
                <p><strong>Lokasi:</strong> {{ $pelatihan->lokasi ?? 'N/A' }}</p>
                <p><strong>Biaya:</strong> Rp{{ number_format($pelatihan->biaya, 0, ',', '.') ?? 'N/A' }}</p>
                <p><strong>Periode:</strong> {{ $pelatihan->periode ?? 'N/A' }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('welcome2.index2') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .card-title {
            font-size: 24px;
        }
        .text-muted {
            font-size: 14px;
        }
    </style>
@stop

@section('js')
    <script>console.log('Detail Pelatihan Page Loaded');</script>
@stop
