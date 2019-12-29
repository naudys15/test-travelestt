var mymap;
var token;
var message_result;
var count_files_in_dropzone = 0;
// marcador cuando haces click en el mapa
var marker;
var markers = [];
var esp_markers = [];
var long_markers;
var elevations = [];
var latitudes = [];
var longitudes = [];
var latitudes_stations = [];
var longitudes_stations = [];
var cant_descrip_puntos_interes = 0;
/**
 * Función que permite buscar en la api de nominatim las direcciones, finalmente ubicando el mapa en la dirección seleccionada
 */
function addrSearch()
{
    var inp = document.getElementById("addr");
    $.getJSON('http://nominatim.openstreetmap.org/search?format=json&q=' + inp.value, function(data) {
        var items = [];
        $.each(data, function(key, val) {
            var event_click = 'chooseAddr(' + val.lat + ', ' + val.lon + ',"'+val.display_name+'");return false;';
        items.push(
            "<li><a href='#' onclick='"+event_click+"'>" + val.display_name +
            '</a></li>'
        );
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
    });
}
/**
 * Función que permite mover el mapa a un punto específico
 * @param {float} lat 
 * @param {float} lng 
 * @param {string} address 
 */
function chooseAddr(lat, lng, address)
{
    $('#address').val(address);
    var location = new L.LatLng(lat, lng);
    mymap.flyTo([lat, lng], 14);
}
/**
 * Función que permite obtener la elevación de un punto específico
 * @param {float} lat 
 * @param {float} lng 
 * @param {string} token 
 */
function getElevationFromApi(lat, lng, token, pos)
{
    $.getJSON('http://open.mapquestapi.com/elevation/v1/profile?shapeFormat=raw&key='+ token +'&latLngCollection=' + lat + ',%20' + lng, function(data) {
        var elevation = data.elevationProfile[0].height;
        if (pos >= 0) {
            elevations[pos] = elevation;
        } else {
            elevations.push(elevation);
        }
        if (elevation < 0) {
            for (var i = 0; i < markers.length; i++) {
                let position = markers[i].getLatLng();
                if (position.lat.toString() == lat && position.lng.toString() == lng) {
                    marker.removeFrom(mymap);
                    markers.splice(i, 1);
                    latitudes.splice(i, 1);
                    longitudes.splice(i, 1);
                    elevations.splice(i, 1);
                    break;
                }
            }
            if (cant_descrip_puntos_interes != 0) {
                $('#name_' + (cant_descrip_puntos_interes-1)).remove();
                $('#description_' + (cant_descrip_puntos_interes-1)).remove();
                cant_descrip_puntos_interes--;
            }
            if (cant_descrip_puntos_interes == 0) {
                $('#points_description').empty();
            }
        }
    });
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

$(document).ready(function () 
{
    //Fijar traducciones
    setTranslations();
    //Textarea con editor enriquecido
    $('.site_information').summernote({
        height : '200px'
    });

    $('#slug').bind('input', function(){
        var input_slug = $(this).val();
        var special_characters_admitted = '-';
        var pattern = /[a-zA-Z- 0-9]/g;
        var result = input_slug.match(pattern);
        var new_result = "";
        if (result != null) {
            for (var i = 0; i < result.length; i++) {
                new_result += result[i];
            }
        }
        new_result = new_result.replace(/[0-9]/g, '');
        new_result = new_result.replace(/\s/g, special_characters_admitted);
        $(this).val(new_result);
    });

    //Inicialización del mapa en el panel
    mymap = L.map('mapid').setView([38.7054500, -0.4743200], 14);
    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mymap);
    $('#state').on('change', function () {
        if ($('#country').val() != '') {
            let data = {
                id: $('#state').val(),
                a: 1
            };
            let url = $(this).attr('city');
            $.ajax({
                url: url,
                type: "GET",
                data: data,
                dataType: 'JSON',
                success: function (respuesta) {
                    if (respuesta.status == 'success') {
                        generarCiudades(respuesta.response);
                    } else {
                        console.log('error');
                    }
                }
            });
        }
    });
    // generar ciudades en select
    var ciudades = translations['cities'];
    function generarCiudades(cities) {
        $.each(cities, function (i, item) {
            ciudades += "<option value='" + item.id + "' lat='" + item.latitude + "' lon='" + item.longitude + "'>" + item.name + "</option>";
        });
        $('#city').html(ciudades);
        ciudades = translations['cities'];
    };
    //Detectar cambios en la ciudad seleccionada
    $('#city').on('change', function () {
        let latitude = $('#city option:selected').attr('lat');
        let longitude = $('#city option:selected').attr('lon');
        mymap.flyTo([latitude, longitude], 14)
    });

    function onMapClick(e) {
        var popUp = translations['coordinates'] + e.latlng.lat.toString() + "," + e.latlng.lng.toString();
        marker = new L.marker(e.latlng, {
            draggable: true,
            autoPan: true
        }).bindPopup(popUp);
        latitudes.push(marker.getLatLng().lat.toString());
        longitudes.push(marker.getLatLng().lng.toString());
        markers.push(marker);
        long_markers = markers.length;
        markers[long_markers-1].on('dragend', function (event) {
            let popup = translations['coordinates'] + position.latlng.lat.toString() + "," + position.latlng.lng.toString();
            let marker = event.target;
            let position = marker.getLatLng();
            latitudes[long_markers-1] = marker.getLatLng().lat.toString();
            longitudes[long_markers-1] = marker.getLatLng().lng.toString();
            marker.setLatLng(position, {
                draggable: true
            }).bindPopup(popup).update();
            getElevationFromApi(position.lat.toString(), position.lng.toString(), 'tHXSNAGXRx6LAoBNdgjjLycOhGqJalg7', (long_markers-1));
        });
        markers[long_markers-1].on('mousedown', function (event) {
            esp_markers[long_markers-1] = setTimeout(function(){
                let marker = event.target;
                let position2 = marker.getLatLng();
                for (var i = 0; i < markers.length; i++) {
                    let position = markers[i].getLatLng();
                    if (position.lat.toString() == position2.lat.toString() && position.lng.toString() == position2.lng.toString()) {
                        marker.removeFrom(mymap);
                        markers.splice(i, 1);
                        latitudes.splice(i, 1);
                        longitudes.splice(i, 1);
                        elevations.splice(i, 1);
                        break;
                    }
                }
                if (cant_descrip_puntos_interes != 0) {
                    $('#name_' + (cant_descrip_puntos_interes-1)).remove();
                    //$('#description_' + (cant_descrip_puntos_interes-1)).remove();
                    cant_descrip_puntos_interes--;
                }
                if (cant_descrip_puntos_interes == 0) {
                    $('#points_description').empty();
                }
            },2000);
        });
        markers[long_markers-1].on('mouseup', function (event) {
            clearTimeout(esp_markers[long_markers-1]);
        });
        let pos = marker.getLatLng();
        getElevationFromApi(pos.lat.toString(), pos.lng.toString(), 'tHXSNAGXRx6LAoBNdgjjLycOhGqJalg7');
        mymap.addLayer(marker);
        if (markers.length > 2) {
            $('#points_description').append('<div class="row" id="name_'+cant_descrip_puntos_interes+'" style="margin-bottom:4%"><label class="col-md-3 control-label" for="stations_name_'+cant_descrip_puntos_interes+'">'+translations['station_name']+'</label><div class="col-md-9"><input required type="text" id="stations_name[]" class="station_name form-control" placeholder="'+translations['station_name']+' '+(cant_descrip_puntos_interes+1)+'" name="stations_name[]"></div></div>');
            //$('#points_description').append('<div class="row" id="description_'+cant_descrip_puntos_interes+'" style="margin-bottom:4%"><label class="col-md-3 control-label" for="stations_information_'+cant_descrip_puntos_interes+'">'+translations['station_description']+'</label><div class="col-md-9"><input required type="text" id="stations_information[]" class="station_description form-control" placeholder="'+translations['station_description']+' '+(cant_descrip_puntos_interes+1)+'" name="stations_information[]"></div></div>');
            cant_descrip_puntos_interes++;
        }
    };
    mymap.on('click', onMapClick);
    var myDropzone = new Dropzone("#dropzoneFiles", {
        paramName: "file[]",
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 100, 
        maxFilesize: 1, 
        maxFiles: 5,
        acceptedFiles: ".jpg, .jpeg, .png",
        addRemoveLinks: true,
        // Language Strings
        dictFileTooBig: translations['dictFileTooBig'],
        dictInvalidFileType: translations['dictInvalidFileType'],
        dictCancelUpload: translations['dictCancelUpload'],
        dictRemoveFile: translations['dictRemoveFile'],
        dictMaxFilesExceeded: translations['dictMaxFilesExceeded'],
        dictDefaultMessage: translations['dictDefaultMessage'],
        removedfile: function(file)
        {
            var _ref = file.previewElement;
            if (Dropzone.forElement("div#dropzoneFiles").files.length < count_files_in_dropzone) {
                Dropzone.forElement("div#dropzoneFiles").options.maxFiles++;
                count_files_in_dropzone--;
            }
            return (_ref != null)? _ref.parentNode.removeChild(file.previewElement) : void 0;
        }
    });
    $('#dropzoneFiles').addClass('dropzone');
    if ($(".well>*").length == 0) {
        $('.well').css('display', 'none');
    }
    $("#form_new_route").on('submit',function (e) {
        e.preventDefault();
        e.stopPropagation();
        var shortDescription = $('.note-editable').eq(0).html();
        var longDescription = $('.note-editable').eq(1).html();
        var files_in_dropzone = Dropzone.forElement("div#dropzoneFiles").files.length;
        var fields_in_form = ($('#name').val() != "" && $('#address').val() != "" && $('#meta_title').val() != "" && $('#meta_description').val() != "" && shortDescription != "" && longDescription != "");
        if (marker != undefined && markers.length >= 2 && files_in_dropzone > 0 && files_in_dropzone < 6 && fields_in_form == true) {
            $("#submit_btn").attr('disabled', 'disabled');
            token = $('input[name="api_token"]').val();
            $('form .well').css('display', 'none');
            $('form .well').html('');
            var url_place_translations_images = "";
            var url_place = $("#form_new_route").attr('action');
            var method_form = $("#form_new_route").attr('method');
            
            if (method_form == 'PUT') {
                message_result = translations['message_result_update']+' '+translations['message_route'];
            } else {
                message_result = translations['message_result']+' '+translations['message_route'];
            }
            cant_descrip_puntos_interes = 0;
            for (var i = 0; i < markers.length; i++) {
                if (i > 0 && i != (markers.length-1)) {
                    latitudes_stations[cant_descrip_puntos_interes] = markers[i].getLatLng().lat.toString();
                    longitudes_stations[cant_descrip_puntos_interes] = markers[i].getLatLng().lng.toString();
                    cant_descrip_puntos_interes++;
                }
            }
            $.ajax({
                url: url_place,
                headers: {'_token': token},
                type: method_form,
                dataType: 'json',
                data: {
                    "latitudes" : latitudes,
                    "longitudes" : longitudes,
                    "elevations" : elevations,
                    "city" : $('#city').val(),
                    "level" : $('#level').val(),
                    "duration_unit" : $('#duration_unit').val(),
                    "duration_value" : $('#duration_value').val(),
                    'slug': $('#slug').val()
                },
                //data: formData,
                //processData: false,
                //contentType: false,
                statusCode: {
                    201: function(data) {
                        if (method_form == 'PUT') {
                            url_place_translations_images = url_place;
                        } else {
                            url_place_translations_images = url_place+'/'+data.response;
                        }
                        var url_place_stations = url_place_translations_images+"/stations";
                        var stations_saved = 0;
                        var translations_of_stations_saved = 0;
                        var recor_id_stations = 0;
                        $('form .well').empty('');
                        $('form .well').css('display', 'none');
                        $.ajax({
                            url: url_place_translations_images+'/translations',
                            headers: {'_token': token},
                            type: method_form,
                            data: {
                                "name" : $('#name').val(),
                                "address" : $('#address').val(),
                                "meta_title" : $('#meta_title').val(),
                                "meta_description" : $('#meta_description').val(),
                                "short_description" : shortDescription,
                                "long_description" : longDescription
                            },
                            //data: formData,
                            //processData: false,
                            //contentType: false,
                            statusCode: {
                                201: function(data) {
                                    //let myForm = document.getElementById('form_site');
                                    //var formData = new FormData(myForm);
                                    var formData = new FormData();

                                    formData.append('count', (myDropzone.getAcceptedFiles().length+myDropzone.getRejectedFiles().length));
                                    for (var i = 0 ; i < myDropzone.getAcceptedFiles().length; i++) {
                                        //console.log(myDropzone.getAcceptedFiles()[i]);
                                        formData.append('file[]', myDropzone.getAcceptedFiles()[i]);
                                    }
                                    if (myDropzone.getRejectedFiles().length > 0) {
                                        for (var i = 0 ; i < myDropzone.getRejectedFiles().length; i++) {
                                            //console.log(myDropzone.getRejectedFiles()[i].name);
                                            formData.append('files_already[]', myDropzone.getRejectedFiles()[i].name);
                                        }
                                    } else {
                                        formData.append('files_already[]', '');
                                    }
                                    $.ajax({
                                        url: url_place_translations_images + '/images',
                                        headers: {'_token': token},
                                        type: 'POST',
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        statusCode: {
                                            201: function(data) {
                                                $('form .well').empty('');
                                                $('form .well').css('display', 'none');
                                                var resp_url_stations = url_place_stations;
                                                for (var i = 0; i < longitudes_stations.length; i++) {
                                                    if (method_form == 'POST') {
                                                        url_place_stations = url_place_stations;
                                                    } else {
                                                        url_place_stations = resp_url_stations+'/'+$('.station_name').eq(recor_id_stations).attr('data-id');
                                                    }
                                                    $.ajax({
                                                        url: url_place_stations,
                                                        headers: {'_token': token},
                                                        type: method_form,
                                                        data: {
                                                            "latitude" : latitudes_stations[i],
                                                            "longitude" : longitudes_stations[i],
                                                        },
                                                        statusCode: {
                                                            201: function(data) {
                                                                var base_url = window.location.origin;
                                                                if (base_url.indexOf('localhost') > 0) {
                                                                    base_url += '/turismotoledo';
                                                                }
                                                                var stations_translations = base_url+"/api/places/stations/"+data.response+"/translations";
                                                                $.ajax({
                                                                    url: stations_translations,
                                                                    headers: {'_token': token},
                                                                    type: method_form,
                                                                    data: {
                                                                        "name" : $('.station_name').eq(translations_of_stations_saved).val(),
                                                                    },
                                                                    statusCode: {
                                                                        201: function(data) {
                                                                            stations_saved++;
                                                                            if (stations_saved == longitudes_stations.length) {
                                                                                $.niftyNoty({
                                                                                    type: "success",
                                                                                    container: "floating",
                                                                                    html: message_result,
                                                                                    closeBtn: true,
                                                                                    floating: {
                                                                                        position: "bottom-right",
                                                                                        animationIn: "jellyIn",
                                                                                        animationOut: "fadeOut"
                                                                                    },
                                                                                    focus: true,
                                                                                    timer: 2500
                                                                                });
                                                                                $("#submit_btn").removeAttr('disabled');
                                                                                $("#form_new_route input[type='text']").val('');
                                                                                $("#form_new_route textarea").val('');
                                                                                $("#form_new_route input[type='number']").val('');
                                                                                $('.note-editable').eq(0).html('');
                                                                                $('.note-editable').eq(1).html('');
                                                                                for(var i = 0; i < markers.length; i++) {
                                                                                    let marker = markers[i];
                                                                                    marker.removeFrom(mymap);
                                                                                }
                                                                                markers = [];
                                                                                latitudes = [];
                                                                                longitudes = [];
                                                                                elevations = [];
                                                                                Dropzone.forElement("div#dropzoneFiles").removeAllFiles(true);
                                                                            }
                                                                        },
                                                                        400: function(data) {
                                                                            var response = JSON.parse(data.responseText);
                                                                            $('form .well').css('display', 'block');
                                                                            $('form .well').html(response.response);
                                                                            $("#submit_btn").removeAttr('disabled');
                                                                        }
                                                                    }
                                                                });
                                                                translations_of_stations_saved++;
                                                            },
                                                            400: function(data) {
                                                                var response = JSON.parse(data.responseText);
                                                                $('form .well').css('display', 'block');
                                                                $('form .well').html(response.response);
                                                                $("#submit_btn").removeAttr('disabled');
                                                            }
                                                        }
                                                    });
                                                    recor_id_stations++;
                                                }
                                            },
                                            400: function(data) {
                                                var response = JSON.parse(data.responseText);
                                                $('form .well').css('display', 'block');
                                                $('form .well').html(response.response);
                                                $("#submit_btn").removeAttr('disabled');
                                            }
                                        }
                                    });
                                },
                                400: function(data) {
                                    var response = JSON.parse(data.responseText);
                                    $('form .well').css('display', 'block');
                                    $('form .well').html(response.response);
                                    $("#submit_btn").removeAttr('disabled');
                                }
                            }
                        });
                    },
                    400: function(data) {
                        var response = data.responseText;
                        var errors = JSON.parse(response);
                        var all_errors = errors.response;
                        $('form .well').html('');
                        for (var indice in all_errors) {
                            $('form .well').append('<p>'+all_errors[indice]+'</p>');
                        }
                        $('form .well').css('display', 'block');
                        $("#submit_btn").removeAttr('disabled');
                    }
                }
            });
        }
        // if (marker != undefined && markers.length >= 2 && files_in_dropzone > 0 && files_in_dropzone < 6 && fields_in_form == true) {
        //     let myForm = document.getElementById('form_new_route');
        //     var formData = new FormData(myForm);
        //     for(var i = 0;i < myDropzone.getAcceptedFiles().length; i++){
        //         formData.append('file[]', myDropzone.getAcceptedFiles()[i]); 
        //     }
        //     if ($(".well>*").length == 0) {
        //         $('.well').css('display', 'none');
        //     }
        //     token = $('input[name="api_token"]').val();
        //     $.ajax({
        //         url: $("#form_new_route").attr('action'),
        //         headers: {'_token': token},
        //         type: 'POST',
        //         dataType: 'json',
        //         data:formData,
        //         processData: false, 
        //         contentType: false,
        //         statusCode: {
        //             201: function(data) {
        //                 if (data.status == 'error') {
        //                     $('form .well').css('display', 'block');
        //                     $('form .well').html(data.response);
        //                 } else {
        //                     $.ajax({
        //                         url: 'http://localhost/turismotoledo/api/places/routes/'+data.response+'/images',
        //                         type: 'POST',
        //                         headers: {'_token': token},
        //                         dataType: 'json',
        //                         data: formData,
        //                         processData: false,
        //                         contentType: false,
        //                         success: function(_data) {
        //                             if (_data.status == 'error') {
        //                                 $('form .well').css('display', 'block');
        //                                 $('form .well').html(_data.response);
        //                             } else {
        //                                 $('form .well').empty();
        //                                 $('form .well').css('display', 'none');
        //                                 $.niftyNoty({
        //                                     type: "success",
        //                                     container: "floating",
        //                                     html: message_result,
        //                                     closeBtn: true,
        //                                     floating: {
        //                                         position: "bottom-right",
        //                                         animationIn: "jellyIn",
        //                                         animationOut: "fadeOut"
        //                                     },
        //                                     focus: true,
        //                                     timer: 2500
        //                                 });
        //                                 $("#form_new_route input[type='text']").val('');
        //                                 $("#form_new_route textarea").val('');
        //                                 $("#form_new_route input[type='number']").val('');
        //                                 for(var i = 0; i < markers.length; i++) {
        //                                     let marker = markers[i];
        //                                     marker.removeFrom(mymap);
        //                                 }
        //                                 markers = [];
        //                                 latitudes = [];
        //                                 longitudes = [];
        //                                 elevations = [];
        //                                 Dropzone.forElement("div#dropzoneFiles").removeAllFiles(true);
        //                             }
        //                         }
        //                     })
        //                 }
        //             },
        //             400: function(data) {
        //                 var response = data.responseText;
        //                 var errors = JSON.parse(response);
        //                 var all_errors = errors.response;
        //                 $('form .well').html('');
        //                 for (var indice in all_errors) {
        //                     $('form .well').append('<p>'+all_errors[indice]+'</p>');
        //                 }
        //                 $('form .well').css('display', 'block');
        //             }
        //         }
        //     });
        // } 
        else if (markers.length < 2 && files_in_dropzone == 0 && fields_in_form == false) {
            $('form .well').css('display', 'block');
            $('form .well').html(translations['message_no_more_than_two_markers_no_images_no_form']);
        } else if (markers.length < 2 && files_in_dropzone == 0 && fields_in_form == true) {
            $('form .well').css('display', 'block');
            $('form .well').html(translations['message_no_more_than_two_markers_no_images_form']);
        } else if (markers.length < 2 && files_in_dropzone > 0 && fields_in_form == false) {
            $('form .well').css('display', 'block');
            $('form .well').html(translations['message_no_more_than_two_markers_images_no_form']);
        } else if (markers.length < 2 && files_in_dropzone > 0 && fields_in_form == true) {
            $('form .well').css('display', 'block');
            $('form .well').html(translations['message_no_more_than_two_markers_images_form']);
        } else if (markers.length >= 2 && files_in_dropzone == 0 && fields_in_form == false) {
            $('form .well').css('display', 'block');
            $('form .well').html(translations['message_more_than_two_markers_no_images_no_form']);
        } else if (markers.length >= 2 && files_in_dropzone == 0 && fields_in_form == true) {
            $('form .well').css('display', 'block');
            $('form .well').html(translations['message_more_than_two_markers_no_images_form']);
        } else if (markers.length >= 2 && files_in_dropzone > 0 && fields_in_form == false) {
            $('form .well').css('display', 'block');
            $('form .well').html(translations['message_more_than_two_markers_images_no_form']);
        }
    });
});
