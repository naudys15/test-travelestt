$('#loading_modal').modal('show')
$('#setLanguage').trigger('click')
var url = window.location.href
var split = url.split('/')
var parent = split[5]
var idParent = parseInt(split[6])
var typeToSearch = false
var id = parseInt(split.pop())
if (parent == translations['url_provinces']) {
    typeToSearch = true 
} 

var max_files = 1;
var exist_new_image = false;

Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#dropzoneFiles", {
    paramName: "file",
    autoProcessQueue: false,
    uploadMultiple: false,
    parallelUploads: 100,
    maxFilesize: 2,
    maxFiles: max_files,
    acceptedFiles: ".jpg, .jpeg, .png",
    dictRemoveFile: "Remover",
    dictFileTooBig: translations['dictFileTooBig'],
    dictInvalidFileType: translations['dictInvalidFileType'],
    dictCancelUpload: translations['dictCancelUpload'],
    dictRemoveFile: translations['dictRemoveFile'],
    dictMaxFilesExceeded: translations['dictMaxFilesExceeded'],
    dictDefaultMessage: translations['dictDefaultMessage'],
    removedfile: function(file) {
        var _ref = file.previewElement;
        return (_ref != null)? _ref.parentNode.removeChild(file.previewElement) : void 0;
    }
});
myDropzone.on("addedfile", function(file) {
    exist_new_image = true

    if (this.files.length > max_files) {
        this.removeFile(file);                          
    }

    myDropzone.emit("complete", file);
    var removeButton = Dropzone.createElement("<button class='btn-link'>"+translations['delete']+"</button>");
    var _this = this;
    removeButton.addEventListener("click", function(e) {
        _this.removeFile(file);
        exist_new_image = false
    });
    file.previewElement.appendChild(removeButton);
})
$('#dropzoneFiles').addClass('dropzone')
if (Number.isInteger(id)) {
    $.ajax({
        url: location.origin+"/api/cities/"+id,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                let resource = data.message
                if (typeToSearch) {
                    $('html>head>title').html('Travelestt | '+resource.province.name+' | '+translations['edit_city'])
                    $('#page-title h1').html(resource.province.name+' | '+translations['edit_city'])
                } else {
                    $('html>head>title').html('Travelestt | '+resource.country.name+' | '+translations['edit_city'])
                    $('#page-title h1').html(resource.country.name+' | '+translations['edit_city'])
                }
                $('input[name="name"]').val(resource.name)
                $('input[name="slug"]').val(resource.slug)
                $('input[name="latitude"]').val(resource.latitude)
                $('input[name="longitude"]').val(resource.longitude)
                $('input[name="altitude"]').val(resource.altitude)
                if(resource.image) {
                    let img_url = location.origin+"/assets/images/cities/"+resource.image
                    let mockFile = {
                        name: resource.image,
                        size: 12345,
                        accepted: true,
                        kind: 'image',
                        status: Dropzone.ADDED,
                    };
                    myDropzone.emit("addedfile", mockFile)
                    myDropzone.emit("thumbnail", mockFile, img_url)
                    myDropzone.files.push(mockFile)
                    myDropzone.emit("complete", mockFile)
                    exist_new_image = false
                }
                $('#loading_modal').modal('hide')
            },
            404: function (data) {
                $('#loading_modal').modal('hide')
                window.history.back()
            }
        }
    });
}
$(document).ready(function(){
    if (typeToSearch) {
        $.ajax({
            async: false,
            url: location.origin+"/api/provinces/"+idParent,
            type: 'GET',
            dataType: 'json',
            headers: authorization(),
            statusCode: {
                200: function(data) {
                    let province = data.message
                    $('html>head>title').html('Travelestt | '+province.name+' | '+translations['new_city'])
                    $('#page-title h1').html(province.name+' | '+translations['new_city'])
                    $.ajax({
                        async: true,
                        url: location.origin+"/api/provinces",
                        type: 'GET',
                        dataType: 'json',
                        headers: authorization(),
                        statusCode: {
                            200: function(_data) {
                                $.each(_data.message, function(i, item) {
                                    $('#province').append($('<option>', {
                                        value: item.id,
                                        text : item.name
                                    }));
                                    if (item.id == idParent) {
                                        $.ajax({
                                            async: true,
                                            url: location.origin+"/api/countries",
                                            type: 'GET',
                                            dataType: 'json',
                                            headers: authorization(),
                                            statusCode: {
                                                200: function(data) {
                                                    $.each(data.message, function(j, element) {
                                                        $('#country').append($('<option>', {
                                                            value: element.id,
                                                            text : element.name
                                                        }));
                                                        if (element.id == item.id) {
                                                            $('#country').val(element.id)
                                                        }
                                                    });
                                                },
                                                404: function(data) {
                                                    $('#loading_modal').modal('hide')
                                                }
                                            }
                                        });
                                        $('#province').val(item.id)
                                    }
                                });
                            },
                            404: function(data) {
                                $('#loading_modal').modal('hide')
                            }
                        }
                    });
                },
                404: function(data) {
                    $('#loading_modal').modal('hide')
                }
            }
        });
        $('#loading_modal').modal('hide')
    } else {
        $.ajax({
            async: true,
            url: location.origin+"/api/countries",
            type: 'GET',
            dataType: 'json',
            headers: authorization(),
            statusCode: {
                200: function(data) {
                    $.each(data.message, function(j, element) {
                        $('#country').append($('<option>', {
                            value: element.id,
                            text : element.name
                        }));
                        if (element.id == idParent) {
                            $('html>head>title').html('Travelestt | '+element.name+' | '+translations['new_city'])
                            $('#page-title h1').html(element.name+' | '+translations['new_city'])
                        }
                    });
                    $.ajax({
                        async: true,
                        url: location.origin+"/api/provinces/country/"+idParent,
                        type: 'GET',
                        dataType: 'json',
                        headers: authorization(),
                        statusCode: {
                            200: function(_data) {
                                $.each(_data.message, function(j, element) {
                                    $('#province').append($('<option>', {
                                        value: element.id,
                                        text : element.name
                                    }));
                                });
                            },
                            404: function(_data) {
                                $('#loading_modal').modal('hide')
                            }
                        }
                    });
                    $('#country').val(idParent)
                },
                404: function(data) {
                    $('#loading_modal').modal('hide')
                }
            }
        });
        $('#loading_modal').modal('hide')
    }
    $('#country').on('change', function(){
        if ($('#country').val() != '') {
            let firstName = $('#province option:first-child')
            $.ajax({
                url: location.origin+"/api/provinces/country/"+$('#country').val(),
                type: "GET",
                success: function(data) {
                    $('#province').html(firstName)
                    $.each(data.message, function(i, item) {
                        $('#province').append($('<option>', {
                            value: item.id,
                            lat: item.latitude,
                            lng: item.longitude,
                            text : item.name
                        }));
                    });
                },
                error: function (data) {
                    $('#province').html(firstName)
                }
            })
        }
    })
})
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
    message = translations['message_city'];
    fieldsInForm = ($('#slug').val() != "" && $('#name').val() != "" && $('#latitude').val() != "" && $('#longitude').val() != "" && $('#altitude').val() != "" && $('#country option:selected').val() != "" && $('#province option:selected').val() != "")
    if (fieldsInForm) {
        var formData = new FormData()
        formData.append('_token', $('input[name="_token"]').val())
        formData.append('name', $('input[name="name"]').val())
        formData.append('slug', $('input[name="slug"]').val())
        formData.append('latitude', $('input[name="latitude"]').val())
        formData.append('longitude', $('input[name="longitude"]').val())
        formData.append('altitude', $('input[name="altitude"]').val())
        formData.append('country', $('#country').val())
        formData.append('province', $('#province').val())
        if(myDropzone.getAcceptedFiles()[0] != undefined && exist_new_image) formData.append('file', myDropzone.getAcceptedFiles()[0]);
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