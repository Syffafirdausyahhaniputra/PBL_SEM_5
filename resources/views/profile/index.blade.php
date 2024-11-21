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
                                <form id="avatar-form" enctype="multipart/form-data">
                                    <label for="avatar" style="cursor: pointer;">
                                        <img id="profile-pic" src="{{ $user->avatar ? asset('storage/avatar/' . $user->avatar) . '?v=' . time() : asset('img/user.png') }}" 
                                             class="rounded-circle" width="100" height="100" alt="Profile Picture">
                                    </label>
                                    <input type="file" id="avatar" name="avatar" class="d-none" onchange="previewAndUploadImage(event)" disabled accept="image/*">
                                </form>
                            </div>
                            <div>
                                <h4 class="mb-0" id="display-nama">{{ $user->nama }}</h4>
                                <p class="text-muted" id="display-username">{{'@' . $user->username }}</p>
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

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    {{ session('info') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<script>
    let originalImageSrc = "{{ $user->avatar ? asset('storage/avatar/' . $user->avatar) : asset('img/user.png') }}";
    let hasNewImage = false;

    function toggleEdit() {
        const isReadOnly = document.getElementById('username').readOnly;
        const editBtn = document.getElementById('edit-btn');
        const saveCancelGroup = document.getElementById('save-cancel-group');
        const oldPasswordGroup = document.getElementById('old-password-group');
        const newPasswordGroup = document.getElementById('new-password-group');
        const avatarInput = document.getElementById('avatar');

        // Toggle read-only state for all fields
        document.getElementById('username').readOnly = !isReadOnly;
        document.getElementById('nama').readOnly = !isReadOnly;
        document.getElementById('nip').readOnly = !isReadOnly;
        avatarInput.disabled = !isReadOnly;

        // Toggle visibility of password fields and buttons
        oldPasswordGroup.classList.toggle('d-none');
        newPasswordGroup.classList.toggle('d-none');
        saveCancelGroup.classList.toggle('d-none');

        // Update button text
        editBtn.innerText = isReadOnly ? 'Batal' : 'Edit';

        // If cancelling, reset form and preview
        if (!isReadOnly) {
            document.getElementById('profile-form').reset();
            if (!hasNewImage) {
                document.getElementById('profile-pic').src = originalImageSrc;
            }
        }
    }

    // Handle form submission with AJAX
    document.getElementById('profile-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const avatarFile = document.getElementById('avatar').files[0];
        if (avatarFile) {
            formData.append('avatar', avatarFile);
        }

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // Refresh halaman
                });
            } else {
                // Handle validation errors
                Object.keys(data.errors || {}).forEach(key => {
                    const input = document.getElementById(key);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.textContent = data.errors[key][0];
                        input.parentNode.appendChild(feedback);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat memperbarui profil.',
                confirmButtonText: 'Tutup'
            });
        });
    });

    function previewAndUploadImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-pic').src = e.target.result;
                hasNewImage = true;
            }
            reader.readAsDataURL(file);
        }
    }

    // Clear validation errors when input changes
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const feedback = this.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.remove();
            }
        });
    });
</script>
@endsection