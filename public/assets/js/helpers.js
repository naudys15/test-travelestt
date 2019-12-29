//Devuelve palabra con primera letra en mayuscula
function upperPrimary(string) {
	if(string.length > 0) {
		return string.charAt(0).toUpperCase() + string.slice(1)
	}
}

//Devuelve la primera letra en mayuscula de un string
function initialString (string) {
	// console.log(string)
	if(string) {
	  let ini = string.substr(0,1);
	  ini = ini.toUpperCase();
	  
	  return ini;
	}
}

//Devuelve ruta de las imagenes segun carpeta
function routeImage(folder) {
	return location.origin+'/assets/images/' + folder + '/'
}

//Encripta un valor
function encrypt(value) {
    const val = window.btoa(value); 
    return val;
}

//Desencripta un valor
function descrypt(value) {
    const val = window.atob(value);
    return val;
}

// Función para manejo de la respuesta al crear comentarios
function handlerCommentResponse()
{
    $.ajax({
        type: $('#comment_form').attr('action'),
        url: $('#comment_form').attr('href'),
        headers: authorization(),
        data: {
            _token: $('input[name="_token"]').val(),
            content: $('#comment_form *[name="content"]').val(),
            title: $('#comment_form *[name="title"]').val(),
            rating: 5,
            recaptcha: grecaptcha.getResponse()
        },
        dataType: 'json',
        statusCode: {
            201: function() {
                $('#comment_form').css('display', 'none')
            },
            400 : function (error) {
                let errors = error.responseJSON.message
                $('#comment_form .border-danger').removeClass('border-danger')
                $('#comment_form .text-danger').remove()
                $.each(errors, function(key, value) {
                    let item = $('#comment_form *[name="'+key+'"]')
                    if (item.length > 0) {
                        item.addClass('border-danger')
                        item.after('<small class="help-block text-danger">'+value+'</small>')
                    }
                });
            }
        }
    })
}