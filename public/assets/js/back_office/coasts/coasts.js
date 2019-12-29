$('#loading_modal').modal('show')
$('#setLanguage').trigger('click')
$('html>head>title').html('Travelestt | '+translations['coasts'])
$('#page-title h1').html(translations['coasts'])
var url = location.href
var urlRequest = location.origin+'/api/coasts'
var split = url.split('/')
var setTitleToCity = false
if (url.includes(translations['url_cities'])) {
    urlRequest += '/city/'+split[6]
    setTitleToCity = true
}
var urlAdd = ''
var update_disable = ''
if (url.includes(translations['url_cities'])) {
    urlAdd += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+split[6]+'/'+translations['url_new_coast']
} else {
    urlAdd += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_new_coast']
}
var urlEdit = ''
if (url.includes(translations['url_cities'])) {
    urlEdit += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+split[6]+'/'+translations['url_edit_coast']+'/'
} else {
    urlEdit += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_edit_coast']+'/'
}
$(document).ready(function () {
    setBootstrapTable('coasts')
    $('#btn_add').attr('href',urlAdd);
    $.ajax({
        async: false,
        url: urlRequest,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function (data) {
                let coasts = data.message
                for (var i = 0; i < coasts.length; i++) {
                    if (setTitleToCity) {
                        $('html>head>title').html('Travelestt | '+translations['coasts']+' | '+coasts[i].location.city.name)
                        $('#page-title h1').html(translations['coasts']+' | '+coasts[i].location.city.name)
                    }
                    let a = false
                    if (language == 'es') {
                        if (coasts[i].translations.hasOwnProperty('es')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+coasts[i].location.city.slug+'/'+translations['url_coasts']+'/'+coasts[i].slug+'">'+coasts[i].translations.es.name+'</a></div>'
                            a = true
                        }
                    }
                    if (language == 'en') {
                        if (coasts[i].translations.hasOwnProperty('en')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+coasts[i].location.city.slug+'/'+translations['url_coasts']+'/'+coasts[i].slug+'">'+coasts[i].translations.en.name+'</a></div>'
                            a = true
                        }
                    }
                    if (language == 'fr') {
                        if (coasts[i].translations.hasOwnProperty('fr')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+coasts[i].location.city.slug+'/'+translations['url_coasts']+'/'+coasts[i].slug+'">'+coasts[i].translations.fr.name+'</a></div>'
                            a = true
                        }
                    }
                    var status
                    var outstanding
                    permissionsEdit()
                    if (coasts[i].status == 1) {
                        status = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked status" type="checkbox" coast="'+coasts[i].id+'" checked></div>'
                    } else {
                        status = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked status" type="checkbox" coast="'+coasts[i].id+'"></div>'
                    }
                    if (coasts[i].outstanding == 1) {
                        outstanding = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked outstanding" type="checkbox" coast="'+coasts[i].id+'" checked></div>'
                    } else {
                        outstanding = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked outstanding" type="checkbox" coast="'+coasts[i].id+'"></div>'
                    }
                    if (a) {
                        $('#table_content').bootstrapTable('insertRow', {
                            index: coasts[i].id,
                            row: {
                                id: coasts[i].id,
                                name: nombre,
                                state: '<div class="d-flex justify-content-center">'+coasts[i].location.province.name+'</div>',
                                city: '<div class="d-flex justify-content-center">'+coasts[i].location.city.name+'</div>',
                                status: status,
                                outstanding: outstanding,
                                actions: permissionsEdit(urlEdit+coasts[i].id),
                            }
                        });
                    }
                }
                $('#table_content').on('page-change.bs.table', function () {
                    changeSwitchery()
                })
                $('#loading_modal').modal('hide')
            },
            404: function (data) {
                $('#loading_modal').modal('hide')
            }
        }
    });
    changeSwitchery()
});

function changeSwitchery() {
    var switcheries = $('.sw-checked')
    $.each(switcheries, function(a, b){
        var switchery = new Switchery(b)
    })
    $('.sw-checked').on('change', function(){
        let url
        if ($(this).hasClass('status')) {
            url = location.origin+'/api/coasts/changeStatus/'+$(this).attr('coast')
        } else if ($(this).hasClass('outstanding')) {
            url = location.origin+'/api/coasts/changeOutstanding/'+$(this).attr('coast')
        }
        $.ajax({
            async: false,
            url: url,
            type: 'POST',
            dataType: 'json',
            headers: authorization()
        })
    })
}

function permissionsEdit(url) {
    if($('#val_edit').val() == 1) {
        if(url) {
            return '<div class="d-flex justify-content-center"><a href="'+url+'" class="btn btn-warning btn_edit_row"><i class="ti-pencil"></i></a></div>'
        } else {
            update_disable = ''
        }
    } else {
        if(url) {
            return '<div class="d-flex justify-content-center"><button class="btn btn-warning btn_edit_row" disabled><i class="ti-pencil"></i></button></div>'
        } else {
            update_disable = 'disabled'
        }
    }
}
