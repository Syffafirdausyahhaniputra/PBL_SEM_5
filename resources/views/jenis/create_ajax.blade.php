<form action="{{ url('/jenis/ajax') }}" method="POST" id="form-tambah-jenis"> 
    @csrf 
    <div id="modal-master" class="modal-dialog modal-lg" jenis="document"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <h5 class="modal-title" id="exampleModalLabel">Tambah Jenis Sertifikasi</h5> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span 
                aria-hidden="true">&times;</span></button> 
            </div> 
            <div class="modal-body"> 
                <div class="form-group"> 
                    <label>Kode Jenis Sertifikasi</label> 
                    <input value="" type="text" name="jenis_kode" id="jenis_kode" class="form-control" required> 
                    <small id="error-jenis_kode" class="error-text form-text text-danger"></small> 
                </div> 
                <div class="form-group"> 
                    <label>Nama Jenis Sertifikasi</label> 
                    <input value="" type="text" name="jenis_nama" id="jenis_nama" class="form-control" required> 
                    <small id="error-jenis_nama" class="error-text form-text text-danger"></small> 
                </div> 
            </div> 
            <div class="modal-footer"> 
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button> 
                <button type="submit" class="btn btn-primary">Simpan</button> 
            </div> 
        </div> 
    </div> 
</form> 
<script> 
    $(document).ready(function() { 
        $("#form-tambah-jenis").validate({ 
            rules: { 
                jenis_kode: {required: true, minlength: 2, maxlength: 20}, 
                jenis_nama: {required: true, minlength: 3, maxlength: 100} 
            }, 
            submitHandler: function(form) { 
                $.ajax({ 
                    url: form.action, 
                    type: form.method, 
                    data: $(form).serialize(), 
                    success: function(response) { 
                        if(response.status){ 
                            $('#myModal').modal('hide'); 
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Berhasil', 
                                text: response.message 
                            }); 
                            datajenis.ajax.reload(); 
                        } else { 
                            $('.error-text').text(''); 
                            $.each(response.msgField, function(prefix, val) { 
                                $('#error-'+prefix).text(val[0]); 
                            }); 
                            Swal.fire({ 
                                icon: 'error', 
                                title: 'Terjadi Kesalahan', 
                                text: response.message 
                            }); 
                        } 
                    }             
                }); 
                return false; 
            }, 
            errorElement: 'span', 
            errorPlacement: function (error, element) { 
                error.addClass('invalid-feedback'); 
                element.closest('.form-group').append(error); 
            }, 
            highlight: function (element, errorClass, validClass) { 
                $(element).addClass('is-invalid'); 
            }, 
            unhighlight: function (element, errorClass, validClass) { 
                $(element).removeClass('is-invalid'); 
            } 
        }); 
    }); 
</script>