const originalImageSrc =
    "{{ $user->avatar ? asset('storage/avatar/' . $user->avatar) : asset('img/user.png') }}";
let hasNewImage = false;

document.addEventListener("DOMContentLoaded", function () {
    document
        .getElementById("profile-form")
        .addEventListener("submit", handleFormSubmit);

    document.querySelectorAll("input").forEach((input) => {
        input.addEventListener("input", clearValidationErrors);
    });
});

function toggleEdit() {
    const isReadOnly = document.getElementById("username").readOnly;
    const editBtn = document.getElementById("edit-btn");
    const saveCancelGroup = document.getElementById("save-cancel-group");
    const oldPasswordGroup = document.getElementById("old-password-group");
    const newPasswordGroup = document.getElementById("new-password-group");
    const avatarInput = document.getElementById("avatar");

    document.getElementById("username").readOnly = !isReadOnly;
    document.getElementById("nama").readOnly = !isReadOnly;
    document.getElementById("nip").readOnly = !isReadOnly;
    avatarInput.disabled = !isReadOnly;

    oldPasswordGroup.classList.toggle("d-none");
    newPasswordGroup.classList.toggle("d-none");
    saveCancelGroup.classList.toggle("d-none");

    editBtn.innerText = isReadOnly ? "Batal" : "Edit";

    if (!isReadOnly) {
        resetProfileForm();
    }
}

function previewAndUploadImage(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2048000) {
            Swal.fire(
                "Error",
                "Ukuran gambar tidak boleh lebih dari 2MB.",
                "error"
            );
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("profile-pic").src = e.target.result;
            hasNewImage = true;
        };
        reader.readAsDataURL(file);
    }
}

document.getElementById("profile-form").addEventListener("submit", function (e) {
    e.preventDefault(); // Mencegah reload halaman

    const formData = new FormData(this);

    // Pastikan input file avatar ditambahkan ke FormData
    const avatarInput = document.getElementById("avatar");
    if (avatarInput.files.length > 0) {
        formData.append("avatar", avatarInput.files[0]);
    }

    fetch(this.action, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            Accept: "application/json",
        },
        credentials: "same-origin",
    })
        .then(async (response) => {
            const data = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: data.message || "Profil berhasil diperbarui!",
                    timer: 3000,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.reload();
                });
            } else if (response.status === 422) {
                let errorMessages = "";
                Object.keys(data.errors).forEach((field) => {
                    errorMessages += `${data.errors[field]}\n`;
                });

                Swal.fire({
                    icon: "error",
                    title: "Validasi Gagal!",
                    text: errorMessages.trim(),
                    timer: 4000,
                    showConfirmButton: false,
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Kesalahan!",
                    text: data.message || "Gagal memperbarui profil.",
                    timer: 4000,
                    showConfirmButton: false,
                });
            }
        })
        .catch((error) => {
            console.error("Error:", error);

            Swal.fire({
                icon: "error",
                title: "Kesalahan Server!",
                text: "Terjadi kesalahan pada server. Silakan coba lagi nanti.",
                timer: 4000,
                showConfirmButton: false,
            });
        });
});

function updateDisplay(user) {
    document.getElementById("display-nama").textContent = user.nama;
    document.getElementById("display-username").textContent =
        "@" + user.username;
    if (user.avatar) {
        document.getElementById("profile-pic").src = `${
            user.avatar
        }?v=${new Date().getTime()}`;
    }
}

function clearValidationErrors() {
    this.classList.remove("is-invalid");
    const feedback = this.parentNode.querySelector(".invalid-feedback");
    if (feedback) {
        feedback.remove();
    }
}
