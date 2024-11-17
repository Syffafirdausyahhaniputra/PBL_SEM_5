@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="text-center me-3">
                                    <form id="avatar-form" enctype="multipart/form-data">
                                        <label for="avatar" style="cursor: pointer;">
                                            <img id="profile-pic"
                                                src="{{ $user->avatar ? asset('storage/avatar/' . $user->avatar) . '?v=' . time() : asset('img/user.png') }}"
                                                class="rounded-circle" width="100" height="100" alt="Profile Picture">
                                        </label>
                                        <input type="file" id="avatar" name="avatar" class="d-none"
                                            onchange="previewAndUploadImage(event)" disabled accept="image/*">
                                    </form>
                                </div>
                                <div>
                                    <h4 class="mb-0" id="display-nama">{{ $user->nama }}</h4>
                                    <p class="text-muted" id="display-username">{{ '@' . $user->username }}</p>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary" id="edit-btn"
                                    onclick="toggleEdit()">Edit</button>
                            </div>
                        </div>

                        <form id="profile-form" action="{{ route('profile.update', $user->user_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="mt-4">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input id="username" name="username" type="text" class="form-control"
                                        value="{{ $user->username }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input id="nama" name="nama" type="text" class="form-control"
                                        value="{{ $user->nama }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input id="nip" name="nip" type="text" class="form-control"
                                        value="{{ $user->nip }}" readonly>
                                </div>
                                <div class="mb-3 d-none" id="old-password-group">
                                    <label for="old_password" class="form-label">Password Lama</label>
                                    <input id="old_password" name="old_password" type="password" class="form-control">
                                </div>
                                <div class="mb-3 d-none" id="new-password-group">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input id="password" name="password" type="password" class="form-control">
                                </div>
                            </div>
                            <div class="mt-4 d-none" id="save-cancel-group">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <script src="path-to-sweetalert"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection
