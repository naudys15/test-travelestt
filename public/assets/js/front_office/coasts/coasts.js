setLanguage()
var slug = document.location.pathname.split('/')
slug = slug[2]
var city = imageCity(slug)
var sort_name = 0, sort_distance = 0, sort_valoration = 0
var types_array = [], types_string = ""
var extras_array = [], extras_string = ""
var stamps_array = [], stamps_string = ""
var sort = "", order = ""
var characteristics = []
var no_data=false;
function verifyTypesAndExtrasAndStamps()
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

    for (let j = 0; j < extras_length; j++) {
        var extra = '#extras' + j.toString()
        if ($(extra).prop('checked')) {
            extras_array.push($(extra).val())
        }
    }
    for (let i = 0; i < extras_array.length; i++) {
        if (i == (extras_array.length - 1)) {
            extras_string += extras_array[i]
        } else {
            extras_string += extras_array[i]+','
        }
    }

    for (let j = 0; j < stamps_length; j++) {
        var stamp = '#stamps' + j.toString()
        if ($(stamp).prop('checked')) {
            stamps_array.push($(stamp).val())
        }
    }
    for (let i = 0; i < stamps_array.length; i++) {
        if (i == (stamps_array.length - 1)) {
            stamps_string += stamps_array[i]
        } else {
            stamps_string += stamps_array[i]+','
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
function typesExtrasAndStampsCoast() 
{
    var sidebar_types = $('#to_do_sidebar #types_sand')
    var sidebar_extras = $('#to_do_sidebar #extras')
    var sidebar_stamps = $('#to_do_sidebar #stamps')
    var typeslength
    var extraslength
    var stampslength
    $.ajax({
        async: false,
        url: "/api/characteristic_entities/entity/coast",
        method: 'GET',
        statusCode: {
            200: function(data) {
                characteristics = data.message
                sidebar_types.html('');
                sidebar_extras.html('');
                sidebar_stamps.html('');
                $.each(characteristics, function(a, b) {                    
                    if (b.name == 'type_sand') {
                        var types = b.characteristics
                        typeslength = b.characteristics.length
                        var typesCoasts = ""
                        $.each(types, function(c, d) {
                            typesCoasts += '<li><input type="checkbox" class="filters_coasts form-check-input" id="types'+c+'" value="'+d.name+'"><label class="adjust" for="types'+c+'">'+translations[d.name]+'</label></li>'
                        })
                        sidebar_types.append(typesCoasts)
                    }
                    if (b.name == 'extras') {
                        var extras = b.characteristics
                        extraslength = b.characteristics.length
                        var extrasCoasts = ""
                        $.each(extras, function(c, d) {
                            extrasCoasts += '<li><input type="checkbox" class="filters_coasts form-check-input" id="extras'+c+'" value="'+d.name+'"><label class="adjust" for="extras'+c+'">'+translations[d.name]+'</label></li>'
                        })
                        sidebar_extras.append(extrasCoasts)
                    }
                    if (b.name == 'stamps') {
                        var stamps = b.characteristics
                        stampslength = b.characteristics.length
                        var stampsCoasts = ""
                        $.each(stamps, function(c, d) {
                            stampsCoasts += '<li><input type="checkbox" class="filters_coasts form-check-input" id="stamps'+c+'" value="'+d.name+'"><label class="adjust" for="stamps'+c+'">'+translations[d.name]+'</label></li>'
                        })
                        sidebar_stamps.append(stampsCoasts)
                    }
                });
                types_length = typeslength
                extras_length = extraslength
                stamps_length = stampslength
            },
            404: function(data) {
                sidebar.append('')
            }
        }
    })
}
function executeAjax(offset = 0, scrolling = false)
{
    var request = "?language="+language+"&city="+slug
    if (types_string != "") {
        request += '&type_sand='+types_string
    }
    if (extras_string != "") {
        request += '&extras='+extras_string
    }
    if (stamps_string != "") {
        request += '&stamps='+stamps_string
    }
    if (sort != "" && order != "") {
        request += '&sort='+sort+'&order='+order
    }
    $.ajax({
        url: "/api/coasts/search"+request+"&limit=5&offset="+offset,
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
                        var folder_coasts = routeImage('coasts')
                        var beach_info = '<div class="col-12 my-3"><div class="row"><div class="col-md-3"><a href="'+href+b.slug+'">';
                        beach_info += '<div class="place_img" style="background-image: url('+folder_coasts+'/'+b.media[0]+')"></div></a></div><div class="col-md-6 col-xl-7 py-3">';
                        count = true
                        $.each(b.translations, function(c, d) {
                            if (c == language) {
                                $.each(d, function(f, g) {
                                    if (f == 'name') {
                                        beach_info += '<div class=""><a class="text-decoration-none d-flex" href="'+href+b.slug+'"><i class="fas fa-umbrella-beach mr-2 text-main"></i><h5 class="font-weight-bold text-dark mb-0">'+b.location.city.name+' . . . '+g+'</h5></a></div><div class=""><div class="text-secondary"><small>'
                                        if (b.valorations) {
                                            if (b.valorations.ratings == 1) {
                                                beach_info += '<span class="valorations mr-1"><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></span>'+b.valorations.ratings+translations['opinion']+'</small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                            } else {
                                                beach_info += '<span class="valorations mr-1"><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></span>'+b.valorations.ratings+translations['opinions']+'</small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                            }
                                        } else {
                                            beach_info += '<span></span></small></div></div><p class="text-secondary place_info">'+ d.short_description+'</div>';
                                        }
                                    }
                                });
                            }
                        });
                        beach_info += '<div class="col-md-3 col-xl-2 d-flex justify-content-center align-items-center"> <button type="button" data-entity="coast" data-id=""class="btn btn-outline-dark rounded-pill btn-block btn_guardar_a_mi_viaje text-secondary"><i class="far fa-bookmark mr-3"></i>'+translations['store_in_my_journeys']+'</button></div>'
                        $('#to_do_content').append(beach_info);
                    }
                })
                if (!count) {
                    if (!scrolling) {
                        $('#to_do_content').html('');
                        $('#to_do_content').append('<div class="col-12 py-4"><div class="row d-flex justify-content-center"><div class="text-secondary text-center">'+translations['coasts_not_found']+'</div></div></div>');
                    } else {
                        no_data = true;
                    }
                }
            },
            404: function(data) {
                $('#to_do_content').html('');
                $('#to_do_content').append('<div class="col-12 py-4"><div class="row d-flex justify-content-center"><div class="text-secondary text-center">'+translations['coasts_not_found']+'</div></div></div>');
            }
        }
    });
}
function clearAll()
{
    types_string = ""
    types_array = []
    extras_string = ""
    extras_array = []
    stamps_string = ""
    stamps_array = []
    sort = ""
    order = ""
}
typesExtrasAndStampsCoast()
executeAjax()
$(document).ready(function(){
    $('html>head>title').html(city.name+' | '+translations['coasts'])
    $('.filters_coasts').change(function(){
        clearAll()
        verifyTypesAndExtrasAndStamps()
        verifySortAndOrder()
        executeAjax()
    });
    $('#sort_coasts1').on('click', function(){
        sort_distance = 0
        sort_valoration = 0
        $('#sort_coasts2').html(translations['sort_distance'])
        $('#sort_coasts3').html(translations['sort_valoration'])
        if (sort_name == 0) {
            sort_name = 1;
            $('#sort_coasts1').html(translations['sort_by_name_asc'])
        } else if (sort_name == 1) {
            sort_name = 2;
            $('#sort_coasts1').html(translations['sort_by_name_desc'])
        } else if (sort_name == 2) {
            sort_name = 0;
            $('#sort_coasts1').html(translations['sort_name'])
        }
        clearAll()
        verifyTypesAndExtrasAndStamps()
        verifySortAndOrder()
        executeAjax()
    })
    $('#sort_coasts2').on('click', function(){
        sort_name = 0
        sort_valoration = 0
        $('#sort_coasts1').html(translations['sort_name'])
        $('#sort_coasts3').html(translations['sort_valoration'])
        if (sort_distance == 0) {
            sort_distance = 1
            $('#sort_coasts2').html(translations['sort_by_distance_asc'])
        } else if (sort_distance == 1) {
            sort_distance = 2
            $('#sort_coasts2').html(translations['sort_by_distance_desc'])
        } else if (sort_distance == 2) {
            sort_distance = 0
            $('#sort_coasts2').html(translations['sort_distance'])
        }
        clearAll()
        verifyTypesAndExtrasAndStamps()
        verifySortAndOrder()
        executeAjax()
    })
    $('#sort_coasts3').on('click', function(){
        sort_name = 0
        sort_distance = 0
        $('#sort_coasts1').html(translations['sort_name'])
        $('#sort_coasts2').html(translations['sort_distance'])
        if (sort_valoration == 0) {
            sort_valoration = 1
            $('#sort_coasts3').html(translations['sort_by_valoration_asc'])
        } else if (sort_valoration == 1) {
            sort_valoration = 2
            $('#sort_coasts3').html(translations['sort_by_valoration_desc'])
        } else if (sort_valoration == 2) {
            sort_valoration = 0
            $('#sort_coasts3').html(translations['sort_valoration'])
        }
        clearAll()
        verifyTypesAndExtrasAndStamps()
        verifySortAndOrder()
        executeAjax()
    })
    $('#clear_filters').on('click', function(){
        $('.filters_coasts').prop('checked', false)
        clearAll()
        verifyTypesAndExtrasAndStamps()
        verifySortAndOrder()
        executeAjax()
    })
    $(window).scroll(function(){
        // console.log('hice scroll');
        // var offset=$(this).offset();
        var windowHeight = $(window).height()
        var windowScrollTop = $(window).scrollTop()
        var documentHeight = $(document).height()
        // console.log(windowScrollTop)
        // console.log(windowHeight)
        // console.log(Math.round(windowHeight+windowScrollTop))
        // console.log(documentHeight)
        if (Math.round(windowHeight+windowScrollTop)+1 == documentHeight) {
            // console.log('estoy al final del documento');
            if (!no_data) {
                let offset_items = $('#to_do_content').children().length;
                // console.log('offset items');
                executeAjax(offset_items, true);
            }
    
        }
    })
})

