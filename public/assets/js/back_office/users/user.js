$('#loading_modal').modal('show');
$('#setLanguage').trigger('click');
var url = location.href.split('/');
var id = parseInt(url.pop());
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



if ( $("#phonenumber")[0] ) {
    $('#phonenumber').mask('+34 999 99 99 99');
}

getRoles();
getProvinces();
hideSection();

if (validId()) {
	$.ajax({
        url: location.origin+"/api/users/"+id,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                let resource = data.message
                $('input[name="firstname"]').val(resource.firstname);
                $('input[name="lastname"]').val(resource.lastname);
                $('input[name="email"]').val(resource.email);
                $('input[name="phonenumber"]').val(resource.phonenumber);
         		$('#rol').val(resource.id);
         		$('#state').val(resource.city.id);
         		getCity(resource.city.id,resource.id);
                
                if(resource.image) {
                    let img_url = location.origin+"/assets/images/users/"+resource.image
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
} else {
	$(document).ready(function(){
        $('#loading_modal').modal('hide');
    })
}


$('#state').change(function() {
	let firstName = $('#city option:first-child')
    $.ajax({
        url: location.origin+"/api/cities/province/"+$('#state').val(),
        type: "GET",
        success: function(data_response) {
            $('#city').html(firstName)
            $.each(data_response.message, function(i, item) {
                $('#city').append($('<option>', {
                    value: item.id,
                    text : item.name
                }));
            });

        }
    })
});

$("#form_site").on('submit',function (e) {
    e.preventDefault();
    e.stopPropagation();
    hideSection()
    var method_form = $("#form_site").attr('method');
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();
    var email = $("#email").val();
    var phonenumber = $('#phonenumber').val();
    var city = $("#city").val();
    var password = $("#password").val();
    var password_confirmation = $("#password_confirmation").val();
    var rol = $("#rol").val();
    var default_prefix = '';
	var url_user = $("#form_site").attr('action');

    if(password != password_confirmation) {
    	$('form .well').css('display', 'block');
		$('form .well').append(translations['message_password_do_not_match']);
	} else if (firstname != '' && lastname != '' && email != '' && phonenumber != '' && city != '' && rol != '') {
        $("#submit_btn").attr('disabled','disabled');

        if(validId()) {
	    	default_prefix = translations['message_result_update']+' ';
	    } else {
		    default_prefix = translations['message_result']+' ';
		} 		

        var formData= new FormData();
        formData.append('firstname', firstname);
        formData.append('lastname', lastname);
        formData.append('email', email);
        formData.append('id', city);
        formData.append('phonenumber', phonenumber);
        if(password != '' && password_confirmation != '') {
            formData.append('password', password);
            formData.append('password_confirmation', password_confirmation);
        }
        formData.append('id', rol);
        if(myDropzone.getAcceptedFiles()[0] != undefined && exist_new_image) formData.append('file', myDropzone.getAcceptedFiles()[0]);

        var message= translations['message_user'];
        
        $.ajax({
            url: url_user,
            headers: authorization(true),
            type: method_form,
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
                        html: default_prefix + message,
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
                    $("#form_site input[type='email']").val('')
                    $("#form_site select").val('')
                    Dropzone.forElement("div#dropzoneFiles").removeAllFiles(true)
                    $("#submit_btn").removeAttr('disabled')
                },
                400: function(data) {
                    $('#loading_modal').modal('hide')
                    var response = data.responseText
                    var errors = JSON.parse(response)
                    var all_errors = errors.message

                    $('form .well').html('')
                    for (var indice in all_errors) {
                        $('form .well').append('<p>'+all_errors[indice]+'</p>')
                    }
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
        });
    } else {
    	$('form .well').css('display', 'block');
        $('form .well').html(translations['message_marker_images_no_form']);
    }

});

function hideSection() {
	$('form .well').css('display', 'none');
	$('form .well').html('');
}

function validId() {
	var url = location.href.split('/');
	var id = parseInt(url.pop());
	var value = false;

	if(Number.isInteger(id)) {
		value = true;
	}

	return value
}

function getProvinces() {
	$.ajax({
        url: location.origin+"/api/provinces/country/67",
        type: "GET",
        success: function(data) {
            $.each(data.message, function(i, item) {
                $('#state').append($('<option>', {
                    value: item.id,
                    text : item.name
                }));
            });
        }
    })	
}

function getRoles() {
	 $.ajax({
        url: location.origin+"/api/roles",
        type: "GET",
        success: function(data) {
            $.each(data.message, function(i, item) {
                $('#rol').append($('<option>', {
                    value: item.id,
                    text : item.description
                }));
            });
        }
    })
}

function getCity(id, city) {
	$.ajax({
        url: location.origin+"/api/cities/province/"+id,
        type: "GET",
        success: function(data) {
            //$('#city').html(firstName)
            $.each(data.message, function(i, item) {
                $('#city').append($('<option>', {
                    value: item.id,
                    text : item.name
                }));
            });
            $('#city').val(city);
        }
    })
}