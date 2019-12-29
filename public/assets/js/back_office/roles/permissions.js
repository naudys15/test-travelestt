$('#loading_modal').modal('show')
$('#setLanguage').trigger('click')
$(document).ready(function(){
    if(validId() != null) {
    	var id = validId()
        permissions()
    	$.ajax({
            async: false,
            url: location.origin+"/api/roles/"+ id,
            type: 'GET',
            dataType: 'json',
            headers: authorization(),
            statusCode: {
                200: function(data) {
                    var perm_role = data.message.permission
                    $.each(perm_role, function(i, perm){
                        var field=''
                        field = "perm_" + perm.id_submodule+'_'+perm.id_range
                        $('#'+field).prop('checked', true)
                    })
                    $('#loading_modal').modal('hide')

                    $('input[type=radio]').change(function(e) {
                        $('#loading_modal').modal('show')
                        var perm = $(this).val().split("_")
                        var result = perm_role.find(p => { 
                            return p.id_submodule == perm[0]
                        })

                        if(result) {
                            deletePermissions('roles/revokePermission/', id, perm[0])
                        }
                        addPermissions('roles/addPermission/', id, perm[0], perm[1])
                        perm_role.push({
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
        error()
    }   
});
function validId() {
	var url = location.href.split('/');
	var id = parseInt(url.pop());
	var value = null;

	if(Number.isInteger(id)) {
		value = id;
	}
	return value
}