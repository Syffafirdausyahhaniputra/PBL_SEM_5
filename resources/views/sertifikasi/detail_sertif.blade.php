@extends('layouts.template')

@section('title', $sertifikasi->nama_sertif)

@section('content_header')
    <h1>Detail Sertifikasi</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">{{ $sertifikasi->nama_sertif }}</h3>
            </div>
            <div class="card-body">
                <h6 class="text-muted">{{ $sertifikasi->bidang->bidang_nama ?? 'N/A' }}</h6>
                <hr>
                <p><strong>Jenis Sertifikasi:</strong> {{ $sertifikasi->jenis->jenis_nama ?? 'N/A' }}</p>
                <p><strong>Mata Kuliah Terkait:</strong> {{ $sertifikasi->matkul->mk_nama ?? 'N/A' }}</p>
                <p><strong>Vendor:</strong> {{ $sertifikasi->vendor->vendor_nama ?? 'N/A' }}</p>
                <p><strong>Tanggal:</strong> {{ $sertifikasi->tanggal ?? 'N/A' }}</p>
                <p><strong>Masa Berlaku:</strong> {{ $sertifikasi->masa_berlaku ?? 'N/A' }}</p>
                <p><strong>Tanggal Akhir:</strong> {{ $sertifikasi->tanggal_akhir ?? 'N/A' }}</p>
                <p><strong>Biaya:</strong> Rp{{ number_format($sertifikasi->biaya, 0, ',', '.') ?? 'N/A' }}</p>
                <p><strong>Periode:</strong> {{ $sertifikasi->periode ?? 'N/A' }}</p>
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
    <script>console.log('Detail Sertifikasi Page Loaded');</script>
@stop
