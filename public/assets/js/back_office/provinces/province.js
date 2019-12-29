$('#loading_modal').modal('show')
var url = location.href.split('/')
var idCountry = url[6]
var id = parseInt(url.pop())
$(document).ready(function(){
    $('#setLanguage').trigger('click')
    $.ajax({
        async: false,
        url: location.origin+"/api/countries",
        type: "GET",
        success: function(data) {
            $.each(data.message, function(i, item) {
                $('#country').append($('<option>', {
                    value: item.id,
                    text : item.name
                }));
            });
            $('#country').val(idCountry)
        }
    })
})
if (Number.isInteger(id)) {
    $.ajax({
        url: location.origin+"/api/provinces/"+id,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                let resource = data.message
                $('html>head>title').html('Travelestt | '+resource.country.name+' | '+translations['edit_province'])
                $('#page-title h1').html(resource.country.name+' | '+translations['edit_province'])
                $('input[name="name"]').val(resource.name)
                if (idCountry == resource.cound) {
                    $('#country').val(resource.id)
                } else {
                    $('#country').val()
                }
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
        $('html>head>title').html('Travelestt | '+$('#country option:selected').html()+' | '+translations['new_province'])
        $('#page-title h1').html($('#country option:selected').html()+' | '+translations['new_province'])
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
    message = translations['message_province'];
    fieldsInForm = ($('#name').val() != "" && $('#country option:selected').val() != "")
    if (fieldsInForm) {
        var formData = new FormData()
        formData.append('_token', $('input[name="_token"]').val())
        formData.append('name', $('input[name="name"]').val())
        formData.append('country', $('#country').val())
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