setLanguage()
var slug = document.location.pathname.split('/')
slug = slug[2]
var city = imageCity(slug)
var sort_name = 0, sort_distance = 0, sort_valoration = 0
var types_sand_array = [], types_sand_string = ""
var extras_array = [], extras_string = ""
var stamps_array = [], stamps_string = ""
var types_array = [], types_string = ""
var levels_array = [], levels_string = ""
var sort = "", order = ""
var characteristics = []
function verifyTypesAndlevels()
{
    for(let j = 0; j < types_length; j++) {
        var type = '#types' + j.toString()
        if($(type).prop('checked')){
            types_array.push($(type).val())
        }
    }
    for (var i = 0; i < types_array.length; i++) {
        if (i == (types_array.length - 1)) {
            types_string += types_array[i]
        } else {
            types_string += types_array[i]+','
        }
    }
    for(let j = 0; j < levels_length; j++) {
        var level = '#levels' + j.toString()
        if($(level).prop('checked')){
            levels_array.push($(level).val())
        }
    }
    for (var i = 0; i < levels_array.length; i++) {
        if (i == (levels_array.length - 1)) {
            levels_string += levels_array[i]
        } else {
            levels_string += levels_array[i]+','
        }
    }
}
function verifySortAndOrder()
{
    if (sort_name > 0) {
        sort = "name";
        if (sort_name == 1) {
            order = "asc";
        } else if (sort_name == 2) {
            order = "desc";
        }
    } else if (sort_distance > 0) {
        sort = "distance";
        if (sort_distance == 1) {
            order = "asc";
        } else if (sort_distance == 2) {
            order = "desc";
        }
    } else if (sort_valoration > 0) {
        sort = "valoration";
        if (sort_valoration == 1) {
            order = "asc";
        } else if (sort_valoration == 2) {
            order = "desc";
        }
    } else {
        sort = "";
        order = "";
    }
}
function typesLevelsRoute() 
{
    var sidebar_types = $('#to_do_sidebar #types')
    var sidebar_levels = $('#to_do_sidebar #levels')
    var typeslength
    var levelslength
    $.ajax({
        async: false,
        url: "/api/characteristic_entities/entity/route",
        method: 'GET',
        statusCode: {
            200: function(data) {
                sidebar_types.html('');
                sidebar_levels.html('');
                $.each(data.message, function(a, b) {                    
                    if(b.name == 'types') {
                        var types = b.characteristics
                        typeslength = b.characteristics.length
                        var typesRoutes = ""
                        $.each(types, function(c, d) {
                            typesRoutes += '<li><input type="checkbox" class="filters_types form-check-input" id="types'+c+'" value="'+d.name+'"><label class="adjust" for="types'+c+'">'+translations[d.name]+'</label></li>'
                        })
                        sidebar_types.append(typesRoutes)
                    }
                    if(b.name == 'level') {
                        var levels = b.characteristics
                        levelslength = b.characteristics.length
                        var levelsRoutes = ""
                        $.each(levels, function(c, d) {
                            levelsRoutes += '<li><input type="checkbox" class="filters_levels form-check-input" id="levels'+c+'" value="'+d.name+'"><label class="adjust" for="levels'+c+'">'+translations[d.name]+'</label></li>'
                        })
                        sidebar_levels.append(levelsRoutes)
                    }
                });
                types_length = typeslength
                levels_length = levelslength
            },
            404: function(data) {
                sidebar.append('')
            }
        }
    })
}
function executeAjax()
{
    var request = "?language="+language+"&city="+slug;
    if (types_string != "") {
        request += '&types='+types_string;
    }
    if (levels_string != "") {
        request += '&levels='+levels_string;
    }
    if (sort != "" && order != "") {
        request += '&sort='+sort+'&order='+order;
    }
    $.ajax({
        url: "/api/routes/search"+request,
        method: 'GET',
        statusCode: {
            200: function(data) {
                $('#to_do_content').html('');
                let count = false
                let href
                let last_chart = location.href.charAt(location.href.length-1)
                if (last_chart != '/') {
                    href = location.href+'/'
                } else {
                    href = location.href
                }
                $.each(data.message, function(a, b) {
                    if (b.translations.hasOwnProperty(language)) {
                        var folder_routes = routeImage('routes')
                        var route_info = '<div class="col-12 my-3"><div class="row"><div class="col-md-3"><a href="'+href+b.slug+'">';
                        route_info += '<div class="place_img" style="background-image: url('+folder_routes+'/'+b.media[0]+')"></div></a></div><div class="col-md-6 col-xl-7 py-3">';
                        count = true
                        $.each(b.translations, function(c, d) {
                            if (c == language) {
                                $.each(d, function(f, g) {
                                    if (f == 'name') {
                                        route_info += '<div class=""><a class="text-decoration-none d-flex" href="'+href+b.slug+'"><i class="fas fa-umbrella-beach mr-2 text-main"></i><h5 class="font-weight-bold text-dark mb-0">'+b.location.city.name+' . . . '+g+'</h5></a></div><div class=""><div class="text-secondary"><small>'
                                        if (b.valorations) {
                                            if (b.valorations.ratings == 1) {
                                                route_info += '<span class="valorations mr-1"><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></span>'+b.valorations.ratings+translations['opinion']+'</small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                            } else {
                                                route_info += '<span class="valorations mr-1"><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></span>'+b.valorations.ratings+translations['opinions']+'</small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                            }
                                        } else {
                                            route_info += '<span></span></small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                        }
                                    }
                                });
                            }
                        });
                        route_info += '<div class="col-md-3 col-xl-2 d-flex justify-content-center align-items-center"> <button type="button" data-entity="coast" data-id=""class="btn btn-outline-dark rounded-pill btn-block btn_guardar_a_mi_viaje text-secondary"><i class="far fa-bookmark mr-3"></i>'+translations['store_in_my_journeys']+'</button></div>'
                        $('#to_do_content').append(route_info);
                    }
                })
                if (!count) {
                    $('#to_do_content').html('');
                    $('#to_do_content').append('<div class="col-12 py-4"><div class="row d-flex justify-content-center"><div class="text-secondary text-center">'+translations['routes_not_found']+'</div></div></div>');
                }
            },
            404: function(data) {
                $('#to_do_content').html('');
                $('#to_do_content').append('<div class="col-12 py-4"><div class="row d-flex justify-content-center"><div class="text-secondary text-center">'+translations['routes_not_found']+'</div></div></div>');
            }
        }
    });
}
function clearAll()
{
    types_string = "";
    types_array = [];
    levels_string = "";
    levels_array = [];
    sort = "";
    order = "";
}
typesLevelsRoute()
executeAjax()
$(document).ready(function(){
    $('html>head>title').html(city.name+' | '+translations['routes'])
    $('.filters_types').change(function(){
        clearAll()
        verifyTypesAndlevels()
        verifySortAndOrder()
        executeAjax()
    });
    $('.filters_levels').change(function(){
        clearAll()
        verifyTypesAndlevels()
        verifySortAndOrder()
        executeAjax()
    });
    $('#sort_routes1').on('click', function(){
        sort_distance = 0;
        sort_valoration = 0;
        $('#sort_routes2').html(translations['sort_distance'])
        $('#sort_routes3').html(translations['sort_valoration'])
        if (sort_name == 0) {
            sort_name = 1;
            $('#sort_routes1').html(translations['sort_by_name_asc'])
        } else if (sort_name == 1) {
            sort_name = 2;
            $('#sort_routes1').html(translations['sort_by_name_desc'])
        } else if (sort_name == 2) {
            sort_name = 0;
            $('#sort_routes1').html(translations['sort_name'])
        }
        clearAll()
        verifyTypesAndlevels()
        verifySortAndOrder()
        executeAjax()
    })
    $('#sort_routes2').on('click', function(){
        sort_name = 0;
        sort_valoration = 0;
        $('#sort_routes1').html(translations['sort_name'])
        $('#sort_routes3').html(translations['sort_valoration'])
        if (sort_distance == 0) {
            sort_distance = 1;
            $('#sort_routes2').html(translations['sort_by_distance_asc'])
        } else if (sort_distance == 1) {
            sort_distance = 2;
            $('#sort_routes2').html(translations['sort_by_distance_desc'])
        } else if (sort_distance == 2) {

            sort_distance = 0;
            $('#sort_routes2').html(translations['sort_distance'])
        }
        clearAll()
        verifyTypesAndlevels()
        verifySortAndOrder()
        executeAjax()
    })
    $('#sort_coasts3').on('click', function(){
        sort_name = 0;
        sort_distance = 0;
        $('#sort_routes1').html(translations['sort_name'])
        $('#sort_routes2').html(translations['sort_distance'])
        if (sort_valoration == 0) {
            sort_valoration = 1;
            $('#sort_routes3').html(translations['sort_by_valoration_asc'])
        } else if (sort_valoration == 1) {
            sort_valoration = 2;
            $('#sort_routes3').html(translations['sort_by_valoration_desc'])
        } else if (sort_valoration == 2) {
            sort_valoration = 0;
            $('#sort_routes3').html(translations['sort_valoration'])
        }
        clearAll()
        verifyTypesAndlevels()
        verifySortAndOrder()
        executeAjax()
    })
    $('#clear_filters').on('click', function(){
        $('.filters_types').prop('checked', false)
        $('.filters_levels').prop('checked', false)
        clearAll()
        verifyTypesAndlevels()
        verifySortAndOrder()
        executeAjax()
    })
})