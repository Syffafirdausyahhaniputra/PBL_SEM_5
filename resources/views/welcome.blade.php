@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Halo {{ Auth::user()->nama }}!</h3> <!-- Menampilkan nama user -->
        <div class="card tools"></div>
    </div>
    <!-- Menambahkan gambar setelah card-body -->
    <div class="card-footer">
        <img src="{{ asset('img/me.png') }}" alt="Gambar Profil" style="display: block; margin: auto; width: 50%; height: auto;">
    </div>
</div>

@endsection