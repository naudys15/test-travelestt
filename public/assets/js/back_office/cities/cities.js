$('#loading_modal').modal('show')
$('#setLanguage').trigger('click')
var url = window.location.href
var split = url.split('/')
var subEntity = split[5]
var entity = ''
var urlRequest = location.origin+"/api/cities/"
var idEntity = split[6]
var switchs = []
var id_switch = []
if (subEntity == translations['url_provinces']) {
    urlRequest += "province/"
    entity = translations['url_provinces']
} else if (subEntity == translations['url_countries']) {
    urlRequest += "country/"
    entity = translations['url_countries']
}
urlRequest += idEntity
$(document).ready(function() {
    setBootstrapTable('cities')
    $('#btn_add').attr('href',location.origin+'/'+language+'/'+translations['url_panel']+'/'+entity+'/'+idEntity+'/'+translations['url_new_city']);
    $.ajax({
        async: false,
        url: urlRequest,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                let cities = data.message
                for (var i = 0; i < cities.length; i++) {
                    if (cities[i].province == undefined) {
                        $('html>head>title').html('Travelestt | '+cities[i].country.name+' | '+translations['cities'])
                        $('#page-title h1').html(cities[i].country.name+' | '+translations['cities'])
                    } else {
                        $('html>head>title').html('Travelestt | '+cities[i].province.name+' | '+translations['cities'])
                        $('#page-title h1').html(cities[i].province.name+' | '+translations['cities'])
                    }
                    let select_of_resource
                    select_of_resource = '<select class="selectpicker col-xs-6 col-sm-4 col-md-6 col-lg-6 sep_button_panel" city="'+cities[i].id+'" data-style="btn-warning">'
                    select_of_resource += '<option>'+translations['select_resoruce']+'</option>'
                    select_of_resource += '<option value="coasts">'+translations['coasts']+'</option>'
                    select_of_resource += '<option value="festivals">'+translations['festivals']+'</option></a>'
                    select_of_resource += '<option value="museums">'+translations['museums']+'</option></a>'
                    select_of_resource += '<option value="night_spots">'+translations['night_spots']+'</option></a>'
                    select_of_resource += '<option value="points_of_interest">'+translations['points_of_interest']+'</option></a>'
                    select_of_resource += '<option value="routes">'+translations['routes']+'</option></a>'
                    select_of_resource += '<option value="street_markets">'+translations['street_markets']+'</option></a>'
                    select_of_resource += '</select>'
                    if (cities[i].outstanding == 1) {
                        outstanding = '<div class="d-flex justify-content-center"><input class="sw-checked outstanding" type="checkbox" city="'+cities[i].id+'" checked></div>'
                    } else {
                        outstanding = '<div class="d-flex justify-content-center"><input class="sw-checked outstanding" type="checkbox" city="'+cities[i].id+'"></div>'
                    }
                    if (cities[i].top_destination == 1) {
                        top_destination = '<div class="d-flex justify-content-center"><input class="sw-checked top_destination" type="checkbox" city="'+cities[i].id+'" checked></div>'
                    } else {
                        top_destination = '<div class="d-flex justify-content-center"><input class="sw-checked top_destination" type="checkbox" city="'+cities[i].id+'"></div>'
                    }
                    $('#table_content').bootstrapTable('insertRow',{
                        index: cities[i].id,
                        row: {
                            id : cities[i].id,
                            name: '<div class="d-flex justify-content-center">'+cities[i].name+'</div>',
                            outstanding: outstanding,
                            top_destination: top_destination,
                            // actions: '<a href="'+location.origin+'/'+language+'/'+translations['url_panel']+'/'+entity+'/'+idEntity+'/'+translations['url_edit_city']+'/'+cities[i].id+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-pencil"></i></a><a href="'+location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+cities[i].id+'/'+translations['url_coasts']+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-search"></i></a><a href="'+location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+cities[i].id+'/'+translations['url_festivals']+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-search"></i></a><a href="'+location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+cities[i].id+'/'+translations['url_museums']+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-search"></i></a><a href="'+location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+cities[i].id+'/'+translations['url_points_of_interest']+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-search"></i></a><a href="'+location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+cities[i].id+'/'+translations['url_night_spots']+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-search"></i></a><a href="'+location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+cities[i].id+'/'+translations['url_routes']+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-search"></i></a><a href="'+location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+cities[i].id+'/'+translations['url_street_markets']+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-search"></i></a>'
                            actions: permissionsEdit(location.origin+'/'+language+'/'+translations['url_panel']+'/'+entity+'/'+idEntity+'/'+translations['url_edit_city']+'/'+cities[i].id) + select_of_resource
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
                $('#table_content').on('page-change.bs.table', function () {
                    selectResource()
                    changeSwitchery()
                })
                $('#loading_modal').modal('hide')
            },
            404: function(data) {
                $('html>head>title').html('Travelestt | '+translations['cities'])
                $('#page-title h1').html(translations['cities'])
                $('#loading_modal').modal('hide')
            }
        }
    });
    selectResource()
    changeSwitchery()
});

function selectResource() {
    $('select').selectpicker()
    $('select').on('change', function(){
        if ($(this).val() != translations['select_resoruce']) {
            let url = location.origin+'/'+language+'/'+translations['url_panel']+'/'+translations['url_cities']+'/'+$(this).attr('city')+'/'
            if ($(this).val() == 'coasts') {
                url += translations['url_coasts']
            } else if ($(this).val() == 'festivals') {
                url += translations['url_festivals']
            } else if ($(this).val() == 'museums') {
                url += translations['url_museums']
            } else if ($(this).val() == 'night_spots') {
                url += translations['url_night_spots']
            } else if ($(this).val() == 'points_of_interest') {
                url += translations['url_points_of_interest']
            } else if ($(this).val() == 'routes') {
                url += translations['url_routes']
            } else if ($(this).val() == 'street_markets') {
                url += translations['url_street_markets']
            }
            window.open(url, '_blank')
        }
    })
}

function changeSwitchery() {
    var switcheries = $('.sw-checked')
    $.each(switcheries, function(a, b){
        var switchery = new Switchery(b)
    })
    $('.sw-checked').on('change', function(){
        // console.log('cambio en switch')
        let url
        if ($(this).hasClass('top_destination')) {
            url = location.origin+'/api/cities/changeTopDestination/'+$(this).attr('city')
        } else if ($(this).hasClass('outstanding')) {
            url = location.origin+'/api/cities/changeOutstanding/'+$(this).attr('city')
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
        return '<a href="'+url+'" class="btn btn-warning btn_edit_row sep_button_panel"><i class="ti-pencil"></i></a>'
    } else {
        return '<button class="btn btn-warning btn_edit_row sep_button_panel" disabled><i class="ti-pencil"></i></disabled></button>'
    }
}

