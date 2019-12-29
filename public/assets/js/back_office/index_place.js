var mymap;
// var token;
var message_result;
var count_files_in_dropzone = 0;
var marker;
var markers = [];
// var day_of_schedule = [];
// var date_schedule = [];
// var opened_hours = [];
// var closed_hours = [];
// var schedule = [];
// var num_schedule = 0;
// var dates_omitted = [];
// var date_to_ommit = "";
// var type_schedule = 0;
var other_language = "";
var other_language_two = "";
var other_language_three = "";
var other_items = [];
var other_items_two = [];
var files_to_edit=[];
/**
 *  Función que permite parsear las fechas añadiendo los ceros correspondientes a la izquierda
 * @param {integer} n
 */
function addZeros(n){
    if (n <= 9) {
        return "0" + n;
    }
    return n;
}
/**
 * Función que permite buscar en la api de nominatim las direcciones, finalmente ubicando el mapa en la dirección seleccionada
 */
function addrSearch()
{
    var inp = document.getElementById("addr");
    var items = [];
    var index = 0;
    if ($('html').attr('lang') == "es") {
        other_language = "es";
        other_language_two = "en";
        other_language_three = "fr";
    } else if ($('html').attr('lang') == "en") {
        other_language = "en";
        other_language_two = "es";
        other_language_three = "fr";
    } else if ($('html').attr('lang') == "fr") {
        other_language = "fr";
        other_language_two = "es";
        other_language_three = "en";
    }
    $.ajax({
        url: 'http://nominatim.openstreetmap.org/search?format=json&q=' + inp.value,
        headers: {'Accept-Language': [other_language]},
        dataType: 'json',
        success: function(data) {
            $.each(data, function(key, val) {
                var event_click = 'chooseAddr(' + val.lat + ', ' + val.lon + ',"'+val.display_name+'","'+index+'");return false;';
                items.push(
                    "<li><a href='#' onclick='"+event_click+"'>" + val.display_name +
                    '</a></li>'
                );
                index++;
            });
            $('#results').empty();
            if (items.length != 0) {
                $('<div>', { html: translations['results_search'] }).appendTo('#results');
                $('<ul/>', {
                    'class': 'my-new-list',
                    html: items.join('')
                }).appendTo('#results');
                $('#results').css('height', '25vh');
                $('#results').css('overflow-y', 'scroll');
            } else {
                $('<p>', { html: translations['no_results_search'] }).appendTo('#results');
                $('#results').css('height', 'auto');
                $('#results').css('overflow-y', 'unset');
            }
            $.ajax({
                url: 'http://nominatim.openstreetmap.org/search?format=json&q=' + inp.value,
                headers: {'Accept-Language': [other_language_two]},
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(key, val) {
                        other_items.push(val.display_name);
                    });
                    $.ajax({
                        url: 'http://nominatim.openstreetmap.org/search?format=json&q=' + inp.value,
                        headers: {'Accept-Language': [other_language_three]},
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, val) {
                                other_items_two.push(val.display_name);
                            });
                        }
                    });
                }
            });
        }
    });
}

/**
 * Función que permite obtener la dirección a partir de una coordenada
 */
function getDirectionFromCoordinate(lat, lng)
{
    let one = "";
    let two = "";
    let three = "";
    if ($('html').attr('lang') == "es") {
        one = "es";
        two = "en";
        three = "fr";
    } else if ($('html').attr('lang') == "en") {
        one = "en";
        two = "es";
        three = "fr";
    } else if ($('html').attr('lang') == "fr") {
        one = "fr";
        two = "es";
        three = "en";
    }
    $.ajax({
        url: 'http://nominatim.openstreetmap.org/reverse?format=json&lat='+lat+'&lon='+lng+'&zoom=18&addressdetails=1',
        headers: {'Accept-Language': [one]},
        dataType: 'json',
        success: function(data) {
            var direction = data.display_name;
            if (one == "es") {
                $('.address_spanish').html(direction);
            } else if (one == "en") {
                $('.address_english').html(direction);
            } else if (one == "fr") {
                $('.address_french').html(direction);
            }
            $.ajax({
                url: 'http://nominatim.openstreetmap.org/reverse?format=json&lat='+lat+'&lon='+lng+'&zoom=18&addressdetails=1',
                headers: {'Accept-Language': [two]},
                dataType: 'json',
                success: function(data) {
                    var direction = data.display_name;
                    if (two == "es") {
                        $('.address_spanish').html(direction);
                    } else if (two == "en") {
                        $('.address_english').html(direction);
                    } else if (two == "fr") {
                        $('.address_french').html(direction);
                    }
                    $.ajax({
                        url: 'http://nominatim.openstreetmap.org/reverse?format=json&lat='+lat+'&lon='+lng+'&zoom=18&addressdetails=1',
                        headers: {'Accept-Language': [three]},
                        dataType: 'json',
                        success: function(data) {
                            var direction = data.display_name;
                            if (three == "es") {
                                $('.address_spanish').html(direction);
                            } else if (three == "en") {
                                $('.address_english').html(direction);
                            } else if (three == "fr") {
                                $('.address_french').html(direction);
                            }
                        }
                    });
                }
            });
        }
    });
}

/**
 * Función que permite mover el mapa a un punto específico
 */
function chooseAddr(lat, lng, address, index)
{
    mymap.flyTo([lat, lng], 14);
    if (marker != null) {
        var popup = translations['coordinates'] + lat + "," + lng;
        marker.setLatLng([lat, lng], {
            draggable: true
        }).bindPopup(popup).update();
    }
    if ($('html').attr('lang') == "es") {
        $('.address_spanish').html(address);
        $('.address_english').html(other_items[index]);
        $('.address_french').html(other_items_two[index]);
    } else if ($('html').attr('lang') == "en") {
        $('.address_english').html(address);
        $('.address_spanish').html(other_items[index]);
        $('.address_french').html(other_items_two[index]);
    } else if ($('html').attr('lang') == "fr") {
        $('.address_french').html(address);
        $('.address_spanish').html(other_items[index]);
        $('.address_english').html(other_items_two[index]);
    }
}
/**
 * Función que permite limpiar el input de las direcciones del mapa
 */
function clearSearch()
{
    $('#results').html('');
    $('#results').css('height', 'auto');
    $('#results').css('overflow-y', 'unset');
    $('#addr').val('');
}
/**
 * Función que permite obtener la elevación de un punto específico
 */
function getElevationFromApi(lat, lng, token)
{
    var value= $.ajax({ 
        url: 'http://open.mapquestapi.com/elevation/v1/profile?shapeFormat=raw&key='+ token +'&latLngCollection=' + lat + ',%20' + lng, 
        async: false
     }).responseJSON.elevationProfile[0].height
     return value
}

/**
 * Función que permite obtener las categorias y caracteristicas de una entidad y renderizarlas
 */
function loadCategoriesAndCharacteristics(entity)
{
    $.ajax({
        async: false,
        url: location.origin+"/api/characteristic_entities/entity/"+entity,
        type: "GET",
        success: function(data) {
            var categories = data.message
            var output = '<div class="form-group"><div class="row">'
            $.each(categories, function(i, category){
                if (categories.length == 1) {
                    output += '<div class="col-xs-12 category"><div class="col-xs-12 text-center"><label class="control-label" for="'+category.name+'">'+translations[category.name]+'</label><br></div>'
                    $.each(category.characteristics, function(j, characteristic){
                        output += '<div class="col-md-3"><div class="radio">'
                        output += '<input class="magic-radio" type="radio" id="'+category.name+'_'+characteristic.name+'" placeholder="'+characteristic.name+'" name="'+category.name+'" value="'+characteristic.name+'">'
                        output += '<label for="'+category.name+'_'+characteristic.name+'">'+translations[characteristic.name]+'</label>'
                        output += '</div></div>' 
                    })
                } else if (categories.length == 2) {
                    output += '<div class="col-xs-12 col-md-6 category"><div class="col-xs-12 text-center"><label class="control-label" for="'+category.name+'">'+translations[category.name]+'</label><br></div>'
                    $.each(category.characteristics, function(j, characteristic){
                        output += '<div class="col-md-6"><div class="'+category.render+'">'
                        output += '<input class="magic-'+category.render+' '+category.name+'" type="'+category.render+'" id="'+category.name+'_'+characteristic.name+'" placeholder="'+characteristic.name+'" name="'+category.name+'" value="'+characteristic.name+'">'
                        output += '<label for="'+category.name+'_'+characteristic.name+'">'+translations[characteristic.name]+'</label>'
                        output += '</div></div>' 
                    })
                    output += '</div>'
                } else if (categories.length == 3) {
                    output += '<div class="col-xs-12 col-md-4 category"><div class="col-xs-12 text-center"><label class="control-label" for="'+category.name+'">'+translations[category.name]+'</label><br></div>'
                    $.each(category.characteristics, function(j, characteristic){
                        output += '<div class="col-md-6"><div class="'+category.render+'">'
                        output += '<input class="magic-'+category.render+' '+category.name+'" type="'+category.render+'" id="'+category.name+'_'+characteristic.name+'" placeholder="'+characteristic.name+'" name="'+category.name+'" value="'+characteristic.name+'">'
                        output += '<label for="'+category.name+'_'+characteristic.name+'">'+translations[characteristic.name]+'</label>'
                        output += '</div></div>' 
                    })
                    output += '</div>'
                }
            })
            $('.tab-base').after(output)
        }
    })
}

var url = location.href.split('/')
var cityId = url[6]
var provinceId = undefined
if (cityId != undefined) {
    $.ajax({
        async: false,
        url: location.origin+"/api/cities/"+cityId,
        type: "GET",
        success: function(data) {
            let resourse = data.message
            provinceId = resourse.id
        }
    })
}
$.ajax({
    async: false,
    url: location.origin+"/api/provinces/country/67",
    type: "GET",
    success: function(data) {
        $.each(data.message, function(i, item) {
            $('#state').append($('<option>', {
                value: item.id,
                lat: item.latitude,
                lng: item.longitude,
                text : item.name
            }));
        });
        if (location.href.includes(translations['url_cities'])) {
            $('#state').val(provinceId)
        }
    }
})
$('#state').on('change', function(){
    if ($('#state').val() != '') {
        let firstName = $('#city option:first-child')
        $.ajax({
            async: false,
            url: location.origin+"/api/cities/province/"+$('#state').val(),
            type: "GET",
            success: function(data) {
                $('#city').html(firstName)
                $.each(data.message, function(i, item) {
                    $('<option>',{
                        value: item.id,
                        lat: item.latitude,
                        lng: item.longitude,
                        text : item.name
                    }).appendTo($('#city'));
                });
            }
        })
    }
})
if (cityId != undefined && provinceId != undefined) {
    let firstName = $('#city option:first-child')
    $.ajax({
        async: false,
        url: location.origin+"/api/cities/province/"+provinceId,
        type: "GET",
        success: function(data) {
            $('#city').html(firstName)
            $.each(data.message, function(i, item) {
                $('<option>',{
                    value: item.id,
                    lat: item.latitude,
                    lng: item.longitude,
                    text : item.name
                }).appendTo($('#city'));
            });
            if (location.href.includes(translations['url_cities'])) {
                $('#city').val(cityId)
            }
        }
    })
}
$('#city').on('change', function(){
    if ($('#state').val() != '') {
        let lat_to_fly = $('#city option:selected').attr('lat');
        let lng_to_fly = $('#city option:selected').attr('lng');
        mymap.flyTo([lat_to_fly, lng_to_fly], 14);
    }
})

$(document).ready(function ()
{
    $('#setLanguage').trigger('click')
    $('#short_information_spanish').summernote({
        height : '200px'
    });
    $('#long_information_spanish').summernote({
        height : '200px'
    });
    $('#short_information_english').summernote({
        height : '200px'
    });
    $('#long_information_english').summernote({
        height : '200px'
    });
    $('#short_information_french').summernote({
        height : '200px'
    });
    $('#long_information_french').summernote({
        height : '200px'
    });
    $('#slug').bind('input', function(){
        var input_slug = $(this).val();
        var special_characters_admitted = '-'
        var pattern = /[a-zA-Z- 0-9]/g
        var result = input_slug.match(pattern)
        var new_result = "";
        if (result != null) {
            for (var i = 0; i < result.length; i++) {
                new_result += result[i]
            }
        }
        new_result = new_result.replace(/[0-9]/g, '')
        new_result = new_result.replace(/\s/g, special_characters_admitted)
        $(this).val(new_result)
    });
    if ( $("#phonenumber")[0] ) {
        $('#phonenumber').mask('+34 999 99 99 99');
    }
    //Formato a los campos de hora de apertura y cierre
    // if ( $(".timepicker")[0] ) {
    //     $('.timepicker').timepicker();
    // }
    //Detectar tipo de horario, recurrente o esporádico
    // $('#type_event').on('change', function(){
    //     if ($(this).prop('checked')) {
    //         type_schedule = 1;
    //         $('#date_event').css('display', 'none');
    //         $('#week_day').css('display', 'block');
    //     } else {
    //         type_schedule = 0;
    //         $('#date_event').css('display', 'block');
    //         $('#week_day').css('display', 'none');
    //     }
    // });
    // $('#week_day').css('display', 'none');
    // date_to_ommit = "";
    //Inicialización del datepicker
    // var d = new Date();
    // var date_string = d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear();
    // $('#date_event_input').datepicker({
    //     showAnim: "fold",
    //     minDate: date_string,
    //     beforeShowDay: function(date) {
    //         var day = jQuery.datepicker.formatDate('dd-mm-yy', date);
    //         // return [!~$.inArray(day, dates_omitted) && (date.getDay() != 0)];
    //         return [!~$.inArray(day, dates_omitted)];
    //     },
    //     onSelect: function(date){
    //         var new_date = date.replace(/\//g, "-");
    //         date_to_ommit = new_date;
    //     }
    // });
    //Evento de guardar horario
    // $('.btn_add_schedule').on('click',function(){

    //     var disable_button = $('.btn_add_schedule').attr('disabled');

    //     if (disable_button != 'disabled') {
    //         var new_schedule = "";
    //         let opened_date = ($('#opened_hour').val()).split(' ');
    //         let closed_date = ($('#closed_hour').val()).split(' ');
    //         let split_time_one = opened_date[0].split(':');
    //         let split_time_two = closed_date[0].split(':');

    //         opened_in_military_hour = split_time_one[0]+":"+split_time_one[1]+" "+opened_date[1];
    //         var object_date = new Date("1/1/2013 " + opened_in_military_hour);
    //         opened_in_military_hour =  object_date.getHours() + ':' + ((object_date.getMinutes()<10)?"0"+object_date.getMinutes():object_date.getMinutes());

    //         closed_in_military_hour = split_time_two[0]+":"+split_time_two[1]+" "+closed_date[1];
    //         var object_date = new Date("1/1/2013 " + closed_in_military_hour);
    //         closed_in_military_hour =  object_date.getHours() + ':' + ((object_date.getMinutes()<10)?"0"+object_date.getMinutes():object_date.getMinutes());

    //         /*var split_opened_military_hour = opened_in_military_hour.split(':');
    //         var split_closed_military_hour = closed_in_military_hour.split(':');

    //         var midnight_correction = false;
    //         if (opened_in_military_hour > closed_in_military_hour && opened_date[1] == "PM" && closed_date[1] == "AM") {
    //             midnight_correction = true;
    //         }*/
    //         //if ((split_opened_military_hour[0] < split_closed_military_hour[0]) || (split_opened_military_hour[0] == split_closed_military_hour[0] && split_opened_military_hour[1] < split_closed_military_hour[1]) || midnight_correction == true) {
    //             opened_hours[num_schedule] = opened_date[0];
    //             closed_hours[num_schedule] = closed_date[0];
    //             if (type_schedule == 1) {
    //                 if ($('#day').val() == 'Lun-Dom' || $('#day').val() == 'Mon-Sun') {
    //                     for(var i = 0; i < days.length; i++) {
    //                         day_of_schedule[num_schedule] = days[i];
    //                         new_schedule = "<div class='row mar-btm schedule'><div class='col-xs-11'><span class='text-muted'>"+days[i]+", "+$('#opened_hour').val()+" - "+$('#closed_hour').val()+"</span></div><div class='col-xs-1 text-center'><a href='#' class='btn-link btn_remove_schedule' data-id='"+days[i]+"'><i class='ti-trash'></i></a></div></div>";
    //                         schedule[num_schedule] = day_of_schedule[num_schedule]+' '+opened_hours[num_schedule]+' '+closed_hours[num_schedule];
    //                         $('option[value="'+days[i]+'"]').attr('disabled','disabled');
    //                         num_schedule++;
    //                         $('.schedules').append(new_schedule);
    //                     }
    //                     var all_schedule = $('option[value="Lun-Dom"]').attr('disabled');
    //                     var all_schedule_two = $('option[value="Mon-Sun"]').attr('disabled');
    //                     if (all_schedule == undefined) {
    //                         $('option[value="Lun-Dom"]').attr('disabled', 'disabled');
    //                     }
    //                     if (all_schedule_two == undefined) {
    //                         $('option[value="Mon-Sun"]').attr('disabled', 'disabled');
    //                     }
    //                 } else {
    //                     day_of_schedule[num_schedule] = $('#day').val();
    //                     new_schedule = "<div class='row mar-btm schedule'><div class='col-xs-11'><span class='text-muted'>"+$('#day').val()+", "+$('#opened_hour').val()+" - "+$('#closed_hour').val()+"</span></div><div class='col-xs-1 text-center'><a href='#' class='btn-link btn_remove_schedule' data-id='"+$('#day').val()+"'><i class='ti-trash'></i></a></div></div>";
    //                     schedule[num_schedule] = day_of_schedule[num_schedule]+' '+opened_hours[num_schedule]+' '+closed_hours[num_schedule];
    //                     $('option[value="'+$('#day').val()+'"]').attr('disabled','disabled');
    //                     var all_schedule = $('option[value="Lun-Dom"]').attr('disabled');
    //                     var all_schedule_two = $('option[value="Mon-Sun"]').attr('disabled');
    //                     if (all_schedule == undefined) {
    //                         $('option[value="Lun-Dom"]').attr('disabled', 'disabled');
    //                     }
    //                     if (all_schedule_two == undefined) {
    //                         $('option[value="Mon-Sun"]').attr('disabled', 'disabled');
    //                     }
    //                     num_schedule++;
    //                     $('.schedules').append(new_schedule);
    //                 }
    //                 $('.btn_add_schedule').attr('disabled', 'disabled');
    //                 $('#type_event').attr('disabled', 'disabled');
    //                 $('.well-schedule').css('display', 'none');
    //                 $('.well-schedule').html('');
    //             } else if (type_schedule == 0 && $('#date_event_input').val() != '') {
    //                 date_schedule[num_schedule] = date_to_ommit;
    //                 new_schedule = "<div class='row mar-btm schedule'><div class='col-xs-11'><span class='text-muted'>"+date_to_ommit+", "+$('#opened_hour').val()+" - "+$('#closed_hour').val()+"</span></div><div class='col-xs-1 text-center'><a href='#' class='btn-link btn_remove_schedule' data-id='"+date_to_ommit+"'><i class='ti-trash'></i></a></div></div>";
    //                 schedule[num_schedule] = date_to_ommit+" "+opened_hours[num_schedule]+' '+closed_hours[num_schedule];
    //                 dates_omitted[num_schedule] = date_to_ommit;
    //                 $('#date_event_input').val('');
    //                 num_schedule++;
    //                 $('.schedules').append(new_schedule);
    //                 $('#type_event').attr('disabled', 'disabled');
    //                 $('.well-schedule').css('display', 'none');
    //                 $('.well-schedule').html('');
    //             } else if (type_schedule == 0 && $('#date_event_input').val() == '') {
    //                 $('.well-schedule').css('display', 'block');
    //                 $('.well-schedule').html(translations['message_schedule_no_date']);
    //             }
    //         /*} else {
    //             $('.well-schedule').css('display', 'block');
    //             $('.well-schedule').html(translations['message_schedule_wrong_opening_and_closing_hours']);
    //         }*/
    //     }
    // });
    //Evento al borrar un horario
    // $('.schedules').on('click','.btn_remove_schedule',function(){
    //     var result = $(this).attr('data-id');
    //     for (var i = 0; i < schedule.length; i++) {
    //         if (type_schedule == 1) {
    //             if (day_of_schedule[i] == result) {
    //                 if (day_of_schedule[i] == 'Lun-Dom' || day_of_schedule[i] == 'Mon-Sun') {
    //                     for (var j = 0; j < days.length; j++) {
    //                         $('option[value="'+days[j]+'"]').removeAttr('disabled');
    //                     }
    //                 } else {
    //                     $('option[value="'+day_of_schedule[i]+'"]').removeAttr('disabled');
    //                 }
    //                 day_of_schedule.splice(i , 1);
    //                 opened_hours.splice(i, 1);
    //                 closed_hours.splice(i, 1);
    //                 schedule.splice(i, 1);
    //                 dates_omitted.splice(i, 1);
    //                 num_schedule--;
    //             }
    //         } else {
    //             if (date_schedule[i] == result) {
    //                 date_schedule.splice(i , 1);
    //                 opened_hours.splice(i, 1);
    //                 closed_hours.splice(i, 1);
    //                 schedule.splice(i, 1);
    //                 dates_omitted.splice(i, 1);
    //                 num_schedule--;
    //             }
    //         }
    //     }
    //     if (num_schedule == 0) {
    //         $('option[value="Lun-Dom"]').removeAttr('disabled');
    //         $('option[value="Mon-Sun"]').removeAttr('disabled');
    //         $('#type_event').removeAttr('disabled');
    //         $('.btn_add_schedule').removeAttr('disabled');
    //     }
    //     $(this).parents('.schedule').remove();
    // });
    // $('#day').on('change', function(){
    //     $('.btn_add_schedule').removeAttr('disabled');
    // });
    if ($(".well>*").length == 0) {
        $('.well').css('display', 'none');
    }
});
