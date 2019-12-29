$(document).ready(function() {

    $.ajax({
        url: location.origin+"/api/countries",
        type: "GET",
        success: function(data) {
            $.each(data.message, function(i, item) {
                $('#country').append($('<option>', {
                    value: item.id,
                    text : item.name
                }));
            });
        }
    })

    $('#country').on('change', function() {
        if ($('#country').val() != '') {
            let firstName = $('#city option:first-child')
            $.ajax({
                url: location.origin+"/api/cities/country/"+$('#country').val(),
                type: "GET",
                success: function(data) {
                    $('#city').html(firstName)
                    $.each(data.message, function(i, item) {
                        $('#city').append($('<option>', {
                            value: item.id,
                            text : item.name
                        }));
                    });
                }
            })
        }
    })

    // select2 ciudades
    $('.select_2').select2()

    $("form button[type='submit']").click(function(e){
        e.preventDefault()
        e.stopPropagation()
        let firstname = $("form input[name='firstname']").val()
        let lastname = $("form input[name='lastname']").val()
        let city = $("form select[name='city']").val()
        let email = $("form input[name='email']").val()
        let phonenumber = $("form input[name='phonenumber']").val()
        let password = $("form input[name='password']").val()
        let password_confirmation = $("form input[name='password_confirmation']").val()
        let _token = $("form input[name='_token']").val()
        $.ajax({
            url: location.origin+"/api/register",
            type: 'POST',
            dataType: "json",
            headers: {
                'Accept-Language': $('html').attr('lang')
            }, 
            data: {
                firstname: firstname,
                lastname: lastname,
                id: city,
                email: email,
                phonenumber: phonenumber,
                password: password,
                password_confirmation: password_confirmation,
                _token: _token
            },
            statusCode: {
                201: function(data) {
                    $.ajax({
                        async: false,
                        url: location.origin+"/api/login",
                        type: $('#form_register').attr('method'),
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
                                localStorage.setItem("travelesttUser", encrypt(JSON.stringify(data.message.user)));
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
                    $('#form_register .has-error').removeClass('has-error')
                    $('#form_register .text-danger').remove()
                    let errors = data.responseJSON.message
                    $.each(errors, function(key, value) {
                        let item = $('#form_register *[name="'+key+'"]')
                        if (item.length > 0) {
                            item.parent().addClass('has-error')
                            $.each(value, function(a, b) {
                                item.after('<small class="pull-left text-danger">'+b+'</small>')
                                
                            });
                        }
                    });
                }
            }
        });
    });
});