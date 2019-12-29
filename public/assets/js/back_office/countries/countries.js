$('#loading_modal').modal('show')
$(document).ready(function() {
    $('#setLanguage').trigger('click')
    $('html>head>title').html('Travelestt | '+translations['countries'])
    $('#page-title h1').html(translations['countries'])
    setBootstrapTable('countries')
    $('#btn_add').attr('href',location.origin+'/'+$('html').attr('lang')+'/'+translations['url_panel']+'/'+translations['url_new_country']);
    $.ajax({
        async: false,
        url: location.origin+"/api/countries",
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept-Language': $('html').attr('lang'),
            'Authorization': 'Bearer '+localStorage.getItem("travelesttAccess")
        },
        statusCode: {
            200: function(data) {
                for (var i = 0; i < data.message.length; i++) {
                    let country = data.message[i]
                    $('#table_content').bootstrapTable('insertRow',{
                        index: country.id,
                        row: {
                            id : country.id,
                            iso: country.iso,
                            name: country.name,
                            actions: permissionsEdit(location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_edit_country']+'/'+country.id) + '<a target="_blank" href="'+location.origin+'/'+$('html').attr('lang')+'/'+translations['url_panel']+'/'+translations['url_countries']+'/'+country.id+'/'+translations['url_provinces']+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-search"></i></a><a target="_blank" href="'+location.origin+'/'+$('html').attr('lang')+'/'+translations['url_panel']+'/'+translations['url_countries']+'/'+country.id+'/'+translations['url_cities']+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-search"></i></a>'
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
});

function permissionsEdit(url) {
    if($('#val_edit').val() == 1) {
        return '<a target="_blank" href="'+url+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-pencil"></i></a>'
    } else {
        return '<button class="btn btn-warning btn_edit_row sep_button_panel" disabled><i class="ti-pencil"></i></button>'
    }
}