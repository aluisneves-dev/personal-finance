$(function(){
    
    var formLogin = $('.formLogin');
    var btnLogin = $('.btnLogin');

    function formLoginReset(){
        $('input, select').removeClass('input-error');
        $('.form-group > #recaptcha > div').removeClass('input-error');
        $('.error-text').text('').css('color','white');
    }

    formLogin.submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Carregando...',
            text: 'Por favor, aguarde',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading()
            }
        });
        var dataForm = new FormData(this);       
        var url = $(this).attr('action');
        var type = $(this).attr('method');

        $.ajax({
            url: url,
            type: type,
            dataType: 'JSON',
            data: dataForm,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                formLoginReset()
            },
            success: function(response){
                Swal.close();
                var status = response.status;
                var comment = response.comment;
                var error = response.error;

                if(status === 0){
                    $.each(error, function(prefix, val){
                        $('span.'+prefix+'_error').text(val[0]);
                        $('select[name='+prefix+'], input[name='+prefix+']').addClass('input-error');
                        $('.form-group > #recaptcha > div').addClass('input-error');
                        $('.loginContainer').css('opacity',1);
                    });
                    if(!error['g-recaptcha-response']){
                        grecaptcha.reset();
                        $('.loginContainer').css('opacity',1);
                    }
                }
                if(status === 'error'){
                    $('#login-error').html(comment);
                    grecaptcha.reset();
                    $('.loginContainer').css('opacity',1);
                }
                if(status === 'success'){
                    Swal.fire({
                        icon:   'success',
                        title:  'Login Autorizado',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    window.location.href= "/dashboard";
                }
            },
            complete: function(){
                $('#username').attr('readonly', false);
                $('#password').attr('readonly', false);
                btnLogin.prop('disabled', false).html('Entrar <i class="fas fa-sign-in-alt"></i>');
            },
        });
    });
});