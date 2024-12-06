@extends('layouts.template')

@section('title', 'Daftar Dosen')

@section('content_header')
    <h1>{{ $breadcrumb->title }}</h1>
    <small>{{ $breadcrumb->subtitle }}</small>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Bidang dan Dosen</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Nama Bidang</th>
                        <th>Daftar Dosen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bidangs as $bidang)
                        <tr>
                            <td>{{ $bidang->nama }}</td>
                            <td>
                                @if ($bidang->dosen->isNotEmpty())
                                    <ul>
                                        @foreach ($bidang->dosen as $dosen)
                                            <li>{{ $dosen->nama }} ({{ $dosen->nip }})</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Tidak ada dosen di bidang ini.</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada data bidang atau dosen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }
    .rounded-circle {
        border-radius: 50%;
    }
    .card-header {
        background-color: #f4f6f9;
    }
    .btn-sm {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }
    .badge {
        font-size: 0.85rem;
    }
</style>
@stop
