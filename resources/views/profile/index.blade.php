@extends('layouts.template')

@section('content')
<div class="card">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Row untuk Avatar dan Tombol Edit -->
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Kolom untuk Gambar Profil dan Informasi Nama -->
                        <div class="d-flex align-items-center">
                            <div class="text-center me-3">
                                <label for="avatar" style="cursor: pointer;">
                                    <img id="profile-pic" src="{{ $user->avatar ? asset('storage/avatar/' . $user->avatar) : asset('img/user.png') }}" class="rounded-circle" width="100" height="100" alt="Profile Picture">
                                </label>
                                <input type="file" id="avatar" name="avatar" class="d-none" onchange="previewImage(event)" disabled>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $user->nama }}</h4>
                                <p class="text-muted">{{ '@' . $user->username }}</p>
                            </div>
                        </div>

                        <!-- Tombol Edit/Batal -->
                        <div>
                            <button type="button" class="btn btn-primary" id="edit-btn" onclick="toggleEdit()">Edit</button>
                        </div>
                    </div>

                    <!-- Informasi Profil dengan tampilan mirip input form -->
                    <form id="profile-form" action="{{ route('profile.update', $user->user_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mt-4">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input id="username" name="username" type="text" class="form-control" value="{{ $user->username }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input id="nama" name="nama" type="text" class="form-control" value="{{ $user->nama }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input id="nip" name="nip" type="text" class="form-control" value="{{ $user->nip }}" readonly>
                            </div>

                            <!-- Form Password Lama dan Baru (tersembunyi kecuali dalam edit mode) -->
                            <div class="mb-3 d-none" id="old-password-group">
                                <label for="old_password" class="form-label">Password Lama</label>
                                <input id="old_password" name="old_password" type="password" class="form-control">
                            </div>

                            <div class="mb-3 d-none" id="new-password-group">
                                <label for="password" class="form-label">Password Baru</label>
                                <input id="password" name="password" type="password" class="form-control">
                            </div>
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="mt-4 d-none" id="save-cancel-group">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <button type="button" class="btn btn-secondary" onclick="toggleEdit()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleEdit() {
        const isReadOnly = document.getElementById('username').readOnly;

        // Toggle read-only state for all fields
        document.getElementById('username').readOnly = !isReadOnly;
        document.getElementById('nama').readOnly = !isReadOnly;
        document.getElementById('nip').readOnly = !isReadOnly;
        document.getElementById('avatar').disabled = !isReadOnly;

        // Toggle visibility for password fields and save-cancel group
        document.getElementById('old-password-group').classList.toggle('d-none');
        document.getElementById('new-password-group').classList.toggle('d-none');
        document.getElementById('save-cancel-group').classList.toggle('d-none');

        // Change Edit button to Cancel and vice versa
        document.getElementById('edit-btn').innerText = isReadOnly ? 'Batal' : 'Edit';
    }

    function previewImage(event) {
        const output = document.getElementById('profile-pic');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // Free up memory
        }
    }
</script>
@endsection
