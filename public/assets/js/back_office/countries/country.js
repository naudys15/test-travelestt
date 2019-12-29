$('#loading_modal').modal('show')
$(document).ready(function(){
    $('#setLanguage').trigger('click')
})
var url = location.href.split('/')
var id = parseInt(url.pop())
if (Number.isInteger(id)) {
    $.ajax({
        url: location.origin+"/api/countries/"+id,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                let resource = data.message
                $('html>head>title').html('Travelestt | '+translations['edit_country'])
                $('#page-title h1').html(translations['edit_country'])
                $('input[name="iso"]').val(resource.iso)
                $('input[name="name"]').val(resource.name)
                $('#loading_modal').modal('hide')
            },
            404: function (data) {
                $('#loading_modal').modal('hide')
                window.history.back()
            }
        }
    });
} else {
    $(document).ready(function(){
        $('html>head>title').html('Travelestt | '+translations['new_country'])
                $('#page-title h1').html(translations['new_country'])
        $('#loading_modal').modal('hide')
    })
}
$("#form_location").on('submit', function(e){
    $("#submit_btn").attr('disabled', 'disabled')
    e.preventDefault()
    e.stopPropagation()
    var defaultPrefix = ''
    if ($('#form_location').attr('action').indexOf('update') > 0) {
        defaultPrefix = translations['message_result_update']+' ';
    } else {
        defaultPrefix = translations['message_result']+' ';
    }
    message = translations['message_country'];
    fieldsInForm = ($('#iso').val() != "" && $('#name').val() != "")
    if (fieldsInForm) {
        var formData = new FormData()
        formData.append('_token', $('input[name="_token"]').val())
        formData.append('iso', $('input[name="iso"]').val())
        formData.append('name', $('input[name="name"]').val())
        $.ajax({
            url: $('#form_location').attr('action'),
            headers: authorization(),
            type: $('#form_location').attr('method'),
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(data) {
                $('#loading_modal').modal('show')
            },
            statusCode: {
                201: function(data) {
                    $('#loading_modal').modal('hide')
                    $('form .well').empty('')
                    $('form .well').css('display', 'none')
                    $.niftyNoty({
                        type: "success",
                        container: "floating",
                        html: defaultPrefix+message,
                        closeBtn: true,
                        floating: {
                            position: "bottom-right",
                            animationIn: "jellyIn",
                            animationOut: "fadeOut"
                        },
                        focus: true,
                        timer: 2500
                    });
                    $("#form_site input[type='text']").val('')
                    $("#submit_btn").removeAttr('disabled')
                },
                400: function(data) {
                    $('#loading_modal').modal('hide')
                    var allErrors = data.responseJSON.message
                    $('form .well').html('')
                    $.each(allErrors, function(i, item){
                        $('form .well').append('<p>'+item+'</p>')
                    })
                    $('form .well').css('display', 'block')
                    $("#submit_btn").removeAttr('disabled')
                },
                401: function(data) {
                    $('#loading_modal').modal('hide')
                    $.niftyNoty({
                        type: "danger",
                        container: "floating",
                        html: data.responseJSON.message,
                        closeBtn: true,
                        floating: {
                            position: "bottom-right",
                            animationIn: "jellyIn",
                            animationOut: "fadeOut"
                        },
                        focus: true,
                        timer: 2500
                    })
                    setTimeout(function() {
                        window.history.back()
                    }, 3000)
                }
            }
        })
    } else {
        $('form .well').css('display', 'block');
        $('form .well').html(translations['message_more_than_two_markers_images_no_form']);
    }
})