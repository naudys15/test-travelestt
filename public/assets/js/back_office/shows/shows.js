$('#loading_modal').modal('show')
$('#setLanguage').trigger('click')
$('html>head>title').html('Travelestt | '+translations['shows'])
$('#page-title h1').html(translations['shows'])
var url = window.location.href
var urlRequest = window.location.origin+'/api/shows'
var split = url.split('/')
var setTitleToCity = false
if (url.includes(translations['url_cities'])) {
    urlRequest += '/city/'+split[6]
    setTitleToCity = true
}
var urlAdd = ''
var update_disable = ''
if (url.includes(translations['url_cities'])) {
    urlAdd += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+split[6]+'/'+translations['url_new_show']
} else {
    urlAdd += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_new_show']
}
var urlEdit = ''
if (url.includes(translations['url_cities'])) {
    urlEdit += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+split[6]+'/'+translations['url_edit_show']+'/'
} else {
    urlEdit += location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_edit_show']+'/'
}
$(document).ready(function () {
    setBootstrapTable('shows')
    $('#btn_add').attr('href',urlAdd);
    $.ajax({
        async: false,
        url: urlRequest,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                let shows = data.message
                for (var i = 0; i < shows.length; i++) {
                    if (setTitleToCity) {
                        $('html>head>title').html('Travelestt | '+translations['shows']+' | '+shows[i].location.city.name)
                        $('#page-title h1').html(translations['shows']+' | '+shows[i].location.city.name)
                    }
                    let a = false
                    if (language == 'es') {
                        if (shows[i].translations.hasOwnProperty('es')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+shows[i].location.city.slug+'/'+translations['url_shows']+'/'+shows[i].slug+'">'+shows[i].translations.es.name+'</a></div>'
                            a = true
                        }
                    }
                    if (language == 'en') {
                        if (shows[i].translations.hasOwnProperty('en')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+shows[i].location.city.slug+'/'+translations['url_shows']+'/'+shows[i].slug+'">'+shows[i].translations.en.name+'</a></div>'
                            a = true
                        }
                    }
                    if (language == 'fr') {
                        if (shows[i].translations.hasOwnProperty('fr')) {
                            var nombre = '<div class="d-flex justify-content-center"><a target="_blank" href="'+ location.origin+'/'+language+'/'+shows[i].location.city.slug+'/'+translations['url_shows']+'/'+shows[i].slug+'">'+shows[i].translations.fr.name+'</a></div>'
                            a = true
                        }
                    }
                    var status
                    var outstanding
                    permissionsEdit()
                    if (shows[i].status == 1) {
                        status = '<div class="d-flex justify-content-center"><input class="sw-checked status" type="checkbox" show="'+shows[i].id+'" checked></div>'
                    } else {
                        status = '<div class="d-flex justify-content-center"><input class="sw-checked status" type="checkbox" show="'+shows[i].id+'"></div>'
                    }
                    if (shows[i].outstanding == 1) {
                        outstanding = '<div class="d-flex justify-content-center"><input class="sw-checked outstanding" type="checkbox" show="'+shows[i].id+'" checked></div>'
                    } else {
                        outstanding = '<div class="d-flex justify-content-center"><input class="sw-checked outstanding" type="checkbox" show="'+shows[i].id+'"></div>'
                    }
                    if (a) {    
                        $('#table_content').bootstrapTable('insertRow',{
                            index: shows[i].id,
                            row: {
                                id: shows[i].id,
                                name: nombre,
                                state: '<div class="d-flex justify-content-center">'+shows[i].location.province.name+'</div>',
                                city: '<div class="d-flex justify-content-center">'+shows[i].location.city.name+'</div>',
                                status: status,
                                outstanding: outstanding,
                                actions: permissionsEdit(urlEdit+shows[i].id),
                            }
                        });
                    }
                }
                var switcheries = $('.sw-checked')
                $.each(switcheries, function(a, b){
                    var switchery = new Switchery(b)
                })
                $('#table_content').on('page-change.bs.table', function () {
                    var switcheries = $('.sw-checked')
                    $.each(switcheries, function(a, b){
                        var switchery = new Switchery(b)
                    })
                })
                $('#loading_modal').modal('hide')
            },
            404: function(data) {
                $('#loading_modal').modal('hide')
            }
        }
    });
    $('.sw-checked').on('change', function(){
        let url
        if ($(this).hasClass('status')) {
            url = location.origin+'/api/shows/changeStatus/'+$(this).attr('show')
        } else if ($(this).hasClass('outstanding')) {
            url = location.origin+'/api/shows/changeOutstanding/'+$(this).attr('show')
        }
        $.ajax({
            async: false,
            url: url,
            type: 'POST',
            dataType: 'json',
            headers: authorization()
        })
    })
});

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