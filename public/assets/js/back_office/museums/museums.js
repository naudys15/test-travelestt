$('#loading_modal').modal('show')
$('#setLanguage').trigger('click')
$('html>head>title').html('Travelestt | '+translations['museums'])
$('#page-title h1').html(translations['museums'])
var url = window.location.href
var urlRequest = window.location.origin+'/api/museums'
var split = url.split('/')
var setTitleToCity = false
if (url.includes(translations['url_cities'])) {
    urlRequest += '/city/'+split[6]
    setTitleToCity = true
}
var urlAdd = ''
var update_disable = ''
if (url.includes(translations['url_cities'])) {
    urlAdd += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+split[6]+'/'+translations['url_new_museum']
} else {
    urlAdd += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_new_museum']
}
var urlEdit = ''
if (url.includes(translations['url_cities'])) {
    urlEdit += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+split[6]+'/'+translations['url_edit_museum']+'/'
} else {
    urlEdit += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_edit_museum']+'/'
}
$(document).ready(function () {
    setBootstrapTable('museums')
    $('#btn_add').attr('href',urlAdd);
    $.ajax({
        async: false,
        url: urlRequest,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                let museums = data.message
                for (var i = 0; i < museums.length; i++) {
                    if (setTitleToCity) {
                        $('html>head>title').html('Travelestt | '+translations['museums']+' | '+museums[i].location.city.name)
                        $('#page-title h1').html(translations['museums']+' | '+museums[i].location.city.name)
                    }
                    let a = false
                    if (language == 'es') {
                        if (museums[i].translations.hasOwnProperty('es')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+museums[i].location.city.slug+'/'+translations['url_museums']+'/'+museums[i].slug+'">'+museums[i].translations.es.name+'</a></div>'
                            a = true
                        }
                    }
                    if (language == 'en') {
                        if (museums[i].translations.hasOwnProperty('en')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+museums[i].location.city.slug+'/'+translations['url_museums']+'/'+museums[i].slug+'">'+museums[i].translations.en.name+'</a></div>'
                            a = true
                        }
                    }
                    if (language == 'fr') {
                        if (museums[i].translations.hasOwnProperty('fr')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+museums[i].location.city.slug+'/'+translations['url_museums']+'/'+museums[i].slug+'">'+museums[i].translations.fr.name+'</a></div>'
                            a = true
                        }
                    }
                    var status
                    var outstanding
                    permissionsEdit()
                    if (museums[i].status == 1) {
                        status = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked status" type="checkbox" museum="'+museums[i].id+'" checked></div>'
                    } else {
                        status = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked status" type="checkbox" museum="'+museums[i].id+'"></div>'
                    }
                    if (museums[i].outstanding == 1) {
                        outstanding = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked outstanding" type="checkbox" museum="'+museums[i].id+'" checked></div>'
                    } else {
                        outstanding = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked outstanding" type="checkbox" museum="'+museums[i].id+'"></div>'
                    }
                    if (a) {    
                        $('#table_content').bootstrapTable('insertRow',{
                            index: museums[i].id,
                            row: {
                                id: museums[i].id,
                                name: nombre,
                                state: '<div class="d-flex justify-content-center">'+museums[i].location.province.name+'</div>',
                                city: '<div class="d-flex justify-content-center">'+museums[i].location.city.name+'</div>',
                                status: status,
                                outstanding: outstanding,
                                actions: permissionsEdit(urlEdit+museums[i].id), 
                            }
                        });
                    }
                }
                $('#table_content').on('page-change.bs.table', function () {
                    changeSwitchery()
                })
                $('#loading_modal').modal('hide')
            },
            404: function(data) {
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
            url = location.origin+'/api/museums/changeStatus/'+$(this).attr('museum')
        } else if ($(this).hasClass('outstanding')) {
            url = location.origin+'/api/museums/changeOutstanding/'+$(this).attr('museum')
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
