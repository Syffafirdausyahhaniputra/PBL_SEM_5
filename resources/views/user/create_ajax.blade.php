<form action="{{ url('/user/ajax') }}" method="POST" id="form-tambah"> 
    @csrf 
    <div id="modal-master" class="modal-dialog modal-lg" role="document"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span 
                aria-hidden="true">&times;</span></button> 
            </div> 
            <div class="modal-body"> 
                <div class="form-group"> 
                    <label>Role Pengguna</label> 
                    <select name="role_id" id="role_id" class="form-control" required> 
                        <option value="">- Pilih Role -</option> 
                        @foreach($role as $l) 
                            <option value="{{ $l->role_id }}">{{ $l->role_nama }}</option> 
                        @endforeach 
                    </select> 
                    <small id="error-role_id" class="error-text form-text text-danger"></small> 
                </div> 
                <div class="form-group"> 
                    <label>Username</label> 
                    <input value="" type="text" name="username" id="username" class="form-control" 
                    required> 
                    <small id="error-username" class="error-text form-text text-danger"></small> 
                </div> 
                <div class="form-group"> 
                    <label>Nama</label> 
                    <input value="" type="text" name="nama" id="nama" class="form-control" 
                    required> 
                    <small id="error-nama" class="error-text form-text text-danger"></small> 
                </div> 
                <div class="form-group"> 
                    <label>NIP</label> 
                    <input value="" type="text" name="nip" id="nip" class="form-control" 
                    required> 
                    <small id="error-nip" class="error-text form-text text-danger"></small> 
                </div> 
                <div class="form-group"> 
                    <label>Email</label> 
                    <input value="" type="text" name="email" id="email" class="form-control" 
                    required> 
                    <small id="error-email" class="error-text form-text text-danger"></small> 
                </div> 
                <div class="form-group"> 
                    <label>Password</label> 
                    <input value="" type="password" name="password" id="password" class="form-control" required> 
                    <small id="error-password" class="error-text form-text text-danger"></small> 
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
        $("#form-tambah").validate({ 
            rules: { 
                role_id: {required: true, number: true}, 
                username: {required: true, minlength: 3, maxlength: 20}, 
                nama: {required: true, minlength: 3, maxlength: 100}, 
                nip: {required: true, minlength: 3, maxlength: 100}, 
                email: {required: true, minlength: 3, maxlength: 100}, 
                password: {required: true, minlength: 6, maxlength: 20} 
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
                            dataUser.ajax.reload(); 
                        }else{ 
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