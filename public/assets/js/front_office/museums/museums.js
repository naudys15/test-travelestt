setLanguage()
var slug = document.location.pathname.split('/')
slug = slug[2]
var city = imageCity(slug)
var sort_name = 0, sort_distance = 0, sort_valoration = 0
var types_array = [], types_string = ""
var sort = "", order = ""
function verifyTypes()
{
    for (let j = 0; j < types_length; j++) {
        var type = '#types' + j.toString()
        if ($(type).prop('checked')) {
            types_array.push($(type).val())
        }
    }
    for (let i = 0; i < types_array.length; i++) {
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
function typesMuseum() {
    var sidebar = $('#to_do_sidebar #types')
    var typesLength
    $.ajax({
        async: false,
        url: "/api/characteristic_entities/entity/museum",
        method: 'GET',
        statusCode: {
            200: function(data) {
                characteristics = data.message
                sidebar.html('')
                $.each(characteristics, function(a, b) {
                    var types = b.characteristics
                    typesLength = b.characteristics.length
                    var typesMuseum = ""
                    $.each(types, function(c, d) {
                        typesMuseum += '<li><input type="checkbox" class="filters_museums form-check-input" id="types'+c+'" value="'+d.name+'"><label class="adjust" for="types'+c+'">'+translations[d.name]+'</label></li>'
                    })
                    sidebar.append(typesMuseum)
                });
                types_length = typesLength
            },
            404: function(data) {
                sidebar.append('')
            }
        }
    })
}
function executeAjax()
{
    var request = "?language="+language+"&city="+slug
    if (types_string != "") {
        request += '&types='+types_string;
    }
    if (sort != "" && order != "") {
        request += '&sort='+sort+'&order='+order;
    }
    $.ajax({
        url: "/api/museums/search"+request,
        method: 'GET',
        statusCode: {
            200: function(data) {
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
                        var folder_museums = routeImage('museums')
                        var museum_info = '<div class="col-12 my-3"><div class="row"><div class="col-md-3"><a href="'+href+b.slug+'">';
                        museum_info += '<div class="place_img" style="background-image: url('+folder_museums+'/'+b.media[0]+')"></div></a></div><div class="col-md-6 col-xl-7 py-3">';
                        count = true
                        $.each(b.translations, function(c, d) {
                            if (c == language) {
                                $.each(d, function(f, g) {
                                    if (f == 'name') {
                                        museum_info += '<div class=""><a class="text-decoration-none d-flex" href="'+href+b.slug+'"><i class="fas fa-umbrella-beach mr-2 text-main"></i><h5 class="font-weight-bold text-dark mb-0">'+b.location.city.name+' . . . '+g+'</h5></a></div><div class=""><div class="text-secondary"><small>'
                                        if (b.valorations) {
                                            if (b.valorations.ratings == 1) {
                                                museum_info += '<span class="valorations mr-1"><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></span>'+b.valorations.ratings+translations['opinion']+'</small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                            } else {
                                                museum_info += '<span class="valorations mr-1"><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></span>'+b.valorations.ratings+translations['opinions']+'</small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                            }
                                        } else {
                                            museum_info += '<span></span></small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                        }
                                    }
                                });
                            }
                        });
                        museum_info += '<div class="col-md-3 col-xl-2 d-flex justify-content-center align-items-center"> <button type="button" data-entity="coast" data-id=""class="btn btn-outline-dark rounded-pill btn-block btn_guardar_a_mi_viaje text-secondary"><i class="far fa-bookmark mr-3"></i>'+translations['store_in_my_journeys']+'</button></div>'
                        $('#to_do_content').append(museum_info);
                    }
                })
                if (!count) {
                    $('#to_do_content').html('');
                    $('#to_do_content').append('<div class="col-12 py-4"><div class="row d-flex justify-content-center"><div class="text-secondary text-center">'+translations['museums_not_found']+'</div></div></div>');
                }
            },
            404: function(data) {
                $('#to_do_content').html('');
                $('#to_do_content').append('<div class="col-12 py-4"><div class="row d-flex justify-content-center"><div class="text-secondary text-center">'+translations['museums_not_found']+'</div></div></div>');
            }
        }
    });
    return
}
function clearAll()
{
    types_string = ""
    types_array = []
    sort = ""
    order = ""
}
typesMuseum()
executeAjax()
$(document).ready(function(){
    $('html>head>title').html(city.name+' | '+translations['museums'])
    $('.filters_museums').change(function(){
        clearAll()
        verifyTypes()
        verifySortAndOrder()
        executeAjax()
    })
    $('#sort_museums1').on('click', function(){
        sort_distance = 0
        sort_valoration = 0
        $('#sort_museums2').html(translations['sort_distance'])
        $('#sort_museums3').html(translations['sort_valoration'])
        if (sort_name == 0) {
            sort_name = 1;
            $('#sort_museums1').html(translations['sort_by_name_asc'])
        } else if (sort_name == 1) {
            sort_name = 2;
            $('#sort_museums1').html(translations['sort_by_name_desc'])
        } else if (sort_name == 2) {
            sort_name = 0;
            $('#sort_museums1').html(translations['sort_name'])
        }
        clearAll()
        verifyTypes()
        verifySortAndOrder()
        executeAjax()
    })
    $('#sort_museums2').on('click', function(){
        sort_name = 0
        sort_valoration = 0

        $('#sort_museums1').html(translations['sort_name'])
        $('#sort_museums3').html(translations['sort_valoration'])
        if (sort_distance == 0) {
            sort_distance = 1
            $('#sort_museums2').html(translations['sort_by_distance_asc'])
        } else if (sort_distance == 1) {
            sort_distance = 2
            $('#sort_museums2').html(translations['sort_by_distance_desc'])
        } else if (sort_distance == 2) {
            sort_distance = 0
            $('#sort_museums2').html(translations['sort_distance'])
        }
        clearAll()
        verifyTypes()
        verifySortAndOrder()
        executeAjax()
    })
    $('#sort_museums3').on('click', function(){
        sort_name = 0
        sort_distance = 0
        $('#sort_museums1').html(translations['sort_name'])
        $('#sort_museums2').html(translations['sort_distance'])
        if (sort_valoration == 0) {
            sort_valoration = 1
            $('#sort_museums3').html(translations['sort_by_valoration_asc'])
        } else if (sort_valoration == 1) {
            sort_valoration = 2
            $('#sort_museums3').html(translations['sort_by_valoration_desc'])
        } else if (sort_valoration == 2) {
            sort_valoration = 0
            $('#sort_museums3').html(translations['sort_valoration'])
        }
        clearAll()
        verifyTypes()
        verifySortAndOrder()
        executeAjax()
    })
    $('#clear_filters').on('click', function(){
        $('.filters_museums').prop('checked', false)
        clearAll()
        verifyTypes()
        verifySortAndOrder()
        executeAjax()
    })
})