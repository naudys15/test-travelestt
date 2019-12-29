$('#loading_modal').modal('show')
$('#setLanguage').trigger('click')
$('html>head>title').html('Travelestt | '+translations['festivals'])
$('#page-title h1').html(translations['festivals'])
var url = window.location.href
var urlRequest = window.location.origin+'/api/festivals'
var split = url.split('/')
var setTitleToCity = false
if (url.includes(translations['url_cities'])) {
    urlRequest += '/city/'+split[6]
    setTitleToCity = true
}
var urlAdd = ''
var update_disable = ''
if (url.includes(translations['url_cities'])) {
    urlAdd += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+split[6]+'/'+translations['url_new_festival']
} else {
    urlAdd += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_new_festival']
}
var urlEdit = ''
if (url.includes(translations['url_cities'])) {
    urlEdit += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+split[6]+'/'+translations['url_edit_festival']+'/'
} else {
    urlEdit += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_edit_festival']+'/'
}
$(document).ready(function () {
    setBootstrapTable('festivals')
    $('#btn_add').attr('href',urlAdd);
    $.ajax({
        async: false,
        url: urlRequest,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                let festivals = data.message
                for (var i = 0; i < festivals.length; i++) {
                    if (setTitleToCity) {
                        $('html>head>title').html('Travelestt | '+translations['festivals']+' | '+festivals[i].location.city.name)
                        $('#page-title h1').html(translations['festivals']+' | '+festivals[i].location.city.name)
                    }
                    let a = false
                    if (language == 'es') {
                        if (festivals[i].translations.hasOwnProperty('es')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+festivals[i].location.city.slug+'/'+translations['url_festivals']+'/'+festivals[i].slug+'">'+festivals[i].translations.es.name+'</a></div>'
                            a = true
                        }
                    }
                    if (language == 'en') {
                        if (festivals[i].translations.hasOwnProperty('en')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+festivals[i].location.city.slug+'/'+translations['url_festivals']+'/'+festivals[i].slug+'">'+festivals[i].translations.en.name+'</a></div>'
                            a = true
                        }
                    }
                    if (language == 'fr') {
                        if (festivals[i].translations.hasOwnProperty('fr')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+festivals[i].location.city.slug+'/'+translations['url_festivals']+'/'+festivals[i].slug+'">'+festivals[i].translations.fr.name+'</a></div>'
                            a = true
                        }
                    }
                    var status
                    var outstanding
                    permissionsEdit()
                    if (festivals[i].status == 1) {
                        status = '<div class="d-flex justify-content-center"><input class="sw-checked status" type="checkbox" festival="'+festivals[i].id+'" checked></div>'
                    } else {
                        status = '<div class="d-flex justify-content-center"><input class="sw-checked status" type="checkbox" festival="'+festivals[i].id+'"></div>'
                    }
                    if (festivals[i].outstanding == 1) {
                        outstanding = '<div class="d-flex justify-content-center"><input class="sw-checked outstanding" type="checkbox" festival="'+festivals[i].id+'" checked></div>'
                    } else {
                        outstanding = '<div class="d-flex justify-content-center"><input class="sw-checked outstanding" type="checkbox" festival="'+festivals[i].id+'"></div>'
                    }
                    if (a) {    
                        $('#table_content').bootstrapTable('insertRow',{
                            index: festivals[i].id,
                            row: {
                                id: festivals[i].id,
                                name: nombre,
                                state: '<div class="d-flex justify-content-center">'+festivals[i].location.province.name+'</div>',
                                city: '<div class="d-flex justify-content-center">'+festivals[i].location.city.name+'</div>',
                                status: status,
                                outstanding: outstanding,
                                actions: permissionsEdit(urlEdit+festivals[i].id),
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
            url = location.origin+'/api/festivals/changeStatus/'+$(this).attr('festival')
        } else if ($(this).hasClass('outstanding')) {
            url = location.origin+'/api/festivals/changeOutstanding/'+$(this).attr('festival')
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
            return '<div class="d-flex justify-content-center"><button disabled class="btn btn-warning btn_edit_row"><i class="ti-pencil"></i></button></div>'
        } else {
            update_disable = 'disabled'
        }
    }
}