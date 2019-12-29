$(document).ready(function(){
    $('button[type="button"]').click(function(){
        let email = $("form input[name='email']").val()
        let _token = $("form input[name='_token']").val()
        $.ajax({
            url: location.origin+"/api/recovery_password",
            type: 'POST',
            dataType: "json",
            data: {
                email: email,
                _token: _token
            },
            statusCode: {
                200: function(data) {
                    window.location.replace(location.origin)
                },
                400: function (data) {
                    $('#form_recovery_password .has-error').removeClass('has-error')
                    $('#form_recovery_password .text-danger').remove()
                    let errors = data.responseJSON.message
                    $.each(errors, function(key, value) {
                        let item = $('#form_recovery_password *[name="'+key+'"]')
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
    })
})