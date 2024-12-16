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
                                                class="rounded-circle" width="100" height="100" alt="Profile Picture">
                                        </label>
                                        <input type="file" id="avatar" name="avatar" class="d-none"
                                            onchange="previewAndUploadImage(event)" accept="image/*">
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
                                    <label for="nip" class="form-label">NIP</label>
                                    <input id="nip" name="nip" type="text" class="form-control"
                                        value="{{ $user->nip }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" name="email" type="email" class="form-control"
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
                                <div class="form-group">
                                    <label>Bidang</label>
                                    <div id="bidang-container">
                                        @foreach ($bidangList as $item)
                                            <div class="bidang-item d-flex align-items-center mb-3">
                                                <select name="bidang_id[]" class="form-control bidang-select me-2" disabled>
                                                    <option value="">- Pilih Bidang -</option>
                                                    @foreach ($bidang as $b)
                                                        <option value="{{ $b->bidang_id }}" 
                                                            @if($item->bidang_id == $b->bidang_id) selected @endif>
                                                            {{ $b->bidang_nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-sm btn-danger remove-bidang edit-mode-only" 
                                                    onclick="removeBidang(this)" style="display: none;">Hapus</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" id="add-bidang" class="btn btn-sm btn-primary mt-2 edit-mode-only" 
                                        onclick="addBidang()" style="display: none;">Tambah Bidang</button>
                                </div>
    
                                <div class="form-group">
                                    <label>Mata Kuliah</label>
                                    <div id="matkul-container">
                                        @foreach ($matkulList as $item)
                                            <div class="matkul-item d-flex align-items-center mb-3">
                                                <select name="mk_id[]" class="form-control matkul-select me-2" disabled>
                                                    <option value="">- Pilih Mata Kuliah -</option>
                                                    @foreach ($matkul as $m)
                                                        <option value="{{ $m->mk_id }}"
                                                            @if($item->mk_id == $m->mk_id) selected @endif>
                                                            {{ $m->mk_nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-sm btn-danger remove-matkul edit-mode-only"
                                                    onclick="removeMatkul(this)" style="display: none;">Hapus</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" id="add-matkul" class="btn btn-sm btn-primary mt-2 edit-mode-only"
                                        onclick="addMatkul()" style="display: none;">Tambah Mata Kuliah</button>
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

    <script>
        let originalFormData = null;
        let originalImageSrc = "{{ $user->avatar ? asset('avatars/' . $user->avatar) : asset('img/user.png') }}";
        

        function toggleEdit() {
            const editBtn = document.getElementById('edit-btn');
            const isEdit = editBtn.innerText === 'Edit';

            if (isEdit && !originalFormData) {
                originalFormData = new FormData(document.getElementById('profile-form'));
            }

            document.querySelectorAll('input:not(#nama,#nip), select').forEach(input => {
                input.readOnly = !isEdit;
                input.disabled = !isEdit;
            });

            editBtn.innerText = isEdit ? 'Batal' : 'Edit';
            document.getElementById('save-cancel-group').classList.toggle('d-none', !isEdit);
            document.getElementById('old-password-group').classList.toggle('d-none', !isEdit);
            document.getElementById('new-password-group').classList.toggle('d-none', !isEdit);

            if (!isEdit && originalFormData) {
                resetForm();
            }
        }

        function resetForm() {
            const form = document.getElementById('profile-form');
            for (let [key, value] of originalFormData.entries()) {
                const input = form.querySelector([name="${key}"]);
                if (input) {
                    input.value = value;
                }
            }
            // Reset avatar
            document.getElementById('profile-pic').src = "{{ $user->avatar ? asset('avatars/' . $user->avatar) : asset('img/user.png') }}";
            
            // Reset password fields
            document.getElementById('old_password').value = '';
            document.getElementById('password').value = '';

            // Remove any validation errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.remove();
            });
        }

        function updateDisplayData(data) {
            document.getElementById('display-nama').textContent = data.user.nama;
            document.getElementById('display-username').textContent = '@' + data.user.username;
            document.getElementById('display-email').textContent = data.user.email;
            if (data.user.dosen) {
                document.getElementById('jabatan_id').value = data.user.dosen.jabatan_id;
                document.getElementById('golongan_id').value = data.user.dosen.golongan_id;
                document.getElementById('pangkat_id').value = data.user.dosen.pangkat_id;
            }
            if (data.user.avatar) {
                document.getElementById('profile-pic').src = '/avatars/' + data.user.avatar;
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

        // Form submission handler
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

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
                        // Store new form data as original
                        originalFormData = new FormData(this);
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


        function previewAndUploadImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-pic').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            originalFormData = new FormData(document.getElementById('profile-form'));

            // Ensure password fields are hidden initially
            document.getElementById('old-password-group').classList.add('d-none');
            document.getElementById('new-password-group').classList.add('d-none');
        });
    </script>
@endsection