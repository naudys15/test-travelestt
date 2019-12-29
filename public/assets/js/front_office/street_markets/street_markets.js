setLanguage()

var slug = document.location.pathname.split('/')
slug = slug[2]

var city = imageCity(slug)

var sort_name = 0, sort_distance = 0, sort_valoration = 0
var types_array = [], types_string = ""
var sort = "", order = ""
function verifyTypes()
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
}
function verifySortAndOrder()
{
    if (sort_name > 0) {
        sort = "name"
        if (sort_name == 1) {
            order = "asc"
        } else if (sort_name == 2) {
            order = "desc"
        }
    } else if (sort_distance > 0) {
        sort = "distance"
        if (sort_distance == 1) {
            order = "asc"
        } else if (sort_distance == 2) {
            order = "desc"
        }
    } else if (sort_valoration > 0) {
        sort = "valoration"
        if (sort_valoration == 1) {
            order = "asc"
        } else if (sort_valoration == 2) {
            order = "desc"
        }
    } else {
        sort = ""
        order = ""
    }
}
function executeAjax()
{
    var request = "?language="+language+"&city="+slug
    var count_elements = 0
    if (types_string != "") {
        request += '&types='+types_string;
        count_elements++
    }
    if (sort != "" && order != "") {
        request += '&sort='+sort+'&order='+order;
        count_elements++
    }
    $.ajax({
        async: false,
        url: "/api/street_markets/search"+request,
        method: 'GET',
        statusCode: {
            200: function(data) {
                $('#to_do_content').html('');
                let count = false
                let href
                if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
                    href = location.href+'/'
                } else if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
                    href = location.href
                } else if (navigator.userAgent.toLowerCase().indexOf('opera') > -1) {

                } else if (navigator.userAgent.toLowerCase().indexOf('MSIE') > -1) {

                }
                $.each(data.message, function(a, b) {
                    if (b.translations.hasOwnProperty(language)) {
                        var folder_street_markets = routeImage('street_markets')
                        var street_market_info = '<div class="col-12 my-3"><div class="row"><div class="col-md-3"><a href="'+href+b.slug+'">';
                        street_market_info += '<div class="place_img" style="background-image: url('+folder_street_markets+'/'+b.media[0]+')"></div></a></div><div class="col-md-6 col-xl-7 py-3">';
                        count = true
                        $.each(b.translations, function(c, d) {
                            if (c == language) {
                                $.each(d, function(f, g) {
                                    if (f == 'name') {
                                        street_market_info += '<div class=""><a class="text-decoration-none d-flex" href="'+href+b.slug+'"><i class="fas fa-umbrella-beach mr-2 text-main"></i><h5 class="font-weight-bold text-dark mb-0">'+b.location.city.name+' . . . '+g+'</h5></a></div><div class=""><div class="text-secondary"><small>'
                                        if (b.valorations) {
                                            if (b.valorations.ratings == 1) {
                                                street_market_info += '<span class="valorations mr-1"><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></span>'+b.valorations.ratings+translations['opinion']+'</small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                            } else {
                                                street_market_info += '<span class="valorations mr-1"><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></span>'+b.valorations.ratings+translations['opinions']+'</small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                            }
                                        } else {
                                            street_market_info += '<span></span></small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                        }
                                    }
                                });
                            }
                        });
                        street_market_info += '<div class="col-md-3 col-xl-2 d-flex justify-content-center align-items-center"> <button type="button" data-entity="coast" data-id=""class="btn btn-outline-dark rounded-pill btn-block btn_guardar_a_mi_viaje text-secondary"><i class="far fa-bookmark mr-3"></i>'+translations['store_in_my_journeys']+'</button></div>'
                        $('#to_do_content').append(street_market_info);
                    }
                })
                if (!count) {
                    $('#to_do_content').html('');
                    $('#to_do_content').append('<div class="col-12 py-4"><div class="row d-flex justify-content-center"><div class="text-secondary text-center">'+translations['street_markets_not_found']+'</div></div></div>');
                }
            },
            404: function(data) {
                $('#to_do_content').html('');
                $('#to_do_content').append('<div class="col-12 py-4"><div class="row d-flex justify-content-center"><div class="text-secondary text-center">'+translations['street_markets_not_found']+'</div></div></div>');
            }
        }
    });
    return
}
function typesStreetMarkets() {
    var sidebar = $('#to_do_sidebar #types')
    var typeslength
    $.ajax({
        url: "/api/characteristic_entities/entity/street_market",
        method: 'GET',
        statusCode: {
            200: function(data) {
                let count = false
                sidebar.html('');
                $.each(data.message, function(a, b) {
                    var types = b.characteristics
                    typeslength = b.characteristics.length
                    var typesNightSpot=""
                    $.each(types, function(c, d) {
                        typesNightSpot += '<li><input type="checkbox" class="filters_streetmarkets form-check-input" id="types'+ c +'"  value="'+ d.name + '"><label class="adjust" for="types'+ c+ '">'+ translations[d.name] +'</label></li>'
                    })
                    sidebar.append(typesNightSpot)
                });
                types_length = typeslength
            },
            404: function(data) {
                sidebar.append('')
            }
        }
    })
}
function clearAll()
{
    types_string = ""
    types_array = []
    sort = ""
    order = ""
}
typesStreetMarkets()
executeAjax()
$(document).ready(function(){
    $('html>head>title').html(city.name+' | '+translations['street_markets'])
    $('.filters_streetmarkets').change(function(){
        clearAll()
        verifyTypes()
        verifySortAndOrder()
        executeAjax()
    })
    $('#sort_street_markets1').on('click', function(){
        sort_distance = 0;
        sort_valoration = 0;
        $('#sort_street_markets2').html(translations['sort_distance'])
        $('#sort_street_markets3').html(translations['sort_valoration'])
        if (sort_name == 0) {
            sort_name = 1;
            $('#sort_street_markets1').html(translations['sort_by_name_asc'])
        } else if (sort_name == 1) {
            sort_name = 2;
            $('#sort_street_markets1').html(translations['sort_by_name_desc'])
        } else if (sort_name == 2) {
            sort_name = 0;
            $('#sort_street_markets1').html(translations['sort_name'])
        }
        clearAll()
        verifyTypes()
        verifySortAndOrder()
        executeAjax()
    })
    $('#sort_street_markets2').on('click', function(){
        sort_name = 0
        sort_valoration = 0

        $('#sort_street_markets1').html(translations['sort_name'])
        $('#sort_street_markets3').html(translations['sort_valoration'])
        if (sort_distance == 0) {
            sort_distance = 1
            $('#sort_street_markets2').html(translations['sort_by_distance_asc'])
        } else if (sort_distance == 1) {
            sort_distance = 2
            $('#sort_street_markets2').html(translations['sort_by_distance_desc'])
        } else if (sort_distance == 2) {
            sort_distance = 0
            $('#sort_street_markets2').html(translations['sort_distance'])
        }
        clearAll()
        verifyTypes()
        verifySortAndOrder()
        executeAjax()
    })
    $('#sort_street_markets3').on('click', function(){
        sort_name = 0
        sort_distance = 0
        $('#sort_street_markets1').html(translations['sort_name'])
        $('#sort_street_markets2').html(translations['sort_distance'])
        if (sort_valoration == 0) {
            sort_valoration = 1
            $('#sort_street_markets3').html(translations['sort_by_valoration_asc'])
        } else if (sort_valoration == 1) {
            sort_valoration = 2
            $('#sort_street_markets3').html(translations['sort_by_valoration_desc'])
        } else if (sort_valoration == 2) {
            sort_valoration = 0
            $('#sort_street_markets3').html(translations['sort_valoration'])
        }
        clearAll()
        verifyTypes()
        verifySortAndOrder()
        executeAjax()
    })
    $('#clear_filters').on('click', function(){
        $('.filters_streetmarkets').prop('checked', false);
        clearAll()
        verifyTypes()
        verifySortAndOrder()
        executeAjax()
    })
})