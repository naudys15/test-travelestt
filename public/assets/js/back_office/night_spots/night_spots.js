$('#loading_modal').modal('show')
$('#setLanguage').trigger('click')
$('html>head>title').html('Travelestt | '+translations['night_spots'])
$('#page-title h1').html(translations['night_spots'])
var url = window.location.href
var urlRequest = window.location.origin+'/api/night_spots'
var split = url.split('/')
var setTitleToCity = false
if (url.includes(translations['url_cities'])) {
    urlRequest += '/city/'+split[6]
    setTitleToCity = true
}
var urlAdd = ''
var update_disable = ''
if (url.includes(translations['url_cities'])) {
    urlAdd += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+split[6]+'/'+translations['url_new_night_spot']
} else {
    urlAdd += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_new_night_spot']
}
var urlEdit = ''
if (url.includes(translations['url_cities'])) {
    urlEdit += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+split[6]+'/'+translations['url_edit_night_spot']+'/'
} else {
    urlEdit += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_edit_night_spot']+'/'
}
$(document).ready(function () {
    setBootstrapTable('night_spots')
    //$('#btn_delete_rows').before("<a href='"+urlAdd+"' class='sep_button_panel'><button class='btn btn-success' >"+translations['add']+"</button></a>")
    $('#btn_add').attr('href',urlAdd);
    $.ajax({
        async: false,
        url: urlRequest,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                let night_spots = data.message
                for (var i = 0; i < night_spots.length; i++) {
                    if (setTitleToCity) {
                        $('html>head>title').html('Travelestt | '+translations['night_spots']+' | '+night_spots[i].location.city.name)
                        $('#page-title h1').html(translations['night_spots']+' | '+night_spots[i].location.city.name)
                    }
                    let a = false
                    if (language == 'es') {
                        if (night_spots[i].translations.hasOwnProperty('es')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+night_spots[i].location.city.slug+'/'+translations['url_night_spots']+'/'+night_spots[i].slug+'">'+night_spots[i].translations.es.name+'</a></div>'
                            a = true
                        }
                    }
                    if (language == 'en') {
                        if (night_spots[i].translations.hasOwnProperty('en')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+night_spots[i].location.city.slug+'/'+translations['url_night_spots']+'/'+night_spots[i].slug+'">'+night_spots[i].translations.en.name+'</a></div>'
                            a = true
                        }
                    }
                    if (language == 'fr') {
                        if (night_spots[i].translations.hasOwnProperty('fr')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+night_spots[i].location.city.slug+'/'+translations['url_night_spots']+'/'+night_spots[i].slug+'">'+night_spots[i].translations.fr.name+'</a></div>'
                            a = true
                        }
                    }
                    var status
                    var outstanding
                    permissionsEdit()
                    if (night_spots[i].status == 1) {
                        status = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked status" type="checkbox" night_spot="'+night_spots[i].id+'" checked></div>'
                    } else {
                        status = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked status" type="checkbox" night_spot="'+night_spots[i].id+'"></div>'
                    }
                    if (night_spots[i].outstanding == 1) {
                        outstanding = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked outstanding" type="checkbox" night_spot="'+night_spots[i].id+'" checked></div>'
                    } else {
                        outstanding = '<div class="d-flex justify-content-center"><input ' + update_disable + ' class="sw-checked outstanding" type="checkbox" night_spot="'+night_spots[i].id+'"></div>'
                    }
                    if (a) {    
                        $('#table_content').bootstrapTable('insertRow',{
                            index: night_spots[i].id,
                            row: {
                                id: night_spots[i].id,
                                name: nombre,
                                state: '<div class="d-flex justify-content-center">'+night_spots[i].location.province.name+'</div>',
                                city: '<div class="d-flex justify-content-center">'+night_spots[i].location.city.name+'</div>',
                                status: status,
                                outstanding: outstanding,
                                actions: permissionsEdit(urlEdit+night_spots[i].id),
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
            url = location.origin+'/api/night_spots/changeStatus/'+$(this).attr('night_spot')
        } else if ($(this).hasClass('outstanding')) {
            url = location.origin+'/api/night_spots/changeOutstanding/'+$(this).attr('night_spot')
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