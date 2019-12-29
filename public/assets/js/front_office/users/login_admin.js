setLanguage();
$(document).ready(function() {
    var btn_login = $('#form_login button')
    $("form button[type='submit']").click(function(e){
        e.preventDefault()
        e.stopPropagation()
        $('small').remove()
        var text_btn = btn_login.text()
        btn_login.html('<i class="fas fa-spinner fa-spin"></i>')
        btn_login.prop("disabled", true)
        $('#email').prop("disabled", true)
        $('#password').prop("disabled", true)
        $.ajax({
            url: location.origin+"/api/login",
            type: 'POST',
            dataType: "json",
            headers: {
                'Accept-Language': $('html').attr('lang')
            }, 
            data: {
                email: $('#form_login input[name="email"]').val(),
                password: $('#form_login input[name="password"]').val(),
                role: 'admin'
            },
            statusCode: {
                200: function(data) {
                    localStorage.setItem("travelesttAccess", data.message.token)
                    localStorage.setItem("travelesttExpires", data.message.expires)
                    localStorage.setItem("travelesttUser", encrypt(JSON.stringify(data.message.user)));
                    window.location.href= `${location.origin}/${language}/${translations['url_panel']}` 
                },
                400: function (data) {
                    btn_login.html(text_btn)
                    btn_login.removeAttr('disabled')
                    $("#email").removeAttr('disabled')
                    $("#password").removeAttr('disabled')

                    $('#form_login .border-danger').removeClass('border-danger')
                    $('#form_login .text-danger').remove()
                    let errors = data.responseJSON.message
                    if (typeof errors === 'object') {
                        $.each(errors, function(key, value) {
                            let item = $('#form_login *[name="'+key+'"]')
                            if (item.length > 0) {
                                item.addClass('border-danger')
                                item.after('<small class="help-block text-danger">'+value+'</small>')
                            }
                        });
                    } else if (typeof errors === 'string') {
                        $('#form_login button').before(`<small class="help-block text-danger">${errors}</small>`)
                    }
                }
            }
        });
    });
});