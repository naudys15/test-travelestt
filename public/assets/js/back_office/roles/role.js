$('#loading_modal').modal('show')

$('form .well').css('display', 'none');
$('form .well').html('');

var url = location.href.split('/');
var id = parseInt(url.pop());

if (validId()) {
$.ajax({
        url: location.origin+"/api/roles/"+id,
        type: 'GET',
        dataType: 'json',
        headers: authorization(true),
        statusCode: {
            200: function(data) {
                // console.log(data)
                let resource = data.message
                $('input[name="name"]').val(resource.name);
                $('input[name="description"]').val(resource.description);
         
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
        $('#loading_modal').modal('hide')
    })
}

$("#form_site").on('submit',function (e) {
    e.preventDefault();
    e.stopPropagation();
    var method_form = $("#form_site").attr('method');
    var name = $("#name").val();
    var description = $("#description").val();
    var default_prefix = '';
	var url_role = $("#form_site").attr('action');

    if (name != undefined && description != undefined) {
        $("#submit_btn").attr('disabled','disabled');
        
        if(validId()) {
	    	default_prefix = translations['message_result_update']+' ';
	    	method_form = 'PUT'
	    } else {
		    default_prefix = translations['message_result']+' ';
		} 		

        var formData = {
        	'name': name, 
        	'description': description
        }

        var message= translations['message_role'];
        
        $.ajax({
            url: url_role,
            headers: authorization(true),
            type: method_form,
            dataType: 'json',
            data: formData,
            beforeSend: function(data) {
                $('#loading_modal').modal('show')
            },
            statusCode: {
                200: function(data) {
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

                    $("#submit_btn").removeAttr('disabled')
                },
                400: function(data) {
                    $('#loading_modal').modal('hide')
                    var response = data.responseText
                    var errors = JSON.parse(response)
                    var all_errors = errors.message

                    //  console.log(all_errors);
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

function validId() {
	var url = location.href.split('/');
	var id = parseInt(url.pop());
	var value = false;

	if(Number.isInteger(id)) {
		value = true;
	}

	return value

}