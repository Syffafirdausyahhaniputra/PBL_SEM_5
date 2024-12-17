@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Row untuk Avatar dan Tombol Edit -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="text-center me-3">
                                    <form id="avatar-form" enctype="multipart/form-data">
                                        <label for="avatar" style="cursor: pointer;">
                                            <img id="profile-pic"
                                                src="{{ $user->avatar ? asset('avatars/' . $user->avatar) : asset('img/user.png') }}"
                                                class="rounded-circle img-fluid" width="120" height="120"
                                                alt="Profile Picture">
                                        </label>
                                        <input type="file" id="avatar" name="avatar" class="d-none"
                                            onchange="previewAndUploadImage(event)" accept="image/*">
                                    </form>
                                </div>
                                <div class="col">
                                    <h4 class="mb-1" id="display-nama">{{ $user->nama }}</h4>
                                    <p class="text-muted mb-0" id="display-email">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="col-auto text-end">
                                <button type="button" class="btn btn-primary" id="edit-btn"
                                    onclick="toggleEdit()">Edit</button>
                            </div>
                        </div>

                        <form id="profile-form" action="{{ route('profileDosen.update', $user->user_id) }}" method="POST"
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
                                    <label for="nip" class="form-label">NIP/NIDN</label>
                                    <input id="nip" name="nip" type="text" class="form-control"
                                        value="{{ $user->nip }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" name="email" type="text" class="form-control"
                                        value="{{ $user->email }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="jabatan_id" class="form-label">Jabatan</label>
                                    <select id="jabatan_id" name="jabatan_id" class="form-control" disabled>
                                        <option value="">- Pilih Jabatan -</option>
                                        @foreach ($jabatan as $j)
                                            <option value="{{ $j->jabatan_id }}"
                                                {{ $user->dosen->jabatan_id == $j->jabatan_id ? 'selected' : '' }}>
                                                {{ $j->jabatan_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="golongan_id" class="form-label">Golongan</label>
                                    <select id="golongan_id" name="golongan_id" class="form-control" disabled>
                                        <option value="">- Pilih Golongan -</option>
                                        @foreach ($golongan as $g)
                                            <option value="{{ $g->golongan_id }}"
                                                {{ $user->dosen->golongan_id == $g->golongan_id ? 'selected' : '' }}>
                                                {{ $g->golongan_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="pangkat_id" class="form-label">Pangkat</label>
                                    <select id="pangkat_id" name="pangkat_id" class="form-control" disabled>
                                        <option value="">- Pilih Pangkat -</option>
                                        @foreach ($pangkat as $p)
                                            <option value="{{ $p->pangkat_id }}"
                                                {{ $user->dosen->pangkat_id == $p->pangkat_id ? 'selected' : '' }}>
                                                {{ $p->pangkat_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- In the Bidang section -->
                                <div class="form-group">
                                    <label>Bidang</label>
                                    <div id="bidang-container">
                                        @foreach ($bidangList as $item)
                                            <div class="bidang-item d-flex align-items-center mb-3">
                                                <select name="bidang_id[]" class="form-control bidang-select me-2" disabled>
                                                    <option value="">- Pilih Bidang -</option>
                                                    @foreach ($bidang as $b)
                                                        <option value="{{ $b->bidang_id }}"
                                                            @if ($item->bidang_id == $b->bidang_id) selected @endif>
                                                            {{ $b->bidang_nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger remove-bidang edit-mode-only"
                                                    onclick="removeBidang(this)" style="display: none;">Hapus</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" id="add-bidang"
                                        class="btn btn-sm btn-primary mt-2 edit-mode-only" onclick="addBidang()"
                                        style="display: none;">Tambah Bidang</button>
                                </div>

                                <!-- In the Mata Kuliah section -->
                                <div class="form-group">
                                    <label>Mata Kuliah</label>
                                    <div id="matkul-container">
                                        @foreach ($matkulList as $item)
                                            <div class="matkul-item d-flex align-items-center mb-3">
                                                <select name="mk_id[]" class="form-control matkul-select me-2" disabled>
                                                    <option value="">- Pilih Mata Kuliah -</option>
                                                    @foreach ($matkul as $m)
                                                        <option value="{{ $m->mk_id }}"
                                                            @if ($item->mk_id == $m->mk_id) selected @endif>
                                                            {{ $m->mk_nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger remove-matkul edit-mode-only"
                                                    onclick="removeMatkul(this)" style="display: none;">Hapus</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" id="add-matkul"
                                        class="btn btn-sm btn-primary mt-2 edit-mode-only" onclick="addMatkul()"
                                        style="display: none;">Tambah Mata Kuliah</button>
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

    <script>
        let originalImageSrc = "{{ $user->avatar ? asset('avatars/' . $user->avatar) : asset('img/user.png') }}";
        let hasNewImage = false;

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
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update display data immediately
                        updateDisplayData(data);

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(() => {
                            toggleEdit(); // Exit edit mode
                        });
                    } else {
                        handleValidationErrors(data.errors);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.',
                        confirmButtonText: 'Tutup'
                    });
                });
        });

        function updateDisplayData(data) {
            if (data.user.avatar) {
                document.getElementById('profile-pic').src = '/avatars/' + data.user.avatar;
                document.getElementById('display-nama').textContent = data.user.nama;
                document.getElementById('display-email').textContent = data.user.email;
            }
        }

        function handleValidationErrors(errors) {
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.remove();
            });

            // Show new errors
            Object.keys(errors || {}).forEach(key => {
                const input = document.getElementById(key);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = errors[key][0];
                    input.parentNode.appendChild(feedback);
                }
            });
        }

        function toggleEdit() {
            const editBtn = document.getElementById('edit-btn');
            const isReadOnly = document.getElementById('username').readOnly;
            const saveCancelGroup = document.getElementById('save-cancel-group');
            const oldPasswordGroup = document.getElementById('old-password-group');
            const newPasswordGroup = document.getElementById('new-password-group');
            const avatarInput = document.getElementById('avatar');

            // Toggle disabled state for inputs
            document.querySelectorAll('select, input:not([type="file"])').forEach(input => {
                input.disabled = !isReadOnly;
            });

            // Toggle readonly state
            document.getElementById('username').readOnly = !isReadOnly;
            document.getElementById('email').readOnly = !isReadOnly;
            avatarInput.disabled = !isReadOnly;

            // Toggle edit-only elements visibility
            document.querySelectorAll('.edit-mode-only').forEach(el => {
                el.style.display = isReadOnly ? 'inline-block' : 'none';
            });

            // Toggle password and save/cancel groups
            oldPasswordGroup.classList.toggle('d-none');
            newPasswordGroup.classList.toggle('d-none');
            saveCancelGroup.classList.toggle('d-none');

            // Update button text
            editBtn.innerText = isReadOnly ? 'Batal' : 'Edit';

            // If cancelling edit, reset form and preview
            if (isReadOnly) {
                resetForm();
            }
        }

        function addBidang() {
            const container = document.getElementById('bidang-container');
            const template = `<div class="bidang-item d-flex align-items-center mb-3">
                <select name="bidang_id[]" class="form-control bidang-select me-2">
                    <option value="">- Pilih Bidang -</option>
                    @foreach ($bidang as $b)
                    <option value="{{ $b->bidang_id }}">{{ $b->bidang_nama }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-sm btn-danger remove-bidang edit-mode-only" onclick="removeBidang(this)">Hapus</button>
            </div>`;
            container.insertAdjacentHTML('beforeend', template);
        }

        function removeBidang(button) {
            button.closest('.bidang-item').remove();
        }

        function addMatkul() {
            const container = document.getElementById('matkul-container');
            const template = `<div class="matkul-item d-flex align-items-center mb-3">
                <select name="mk_id[]" class="form-control matkul-select me-2">
                    <option value="">- Pilih Mata Kuliah -</option>
                    @foreach ($matkul as $m)
                    <option value="{{ $m->mk_id }}">{{ $m->mk_nama }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-sm btn-danger remove-matkul edit-mode-only" onclick="removeMatkul(this)">Hapus</button>
            </div>`;
            container.insertAdjacentHTML('beforeend', template);
        }

        function removeMatkul(button) {
            button.closest('.matkul-item').remove();
        }

        function removeMatkul(button) {
            const isEditMode = document.getElementById('edit-btn').innerText === 'Batal';

            if (!isEditMode) return; // Prevent removing if not in edit mode

            // Prevent removing the last mata kuliah
            const matkulItems = document.querySelectorAll('.matkul-item');
            if (matkulItems.length > 1) {
                button.closest('.matkul-item').remove();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa Menghapus',
                    text: 'Minimal harus memiliki satu mata kuliah.',
                    confirmButtonText: 'Tutup'
                });
            }
        }

        function resetForm() {
            // Reset form fields
            document.getElementById('profile-form').reset();

            // Reset avatar to original
            document.getElementById('profile-pic').src = originalImageSrc;
            hasNewImage = false;

            // Remove validation errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.remove();
            });
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            originalFormData = new FormData(document.getElementById('profile-form'));
        });
    </script>
@endsection
