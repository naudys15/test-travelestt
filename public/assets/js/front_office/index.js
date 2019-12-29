setLanguage()
var user = localStorage.getItem('travelesttUser')
var expires = localStorage.getItem('travelesttExpires')

$(document).ready(function() {

    Scrollbar.initAll();
    
    if(user && new Date() < new Date(expires)) {
        user = JSON.parse(descrypt(localStorage.getItem("travelesttUser")))
        $("#login_icon i").removeClass('fas fa-user')
        if(user.image) {
            var route_image = routeImage('users')
            var img = '<img class="avatar" src="'+ route_image + user.image +'">'
            $("#login_icon").append(img)
        } else {
            var init = '<span class="avatar_user">'+initialString(user.full_name)+'</span>'
            $("#login_icon").append(init)
        }
        $('#login').css('display', 'none')
    }

    $('#search_hero_input').click(function(e){
        e.preventDefault()
        e.stopPropagation()
        $('#top_destinos').css('display','block')
    })
    // $('#search_hero_input').focusout(function(){
    //     $('#top_destinos').css('display','none')
    // })
    $(document).on('click',function(e){
        // console.log('click')
        let top_destinos=$('#top_destinos');
        let top_destinos_h5=$('#top_destinos h5');
        let click=e.target;
        let search_hero_input=$('#search_hero_input');
        // console.log(click)
        // console.log(search_hero_input)
        if(top_destinos.css('display')=='block' && click!=top_destinos){
            // top_destinos.style.display==none
            // console.log('estoy clicando fuera')
            top_destinos.css('display','none')
        }
    })
    var btn_login = $('#form_login button')
    $('#email').keypress(function(e) {
        focusField(e, '#password')
    })
    $('#password').keypress(function(e) {
        focusField(e, '#form_login button')
    })
    $('#form_login button').on('click', function() {
        $('small').remove()
        var text_btn = btn_login.text()
        btn_login.html('<i class="fas fa-spinner fa-spin"></i>')
        btn_login.prop("disabled", true)
        $('#email').prop("disabled", true)
        $('#password').prop("disabled", true)

        $.ajax({
            async: false,
            url: location.origin+"/api/login",
            type: $('#form_login').attr('method'),
            dataType: 'json',
            headers: {
                'Accept-Language': language
            },  
            data: {
                email: $('#form_login input[name="email"]').val(),
                password: $('#form_login input[name="password"]').val(),
                role: 'client'
            },
            statusCode: {
                200: function(data) {
                    localStorage.setItem("travelesttAccess", data.message.token)
                    localStorage.setItem("travelesttExpires", data.message.expires)
                    localStorage.setItem("travelesttUser", encrypt(JSON.stringify(data.message.user)));
                    window.location.href= location.origin+'/' + language
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
                        $('#form_login').append(`<small class="help-block text-danger">${errors}</small>`)
                    }
                }
            },
        });
    });
    $('#search_icon').on('click', function(){
        $('#search_hero_input').focus()
    });
    $('#mis_viajes').hover(
        function() {
            $('#mi_viaje_sidebar').addClass('sidebar_on')
            if ($('#login').css('display') == 'block') {
                $('#login').css('display', 'none')
            }
        },
        function() {
            setTimeout(function() {
                $('#mi_viaje_sidebar').mouseleave(function() {
                    $('#mi_viaje_sidebar').removeClass('sidebar_on')
                })
            }, 1000)
        }
    );
    $('#login_icon').click(function() {
        if(user) {
            if(language == 'es' || language == 'en') {
                redirectLogin('panel')
            } else {
                redirectLogin('panneau')
            }
        }
    })

    $('#login_icon').hover(
        function() {
            if (!user) {
                $('#login').css('display', 'block')
                if ($('#mi_viaje_sidebar').hasClass('sidebar_on')) {
                    $('#mi_viaje_sidebar').removeClass('sidebar_on')
                }
            } else {
               $('#submenu').css('display', 'block')
            }
        },
        function() {
            setTimeout(function() {
                $('#login').mouseleave(function() {
                    $('#login').css('display', 'none')
                })
                $('#submenu').mouseleave(function() {
                    $('#submenu').css('display', 'none')
                })
            }, 1000)
        }
    );
})

function redirectLogin(route) {
    window.location.replace(location.origin+'/'+language+"/"+ route)    
}

function focusField(e, field) {
    var code = (e.keyCode ? e.keyCode : e.which)
    if (code == 13) {
        $(field).focus()
    }
}

function loadMainDestinations()
{
    $.ajax({
        async: false,
        url: location.origin+"/api/cities/featured",
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept-Language': language
        },  
        statusCode: {
            200: function(data) {
                let container = $('#principales_destinos .destinos>.row')
                let mainDestinations = data.message
                $.each(mainDestinations, function(a, b){
                    let output = ''
                    output += '<div class="col-sm-6 col-md-4 destino mb-2"><div class="destino_img position-relative">'
                    if (b.image != null) {
                        output += '<a href="'+location.origin+'/'+language+'/'+b.slug+'"><div class="bg_img" style="height:225px; background-image: url('+location.origin+'/assets/images/cities/'+b.image+')"></div></a>'
                    } else {
                        output += '<a href="'+location.origin+'/'+language+'/'+b.slug+'"><div class="bg_img" style="height:225px; background-image: url('+location.origin+'/assets/images/madrid.jpg)"></div></a>'
                    }
                    output += '<a class="destino_info position-absolute p-3 w-100" href="'+location.origin+'/'+language+'/'+b.slug+'"><h4 class="ciudad text-white font-weight-bold mb-0">'+b.name+'</h4>'
                    // output += '<div class="text-white"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>56 opiniones</small></div>'
                    output += '</a></div></div>'
                    $(container).append(output)
                })
                // $(container).append('<div class="col-12 mt-4 text-center"><a href="" class="btn btn-outline-main px-4 py-2">Ver todos</a></div>')
            },
            400: function (data) {
                // console.log(data)
            }
        },
    });
}

if ($('#principales_destinos').length > 0) {
    loadMainDestinations();
}

function loadTopDestinations()
{
    $.ajax({
        async: false,
        url: location.origin+"/api/cities/top_destinations",
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept-Language': language
        },  
        statusCode: {
            200: function(data) {
                let container = $('#top_destinos_content .row')
                let topDestinations = data.message
                $.each(topDestinations, function(a, b){
                    let output = ''
                    output += '<div class="col-md-4 text-left"><ul><li>'
                    output += '<a href="'+location.origin+'/'+language+'/'+b.slug+'"><strong>'+b.name+'</strong><br><span class="text-secondary">'+b.country.name+'</span></a>'
                    output += '</li></ul></div>'     
                    $(container).append(output)
                })
            },
            400: function (data) {
                // console.log(data)
            }
        },
    });
}

if ($('#top_destinos_content').length > 0) {
    loadTopDestinations();
}