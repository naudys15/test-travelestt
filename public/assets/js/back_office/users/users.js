$('#loading_modal').modal('show')
$('#setLanguage').trigger('click')
var urlAdd = location.origin+'/'+$('html').attr('lang')+'/'+translations['url_panel']+'/'+translations['url_new_user']
var urlEdit = location.origin+'/'+$('html').attr('lang')+'/'+translations['url_panel']+'/'+translations['url_edit_user']+'/'
var urlPerm = location.origin+'/'+$('html').attr('lang')+'/'+translations['url_panel']+'/'+translations['url_permissions_user']+'/'
$(document).ready(function () {
    $('#btn_add').attr('href',urlAdd);
	$.ajax({
        async: false,
        url: location.origin+"/api/users",
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                for (var i = 0; i < data.message.length; i++) {
                    $('#table_content').bootstrapTable('insertRow',{
                        index: i,
                        row: {
                            id : data.message[i].id,
                            firstname: data.message[i].firstname,
                            lastname: data.message[i].lastname,
                            email: data.message[i].email,
                            rol: data.message[i].role.description, 
                            actions: permissionsEdit(urlEdit+data.message[i].id)+
                            '<a href="'+urlPerm+data.message[i].id+'" class="btn btn-success btn_edit_row"><i class="ti-unlock"></i></a>'
                        }
                    });
                }
                $('#table_content tr').each(function(){
                    if ($('th:nth-child(2)').length > 0) {
                        $('th:nth-child(2)').css('display', 'none')
                    }
                    if ($('td:nth-child(2)').length > 0) {
                        $('td:nth-child(2)').css('display', 'none')
                    }
                })
                $('#loading_modal').modal('hide')
            },
            404: function(data) {
                $('#loading_modal').modal('hide')
            }
        }
    });
})

var $table = $('#table_content'),	$remove = $('#btn_delete_rows');

$table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
    $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
});

var lang = $('html').attr('lang')
switch (lang) {
	case 'es':
    	var columns = ['Id', 'Primer Nombre', 'Segundo Nombre', 'Correo Electrónico', 'Rol'];
    	break;
    case 'en':
    	var columns = ['Id', 'First Name', 'Last Name', 'Email', 'Rol'];
    	break;
    case 'fr':
    	var columns = ['Id', 'Prenom', 'Le nom', 'Email', 'Rôle'];
    	break;
}

$table.bootstrapTable({
    pagination: true,
    search: true,
    columns: [
        {
            field: 'check',
            title: ''
        }, {
            field: 'id',
            title: 'Id',
        }, {
            field: 'firstname',
            title: columns[1]
        }, {
            field: 'lastname',
            title: columns[2]
        }, {
            field: 'email',
            title: columns[3]
        },  {
            field: 'rol',
            title: columns[4]
        },{
            field: 'actions',
            title: ''
        }
    ],
})

function eraseUser()
{
    setTranslations();
    var message_delete = translations['message_result_delete']+' '+translations['message_user'];
    
    var selected_data = [];
    var selected_rows = $('#table_content tr.selected');
    selected_rows.each(function(){
        selected_data.push($(this).children('td:nth-child(2)').html());
    })
    for (var i = 0; i < selected_data.length; i ++) {
        var delete_url = '/api/users/' + selected_data[i];
        $.ajax({
            url: delete_url,
            headers: authorization(true),
            beforeSend: function(data) {
                $('#loading_modal').modal('show')
            },
            type: "DELETE",
            success: function(respuesta)
            {
                $('#loading_modal').modal('hide')
                var $table = $('#table_content');
                var $remove = $('#btn_delete_rows');
                var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
                    return row.id
                });
                $table.bootstrapTable('remove', {
                    field: 'id',
                    values: ids
                });
                $('#table_content tr').each(function(){
                    if ($('th:nth-child(2)').length > 0) {
                        $('th:nth-child(2)').css('display', 'none')
                    }
                    if ($('td:nth-child(2)').length > 0) {
                        $('td:nth-child(2)').css('display', 'none')
                    }
                })
                $remove.prop('disabled', true);
                $.niftyNoty({
                    type: "success",
                    container: "floating",
                    html: message_delete,
                    closeBtn: true,
                    floating: {
                        position: "bottom-right",
                        animationIn: "jellyIn",
                        animationOut: "fadeOut"
                    },
                    focus: true,
                    timer: 2500
                });
            },
            error: function(respuesta)
            {
                $('#loading_modal').modal('hide')
                $.niftyNoty({
                    type: "danger",
                    container: "floating",
                    html: 'Error',
                    closeBtn: true,
                    floating: {
                        position: "bottom-right",
                        animationIn: "jellyIn",
                        animationOut: "fadeOut"
                    },
                    focus: true,
                    timer: 2500
                });
            }
        });
    }
}

function permissionsEdit(url) {
    if($('#val_edit').val() == 1) {
        return '<a href="'+url+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-pencil"></i></a>'
    } else {
        return '<button class="btn btn-warning btn_edit_row sep_button_panel" disabled><i class="ti-pencil"></i></button>'
    }
}
