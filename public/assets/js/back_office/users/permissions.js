$('#loading_modal').modal('show')
$('#setLanguage').trigger('click');
var id = validId()
$(document).ready(function(){
    if(validId() != null) {
        
        permissions()
    	
        $.ajax({
            async: false,
            url: location.origin+"/api/users/"+ id,
            type: 'GET',
            dataType: 'json',
            headers: authorization(),
            statusCode: {
                200: function(data) {
                    var perm_user = data.message.permission
                    $.each(perm_user, function(i, perm){
                        var field=''
                        field = "perm_" + perm.id_submodule+'_'+perm.id_range
                        $('#'+field).prop('checked', true)
                    })
                    $('#loading_modal').modal('hide')

                    $('input[type=radio]').change(function(e) {
                        $('#loading_modal').modal('show')
                        var perm = $(this).val().split("_")
                        var result = perm_user.find(p => { 
                            return p.id_submodule == perm[0]
                        })
                        if(result) {
                            deletePermissions('users/revokePermission/', id, perm[0])
                        }
                        addPermissions('users/addPermission/', id, perm[0], perm[1])
                        perm_user.push({
                            'id_submodule': perm[0],
                            'id_range': perm[1]
                        })
                    })
                },
                404: function(data) {
                    $('#loading_modal').modal('hide')
                }
            }
        });

    } else {
            $('#loading_modal').modal('hide')
    }   
})


function validId() {
	var url = location.href.split('/');
	var id = parseInt(url.pop());
	var value = null;

	if(Number.isInteger(id)) {
		value = id;
	}
	return value
}