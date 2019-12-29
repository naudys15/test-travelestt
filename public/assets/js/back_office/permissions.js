function permissions() {
	var output = '<div class="form-group"><div class="row">'
    var permissions
    var ranges

	$.ajax({
        async: false,
        url: location.origin+"/api/submodules",
        type: 'GET',
        dataType: 'json',
        statusCode: {
            200: function(data) {
                permissions = data.message
            },
            404: function(data) {
                error()
            }
        }
    });
    $.ajax({
        async: false,
        url: location.origin+"/api/rangesubmodules",
        type: 'GET',
        dataType: 'json',
        statusCode: {
            200: function(data) {
                ranges = data.message
            },
            404: function(data) {
                error()
            }
        }
    });

    $.ajax({
        async: false,
        url: location.origin+"/api/modules",
        type: 'GET',
        dataType: 'json',
        statusCode: {
            200: function(data) {
                var modules = data.message
                var ran_name = []
                var ran_id = []

                if(ranges == undefined) {
                    return
                }

                if(permissions == undefined) {
                    return
                }
                                
                $.each(ranges, function(j, range) {
                    ran_id[j] = range.id
                    ran_name[j] = translations[range.name]
                })

                $.each(modules, function(i, mod){
                    output += '<div class="col-md-12""><table class="table"><thead><tr colspan="4" class="text-center"><strong>'+translations[mod.name]+'</strong></tr><tr><th width="50%"></th>' 
                    output += '<th class="text-center">'+ran_name[0]+'</th><th class="text-center">'+ran_name[1]+'</th><th class="text-center">'+ran_name[2]+'</th></thead><tbody>'
                    $.each(permissions, function(k, perm){
                        if(mod.id == perm.module.id) {
                            output += '<tr><td width="50%">'+translations[perm.name]+'</td><td><div class="radio d-flex justify-content-center"><input class="magic-radio" type="radio" value="'+perm.id+'_'+ran_id[0]+'" name="perm_'+perm.id+'" id="perm_'+perm.id+'_'+ran_id[0]+'"><label for="perm_'+perm.id+'_'+ran_id[0]+'"></label></td><td><div class="radio d-flex justify-content-center"><input type="radio" class="magic-radio" value="'+perm.id+'_'+ran_id[1]+'" name="perm_'+perm.id+'" id="perm_'+perm.id+'_'+ran_id[1]+'"><label for="perm_'+perm.id+'_'+ran_id[1]+'"></div></td><td><div class="radio d-flex justify-content-center"><input type="radio" class="magic-radio" value="'+perm.id+'_'+ran_id[2]+'" name="perm_'+perm.id+'" id="perm_'+perm.id+'_'+ran_id[2]+'"><label for="perm_'+perm.id+'_'+ran_id[2]+'"></div></td></tr>'
                        }
                    });
                });
                output += '</tbody></table><div></div></div>'
                $('#permissions').after(output)
            },
            404: function(data) {
                error()
            }
        }
    });
}

function error() {
    var html = '<div class="col-lg-12 text-center"><strong>'+translations['permissions_not_found']+'</strong></div>'
    $('#permissions').before(html)
    $('form-group').html('')
    $('table').empty('')
    $('#loading_modal').modal('hide')
}

function deletePermissions(route, id, submodule) {
    var data = {'submodule': submodule}
    $.ajax({
        async: false,
        url: location.origin+"/api/" + route + id,
        type: 'DELETE',
        dataType: 'json',
        data: data,
        headers: authorization(true),
        statusCode: {
            200: function(data) {
            },
            404: function(data) {
                $('#loading_modal').modal('hide')
            }
        }
    });
}

function addPermissions(route, id, submodule, range) {
    var data = {'submodule': submodule, 'range': range}
    $.ajax({
        async: false,
        url: location.origin+"/api/"+ route + id,
        type: 'POST',
        dataType: 'json',
        data: data,
        headers: authorization(true),
        statusCode: {
            200: function(data) {
                $('#loading_modal').modal('hide')
            },
            404: function(data) {
                $('#loading_modal').modal('hide')
            }
        }
    });
}
