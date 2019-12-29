$(document).ready(function(){
    $('button[type="button"]').click(function(){
        let key = $("form input[name='key']").val()
        let password = $("form input[name='password']").val()
        let password_confirmation = $("form input[name='password_confirmation']").val()
        let _token = $("form input[name='_token']").val()
        $.ajax({
            url: location.origin+"/api/new_password",
            type: 'POST',
            dataType: "json",
            data: {
                key: key,
                password: password,
                password_confirmation: password_confirmation,
                _token: _token
            },
            statusCode: {
                200: function(data) {
                    let email = data.message.email
                    $.ajax({
                        async: false,
                        url: location.origin+"/api/login",
                        type: 'POST',
                        dataType: 'json',
                        headers: {
                            'Accept-Language': $('html').attr('lang')
                        },  
                        data: {
                            email: email,
                            password: password
                        },
                        statusCode: {
                            200: function(data) {
                                localStorage.setItem("travelesttAccess", data.message.token)
                                if ($('html').attr('lang') == 'es' || $('html').attr('lang') == 'en') {
                                    window.location.replace(location.origin+"/"+$('html').attr('lang')+"/panel")    
                                } else {
                                    window.location.replace(location.origin+"/"+$('html').attr('lang')+"/panneau")
                                }
                            }
                        }
                    });
                },
                400: function (data) {
                    $('#form_new_password .has-error').removeClass('has-error')
                    $('#form_new_password .text-danger').remove()
                    let errors = data.responseJSON.message
                    $.each(errors, function(key, value) {
                        let item = $('#form_new_password *[name="'+key+'"]')
                        if (item.length > 0) {
                            item.parent().addClass('has-error')
                            $.each(value, function(a, b) {
                                item.after('<small class="pull-left text-danger">'+b+'</small>')
                                
                            });
                        }
                    });
                }
            }
        })
    })
})