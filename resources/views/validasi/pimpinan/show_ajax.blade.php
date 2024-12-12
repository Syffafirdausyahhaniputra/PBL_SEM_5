@extends('layouts.template')

<div class="modal fade" id="validasiModal" tabindex="-1" aria-labelledby="validasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="validasiModalLabel">Detail Validasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Kontainer data -->
                <div id="detailValidasi">
                    <!-- Data akan dimuat melalui AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="validasiButton">Validasi</button>
            </div>
        </div>
    </div>
</div>

<script>
    function modalAction(url) {
        // Muat data menggunakan AJAX
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.status) {
                    // Muat konten ke modal
                    let validasi = response.validasi;
                    let html = `
                        <table class="table table-bordered">
                            <tr>
                                <th>Type</th>
                                <td>${validasi.type === 'sertifikasi' ? 'Sertifikasi' : 'Pelatihan'}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>${validasi.nama}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>${validasi.keterangan}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>${validasi.status}</td>
                            </tr>
                            <tr>
                                <th>Peserta</th>
                                <td>
                                    <ul>
                                        ${validasi.peserta.map(p => `<li>${p.nama}</li>`).join('')}
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    `;
                    $('#detailValidasi').html(html);
                    $('#validasiModal').modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat memuat data.');
            }
        });
    }

    // Pastikan tombol Validasi memanggil modalAction dengan URL yang benar
    $(document).on('click', '#validasiButton', function() {
        var url = $(this).data('url'); // Ambil URL dari data-url
        modalAction(url); // Panggil modalAction dengan URL
    });
</script>
