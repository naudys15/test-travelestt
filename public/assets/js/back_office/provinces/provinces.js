$('#loading_modal').modal('show')
var url = window.location.href
var subEntity = url.split('/')
var idCountry = parseInt(subEntity[6])
var urlRequest = location.origin+"/api/provinces/"
if (Number.isInteger(idCountry)) {
    urlRequest += "country/"+idCountry
}
$(document).ready(function() {
    $('#setLanguage').trigger('click')
    setBootstrapTable('provinces')
    $('#btn_add').attr('href',location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_countries']+'/'+idCountry+'/'+translations['url_new_province']);
    $.ajax({
        async: false,
        url: urlRequest,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                for (var i = 0; i < data.message.length; i++) {
                    $('html>head>title').html('Travelestt | '+data.message[i].country.name+' | '+translations['provinces'])
                    $('#page-title h1').html(data.message[i].country.name+' | '+translations['provinces'])
                    let province = data.message[i]
                    $('#table_content').bootstrapTable('insertRow',{
                        index: province.id,
                        row: {
                            id : province.id,
                            name: province.name,
                            actions: permissionsEdit(location.origin+'/'+$('html').attr('lang')+'/'+translations['url_panel']+'/'+translations['url_countries']+'/'+idCountry+'/'+translations['url_edit_province']+'/'+province.id)+ '<a target="_blank" href="'+location.origin+'/'+$('html').attr('lang')+'/'+translations['url_panel']+'/'+translations['url_provinces']+'/'+province.id+'/'+translations['url_cities']+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-search"></i></a>'
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
                $('html>head>title').html('Travelestt | '+translations['provinces'])
                $('#page-title h1').html(translations['provinces'])
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