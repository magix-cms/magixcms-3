$(document).ready(function() {
    // Initialize events
    $("#login_form").validate({
        rules: {
            "email_admin":{
                "email": true,
                "required": true
            },
            "passwd_admin": {
                "required": true
            }
        },
        //submitHandler: function(form) {
        //    doAjaxLogin($('#redirect').val());
        //},
        // override jquery validate plugin defaults for bootstrap 3
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $("#forgot_password_form").validate({
        rules: {
            "email_forgot": {
                "email": true,
                "required": true
            }
        },
        onsubmit: true,
        event: 'submit',
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(f,e) {
            e.preventDefault();
            $.jmRequest({
                handler: "submit",
                url: '/admin/index.php?controller=login&action=rstpwd',
                method: 'post',
                form: $(f),
                resetForm:true,
                beforeSend:function(){},
                success:function(d){
                    if(d.status) {
                        $(f).find('.form-group').hide();
                        $(f).find('[type="submit"]').hide();
                        var content = $(f).find('.mc-message').html();
                        $(f).find('.mc-message').toggleClass('alert-info').toggleClass('alert-success').find('h4').text('Demande de mot de passe envoyée !');
                        $(f).find('.mc-message').find('p').text(d.result);

                        setTimeout(function(){
                            $(f).find('.form-group').show();
                            $(f).find('[type="submit"]').show();
                            $(f).find('.mc-message').empty();
                            $(f).find('.mc-message').toggleClass('alert-info').toggleClass('alert-success').prepend(content);
                        },10000);
                    }
                }
            });
            return false;
        }
    });

    $('.forgot-password').on('click',function(e) {
        e.preventDefault();
        displayForgotPassword();
    });

    $('.login-form').on('click',function(e) {
        e.preventDefault();
        displayLogin();
    });

    $('#email_admin').focus();
});

function displayForgotPassword() {
    $('#error').hide();
    $("#login").find('.flip-container').toggleClass("flip");
    setTimeout(function(){$('.front').hide()},200);
    setTimeout(function(){$('.back').show()},200);
    $('#email_forgot').select();
}

function displayLogin() {
    $('#error').hide();
    $("#login").find('.flip-container').toggleClass("flip");
    setTimeout(function(){$('.back').hide()},200);
    setTimeout(function(){$('.front').show()},200);
    $('#email').select();
    return false;
}