$('#loading_modal').modal('show')
$('#setLanguage').trigger('click')
loadCategoriesAndCharacteristics('route')
var setTitleToCity = false
var marker
var markers = []
var esp_markers = []
var long_markers
var elevations = []
var latitudes = []
var longitudes = []
var lastItemForAppend = ''
// var latitudes_stations = []
// var longitudes_stations = []
var count_stations = 0
function markerDragend(event) {
    let marker = event.target
    let position = marker.getLatLng()
    let popup = translations['coordinates'] + position.lat.toString() + "," + position.lng.toString()
    latitudes[long_markers-1] = marker.getLatLng().lat.toString()
    longitudes[long_markers-1] = marker.getLatLng().lng.toString()
    marker.setLatLng(position, {
        draggable: true
    }).bindPopup(popup).update()
    getElevationFromApi(position.lat.toString(), position.lng.toString(), 'tHXSNAGXRx6LAoBNdgjjLycOhGqJalg7', (long_markers-1))
}
function mouseDown(event) {
    esp_markers[long_markers-1] = setTimeout(function(){
        let marker = event.target
        let position2 = marker.getLatLng()
        for (var i = 0; i < markers.length; i++) {
            let position = markers[i].getLatLng()
            if (position.lat.toString() == position2.lat.toString() && position.lng.toString() == position2.lng.toString()) {
                marker.removeFrom(mymap)
                markers.splice(i, 1)
                latitudes.splice(i, 1)
                longitudes.splice(i, 1)
                elevations.splice(i, 1)
                $('#name_station_'+(i-2)).parents('div.form-group').remove()
                break
            }
        }
        if (count_stations != 0) {
            count_stations--
        }
    }, 2000)
}
//Inicialización del mapa en el panel
mymap = L.map('mapid').setView([38.7054500, -0.4743200], 14);
L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(mymap)
//Función que detecta el evento de click en el mapa
function onMapClick(e) {
    var popUp = translations['coordinates'] + e.latlng.lat.toString() + "," + e.latlng.lng.toString()
    marker = new L.marker(e.latlng, {
        draggable: true,
        autoPan: true
    }).bindPopup(popUp)
    latitudes.push(marker.getLatLng().lat.toString())
    longitudes.push(marker.getLatLng().lng.toString())
    markers.push(marker)
    long_markers = markers.length
    markers[long_markers-1].on('dragend', function (event) {
        var marker_dragend = markers.findIndex(marker => marker._leaflet_id == event.target._leaflet_id)
        latlngInput(markers, marker_dragend)
        markerDragend(event)
    });
    markers[long_markers-1].on('mousedown', function(event){
        mouseDown(event)
    });
    markers[long_markers-1].on('mouseup', function (event) {
        clearTimeout(esp_markers[long_markers-1])
    });
    let pos = marker.getLatLng()
    elevations.push(getElevationFromApi(pos.lat.toString(), pos.lng.toString(), 'tHXSNAGXRx6LAoBNdgjjLycOhGqJalg7'))
    mymap.addLayer(marker)
    if (markers.length == 1) {
        let output = '<h4 class="mar-btm">'+translations['route']+'</h4><div class="form-group"><div class="row"><div class="col-md-12 container-coordinates">'
        output += '</div></div></div>'
        $('#spanish>div.form-group:last-child').after(output)
        $('#english>div.form-group:last-child').after(output)
        $('#french>div.form-group:last-child').after(output)

        let exit = '<div class="col-md-3"><label class="control-label" for="latitude_exit">'+translations['latitude_exit']+'</label>'
        exit += '<input type="number" step="0.0001" id="latitude_exit" class="form-control" name="latitude_exit" value="'+markers[0]._latlng.lat.toFixed(4)+'"></div>'
        exit += '<div class="col-md-3"><label class="control-label" for="longitude_exit">'+translations['longitude_exit']+'</label>'
        exit += '<input type="number" step="0.0001" id="longitude_exit" class="form-control" name="longitude_exit" value="'+markers[0]._latlng.lng.toFixed(4)+'"></div>'
        exit += '</div></div></div>'
        $('#spanish div.container-coordinates').append(exit)
        $('#english div.container-coordinates').append(exit)
        $('#french div.container-coordinates').append(exit)
    }
    
    if(markers.length == 2) {
        let arrival = '<div class="col-md-3"><label class="control-label" for="latitude_arrival">'+translations['latitude_arrival']+'</label>'
        arrival += '<input type="number" step="0.0001" id="latitude_arrival" class="form-control" name="latitude_arrival" value="'+markers[1]._latlng.lat.toFixed(4)+'"></div>'
        arrival += '<div class="col-md-3"><label class="control-label" for="longitude_arrival">'+translations['longitude_arrival']+'</label>'
        arrival += '<input type="number" step="0.0001" id="longitude_arrival" class="form-control" name="longitude_arrival" value="'+markers[1]._latlng.lng.toFixed(4)+'"></div>'
        arrival += '</div></div></div>'
        $('#spanish div.container-coordinates').append(arrival)
        $('#english div.container-coordinates').append(arrival)
        $('#french div.container-coordinates').append(arrival)
    }

    if (markers.length > 2) {
        let output = '<div class="form-group"><div class="row mt-2 mb-2">'
        output += '<div class="col-md-4"><label class="control-label" for="name_station_'+count_stations+'">'+translations['station_name']+(count_stations+1)+'</label>'
        output += '<input id="name_station_'+count_stations+'" class="form-control station" name="name_station_'+count_stations+'"></div>'
        output += '<div class="col-md-4"><label class="control-label" for="name_lat_station_'+count_stations+'">'+translations['latitude_station']+(count_stations+1)+'</label>'
        output += '<input id="name_lat_station_'+count_stations+'" type="number" step="0.0001" class="form-control" name="name_lat_station_'+count_stations+'" value="'+markers[count_stations + 2]._latlng.lat.toFixed(4)+'"></div>'
        output += '<div class="col-md-4"><label class="control-label" for="name_lng_station_'+count_stations+'">'+translations['longitude_station']+(count_stations+1)+'</label>'
        output += '<input id="name_lng_station_'+count_stations+'" type="number" step="0.0001" class="form-control" name="name_lng_station_'+count_stations+'" value="'+markers[count_stations + 2]._latlng.lng.toFixed(4)+'"></div>'
        output += '</div></div>'
        $('#spanish>div.form-group:last-child').after(output)
        $('#english>div.form-group:last-child').after(output)
        $('#french>div.form-group:last-child').after(output)
        count_stations++
    }
}
mymap.on('click', onMapClick)
$(document).ready(function(){
    if (language == 'es') {
        $('a[href="#english"]').trigger('click')
        $('a[href="#spanish"]').trigger('click')
    } else if (language == 'en') {
        $('a[href="#french"]').trigger('click')
        $('a[href="#english"]').trigger('click')
    } else if (language == 'fr') {
        $('a[href="#spanish"]').trigger('click')
        $('a[href="#french"]').trigger('click')
    }
    if (location.href.includes(translations['url_cities'])) {
        let url = location.href.split('/')
        let cityId = url[6]
        let lat = $('#city option[value="'+cityId+'"]').attr('lat')
        let long = $('#city option[value="'+cityId+'"]').attr('lng')
        mymap.flyTo([lat, long], 14);
    }
})
Dropzone.autoDiscover = false
var count_files_in_dropzone = 0
var files_to_edit = []
var myDropzone = new Dropzone("#dropzoneFiles", {
    paramName: "file[]",
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 100,
    maxFilesize: 1,
    maxFiles: 5,
    acceptedFiles: ".jpg, .jpeg, .png",
    dictRemoveFile: "Remover",
    dictFileTooBig: translations['dictFileTooBig'],
    dictInvalidFileType: translations['dictInvalidFileType'],
    dictCancelUpload: translations['dictCancelUpload'],
    dictRemoveFile: translations['dictRemoveFile'],
    dictMaxFilesExceeded: translations['dictMaxFilesExceeded'],
    dictDefaultMessage: translations['dictDefaultMessage'],
    removedfile: function(file) {
        var _ref = file.previewElement;
        count_files_in_dropzone--;
        return (_ref != null)? _ref.parentNode.removeChild(file.previewElement) : void 0;
    }
});
myDropzone.on("addedfile", function(file) {
    count_files_in_dropzone++
    myDropzone.emit("complete", file);
    var removeButton = Dropzone.createElement("<button class='btn-link'>"+translations['delete']+"</button>");
    var _this = this;
    removeButton.addEventListener("click", function(e) {
        _this.removeFile(file);
    });
    file.previewElement.appendChild(removeButton);
})
$('#dropzoneFiles').addClass('dropzone')
var url = location.href.split('/')
var id = parseInt(url.pop())
if (url.length >= 7) {
    setTitleToCity = true
}
if (Number.isInteger(id)) {
    $.ajax({
        url: location.origin+"/api/routes/"+id,
        type: 'GET',
        dataType: 'json',
        headers: authorization(),
        statusCode: {
            200: function(data) {
                let resource = data.message[0]
                if (setTitleToCity) {
                    $('html>head>title').html('Travelestt | '+resource.location.city.name+' | '+translations['edit_route'])
                    $('#page-title h1').html(resource.location.city.name+' | '+translations['edit_route'])
                } else {
                    $('html>head>title').html('Travelestt | '+translations['edit_route'])
                    $('#page-title h1').html(translations['edit_route'])
                }
                if (resource.media) {
                    for (i = 0; i < resource.media.length; i++) {
                        let place_img_url = location.origin+"/assets/images/routes/"+resource.media[i]
                        let mockFile = {
                            name: resource.media[i],
                            size: 12345,
                            accepted: true,
                            kind: 'image'
                        };
                        myDropzone.emit("addedfile", mockFile)
                        myDropzone.emit("thumbnail", mockFile, place_img_url)
                        myDropzone.files.push(mockFile)
                        myDropzone.emit("complete", mockFile)
                        files_to_edit.push(data.message[0].media[i])
                    }
                }

                mymap.setView([resource.latitude_start, resource.longitude_start], 14)
                //Salida
                var popup = translations['coordinates'] + resource.latitude_start + "," + resource.longitude_start
                marker = new L.marker(
                    {
                        lon: resource.longitude_start, 
                        lat: resource.latitude_start
                    }, {
                        draggable: true,
                        autoPan: true
                    }).bindPopup(popup)
                latitudes.push(marker.getLatLng().lat.toString())
                longitudes.push(marker.getLatLng().lng.toString())
                markers.push(marker)

                let output = '<h4 class="mar-btm">'+translations['route']+'</h4><div class="form-group"><div class="row"><div class="col-md-12 container-coordinates">'
                output += '</div></div></div>'
                $('#spanish>div.form-group:last-child').after(output)
                $('#english>div.form-group:last-child').after(output)
                $('#french>div.form-group:last-child').after(output)

                let exit = '<div class="col-md-3"><label class="control-label" for="latitude_exit">Latitud de Salida</label>'
                exit += '<input type="number" step="0.0001" id="latitude_exit" class="form-control" name="latitude_exit" value="'+markers[0]._latlng.lat.toFixed(4)+'"></div>'
                exit += '<div class="col-md-3"><label class="control-label" for="longitude_exit">Longitud de Salida</label>'
                exit += '<input type="number" step="0.0001" id="longitude_exit" class="form-control" name="longitude_exit" value="'+markers[0]._latlng.lng.toFixed(4)+'"></div>'
                exit += '</div></div></div>'
                $('#spanish div.container-coordinates').append(exit)
                $('#english div.container-coordinates').append(exit)
                $('#french div.container-coordinates').append(exit)

                long_markers = markers.length
                markers[long_markers-1].on('dragend', function(event) {
                    var marker_dragend = markers.findIndex(marker => marker._leaflet_id == event.target._leaflet_id)
                    latlngInput(markers, marker_dragend)
                    markerDragend(event)
                });
                markers[long_markers-1].on('mousedown', function(event){
                    mouseDown(event)
                });
                markers[long_markers-1].on('mouseup', function (event) {
                    clearTimeout(esp_markers[long_markers-1])
                });
                let pos = marker.getLatLng()
                elevations.push(getElevationFromApi(pos.lat.toString(), pos.lng.toString(), 'tHXSNAGXRx6LAoBNdgjjLycOhGqJalg7'))
                mymap.addLayer(marker)
                //Llegada
                popup = translations['coordinates'] + resource.latitude_end + "," + resource.longitude_end
                marker = new L.marker(
                    {
                        lon: resource.longitude_end, 
                        lat: resource.latitude_end
                    }, {
                        draggable: true,
                        autoPan: true
                    }).bindPopup(popup)
                latitudes.push(marker.getLatLng().lat.toString())
                longitudes.push(marker.getLatLng().lng.toString())
                markers.push(marker)
                
                let arrival = '<div class="col-md-3"><label class="control-label" for="latitude_arrival">Latitud de Llegada</label>'
                arrival += '<input type="number" step="0.0001" id="latitude_arrival" class="form-control" placeholder="Latitud" name="latitude_arrival" value="'+markers[1]._latlng.lat.toFixed(4)+'"></div>'
                arrival += '<div class="col-md-3"><label class="control-label" for="longitude_exit">Longitud de Llegada</label>'
                arrival += '<input type="number" step="0.0001" id="longitude_arrival" class="form-control" placeholder="Longitud" name="longitude_arrival" value="'+markers[1]._latlng.lng.toFixed(4)+'"></div>'
                arrival += '</div></div></div>'
                $('#spanish div.container-coordinates').append(arrival)
                $('#english div.container-coordinates').append(arrival)
                $('#french div.container-coordinates').append(arrival)

                long_markers = markers.length
                markers[long_markers-1].on('dragend', function(event) {
                    var marker_dragend = markers.findIndex(marker => marker._leaflet_id == event.target._leaflet_id)
                    latlngInput(markers, marker_dragend)
                    markerDragend(event)
                });
                markers[long_markers-1].on('mousedown', function(event){
                    mouseDown(event)
                });
                markers[long_markers-1].on('mouseup', function (event) {
                    clearTimeout(esp_markers[long_markers-1])
                });
                pos = marker.getLatLng()
                elevations.push(getElevationFromApi(pos.lat.toString(), pos.lng.toString(), 'tHXSNAGXRx6LAoBNdgjjLycOhGqJalg7'))
                mymap.addLayer(marker)
                //Estaciones
                if (!jQuery.isEmptyObject(resource.stations)) {
                    $.each(resource.stations, function(i, item){
                        var popup = translations['coordinates'] + item.latitude + "," + item.longitude
                        marker = new L.marker(
                            {
                                lon: item.longitude, 
                                lat: item.latitude
                            }, {
                                draggable: true,
                                autoPan: true
                            }).bindPopup(popup)
                        latitudes.push(marker.getLatLng().lat.toString())
                        longitudes.push(marker.getLatLng().lng.toString())
                        markers.push(marker)
                        long_markers = markers.length
                        markers[long_markers-1].on('dragend', function(event) {
                            var marker_dragend = markers.findIndex(marker => marker._leaflet_id == event.target._leaflet_id)
                            latlngInput(markers, marker_dragend)
                            markerDragend(event)
                        });
                        markers[long_markers-1].on('mousedown', function(event){
                            mouseDown(event)
                        });
                        markers[long_markers-1].on('mouseup', function (event) {
                            clearTimeout(esp_markers[long_markers-1])
                        });
                        let pos = marker.getLatLng()
                        elevations.push(getElevationFromApi(pos.lat.toString(), pos.lng.toString(), 'tHXSNAGXRx6LAoBNdgjjLycOhGqJalg7'))
                        mymap.addLayer(marker)
                      
                        let output = '<div class="form-group  mt-2 mb-2"><div class="col-md-12"><div class="row">'
                        output += '<div class="col-md-4"><label class="control-label" for="name_station_'+count_stations+'">Nombre estación '+(count_stations+1)+'</label>'
                        output += '<input id="name_station_'+count_stations+'" class="form-control station" name="name_station_'+count_stations+'"></div>'
                        output += '<div class="col-md-4"><label class="control-label" for="name_lat_station_'+count_stations+'">Latitud estación '+(count_stations+1)+'</label>'
                        output += '<input id="name_lat_station_'+count_stations+'" type="number" step="0.0001" class="form-control" name="name_lat_station_'+count_stations+'" value="'+markers[count_stations + 2]._latlng.lat.toFixed(4)+'"></div>'
                        output += '<div class="col-md-4"><label class="control-label" for="name_lng_station_'+count_stations+'">Longitud estación '+(count_stations+1)+'</label>'
                        output += '<input id="name_lng_station_'+count_stations+'" type="number" step="0.0001" class="form-control" name="name_lng_station_'+count_stations+'" value="'+markers[count_stations + 2]._latlng.lng.toFixed(4)+'"></div>'
                        output += '</div></div></div>'
                        $('#spanish>div.form-group:last-child').after(output)
                        $('#english>div.form-group:last-child').after(output)
                        $('#french>div.form-group:last-child').after(output)
                 
                        if (item.es) {
                            $('#spanish>div.form-group:last-child input').val(item.es)
                        }
                        $('#english>div.form-group:last-child').after(output)
                        if (item.en) {
                            $('#english>div.form-group:last-child input').val(item.en)
                        }
                        $('#french>div.form-group:last-child').after(output)
                        if (item.fr) {
                            $('#french>div.form-group:last-child input').val(item.fr)
                        }
                        count_stations++
                    })
                }
                $('input[name="slug"]').val(resource.slug)
                $('#types_'+resource.characteristics[0]).prop('checked', true)
                $('#level_'+resource.characteristics[1]).prop('checked', true)
                var url = location.origin+'/'+language+'/'+resource.location.city.slug+'/'+translations['url_routes']+'/'+resource.slug
                $('#url').html(url)
                $('#url').attr('href', url)
                if (resource.translations.es) {
                    $('input[name="name_spanish"]').val(resource.translations.es.name)
                    $('#short_information_spanish').summernote('code', resource.translations.es.short_description)
                    $('#long_information_spanish').summernote('code', resource.translations.es.long_description)
                    $('textarea[name="address_spanish"]').html(resource.translations.es.address)
                    $('input[name="meta_title_spanish"]').val(resource.translations.es.meta_title)
                    $('input[name="meta_description_spanish"]').val(resource.translations.es.meta_description)
                }
                if (resource.translations.en) {
                    $('input[name="name_english"]').val(resource.translations.en.name)
                    $('#short_information_english').summernote('code', resource.translations.en.short_description)
                    $('#long_information_english').summernote('code', resource.translations.en.long_description)
                    $('textarea[name="address_english"]').html(resource.translations.en.address)
                    $('input[name="meta_title_english"]').val(resource.translations.en.meta_title)
                    $('input[name="meta_description_english"]').val(resource.translations.en.meta_description)
                }
                if (resource.translations.fr) {
                    $('input[name="name_french"]').val(resource.translations.fr.name)
                    $('#short_information_french').summernote('code', resource.translations.fr.short_description)
                    $('#long_information_french').summernote('code', resource.translations.fr.long_description)
                    $('textarea[name="address_french"]').html(resource.translations.fr.address)
                    $('input[name="meta_title_french"]').val(resource.translations.fr.meta_title)
                    $('input[name="meta_description_french"]').val(resource.translations.fr.meta_description)
                }
                $('#state').val(resource.location.province.id)
                if ($('#state').val() != '') {
                    let firstName = $('#city option:first-child')
                    $.ajax({
                        url: location.origin+"/api/cities/province/"+resource.location.province.id,
                        type: "GET",
                        success: function(data_response) {
                            $('#city').html(firstName)
                            $.each(data_response.message, function(i, item) {
                                $('#city').append($('<option>', {
                                    value: item.id,
                                    lat: item.latitude,
                                    lng: item.longitude,
                                    text : item.name
                                }));
                            });
                            $('#city').val(resource.location.city.id)

                        }
                    })
                }
                $('#loading_modal').modal('hide')
            },
            404: function (data) {
                $('#loading_modal').modal('hide')
                window.history.back()
            }
        }
    });
} else {
    $(document).ready(function(){
        if (setTitleToCity) {
            $('html>head>title').html('Travelestt | '+$("#city option:selected").html()+' | '+translations['new_route'])
            $('#page-title h1').html($("#city option:selected").html()+' | '+translations['new_route'])
        } else {
            $('html>head>title').html('Travelestt | '+translations['new_route'])
            $('#page-title h1').html(translations['new_route'])
        }
        $('#url').parents('.form-group').css('display', 'none')
        $('#loading_modal').modal('hide')
    })
}
$(document).on('change', function(){
    updateCoordenate()
})

$("#form_site").on('submit',function (e) {
    e.preventDefault()
    e.stopPropagation()
    var shortDescription = ''
    var longDescription = ''
    var checkStations = true
    if (language == "es") {
        name = $('input[name="name_spanish"]').val()
        shortDescription = $('.note-editable').eq(0).html()
        longDescription = $('.note-editable').eq(1).html()
        address = $('textarea[name="address_spanish"]').val()
        metaTitle = $('input[name="meta_title_spanish"]').val()
        metaDescription = $('input[name="meta_description_spanish"]').val()
        if (count_stations != 0) {
            $('#spanish input.station').map(function(){
                if (this.value == '' && checkStations) {
                    checkStations = false
                }
            })
        }
    } else if (language == "en") {
        name = $('input[name="name_english"]').val()
        shortDescription = $('.note-editable').eq(2).html()
        longDescription = $('.note-editable').eq(3).html()
        address = $('textarea[name="address_english"]').val()
        metaTitle = $('input[name="meta_title_english"]').val()
        metaDescription = $('input[name="meta_description_english"]').val()
        if (count_stations != 0) {
            $('#english input.station').map(function(){
                if (this.value == '' && checkStations) {
                    checkStations = false
                }
            })
        }
    } else if (language == "fr") {
        name = $('input[name="name_french"]').val()
        shortDescription = $('.note-editable').eq(4).html()
        longDescription = $('.note-editable').eq(5).html()
        address = $('textarea[name="address_french"]').val()
        metaTitle = $('input[name="meta_title_french"]').val()
        metaDescription = $('input[name="meta_description_french"]').val()
        if (count_stations != 0) {
            $('#french input.station').map(function(){
                if (this.value == '' && checkStations) {
                    checkStations = false
                }
            })
        }
    }
    if (count_stations != 0) {
        $('#input.station').map(function(){
            if (this.value == '' && checkStations) {
                checkStations = false
            }
        })
    }
    var files_in_dropzone = Dropzone.forElement("div#dropzoneFiles").files.length
    var fields_in_form = false
    var url_place = $("#form_site").attr('action');
    // var validation_schedule_to_entities_with_schedule = 0;
    // if (url_place.indexOf('festival') > 0 || url_place.indexOf('night') > 0 || url_place.indexOf('museum') > 0 || url_place.indexOf('market') > 0) {
    //     validation_schedule_to_entities_with_schedule = 1;
    // }
    fields_in_form = ($('#slug').val() != "" && name != "" && shortDescription != "" && longDescription != "" && address != "" && metaTitle != "" && metaDescription != "" && $('#city option:selected').val() != "" && checkStations && $('input:radio[name=types]:checked').val() != undefined &&$('input:radio[name=level]:checked').val() != undefined)
    //if (marker != undefined && files_in_dropzone > 0 && files_in_dropzone < 6 && fields_in_form == true && ((schedule.length > 0 && validation_schedule_to_entities_with_schedule == 1) || (schedule.length == 0 && validation_schedule_to_entities_with_schedule == 0))) {
    if (files_in_dropzone >= 2 && files_in_dropzone <= 5 && fields_in_form == true) {
        $("#submit_btn").attr('disabled','disabled');
        token = $('input[name="api_token"]').val();
        $('form .well').css('display', 'none');
        $('form .well').html('');
        // $('.well-schedule').css('display', 'none');
        // $('.well-schedule').html('');
        // var url_place_translations_images = "";
        var method_form = $("#form_site").attr('method');
        var default_prefix = '';
        if (url_place.indexOf('update') > 0) {
            default_prefix = translations['message_result_update']+' ';
        } else {
            default_prefix = translations['message_result']+' ';
        }
        var message = "";
        var formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());
        formData.append('status', 1);
        latitudes.forEach(function(i, item){
            formData.append('latitudes[]', i)
        })
        longitudes.forEach(function(i, item){
            formData.append('longitudes[]', i)
        })
        elevations.forEach(function(i, item){
            formData.append('elevations[]', i)
        })
        formData.append('city', $('#city').val());
        // formData.append('duration_unit', $('#duration_unit').val());
        // formData.append('duration_value', $('#duration_value').val());
        // formData.append('phonenumber', $('#phonenumber').val());
        formData.append('slug', $('#slug').val());
        if ($('#name_spanish').val() != "" && $('#address_spanish').val() != "" && $('#meta_title_spanish').val() != "" && $('#meta_description_spanish').val() != "" && $('.note-editable').eq(0).html() != '' && $('.note-editable').eq(1).html() != '') {
            formData.append('translations[spanish][name]', $('#name_spanish').val())
            formData.append('translations[spanish][address]', $('#address_spanish').val())
            formData.append('translations[spanish][short_description]', $('.note-editable').eq(0).html())
            formData.append('translations[spanish][long_description]', $('.note-editable').eq(1).html())
            formData.append('translations[spanish][meta_title]', $('#meta_title_spanish').val())
            formData.append('translations[spanish][meta_description]', $('#meta_description_spanish').val())
            $('#spanish input.station').map(function(){
                formData.append('stations[spanish][]', this.value)
            })
        }
        if ($('#name_english').val() != "" && $('#address_english').val() != "" && $('#meta_title_english').val() != "" && $('#meta_description_english').val() != "" && $('.note-editable').eq(2).html() != '' && $('.note-editable').eq(3).html() != '') {
            formData.append('translations[english][name]', $('#name_english').val())
            formData.append('translations[english][address]', $('#address_english').val())
            formData.append('translations[english][short_description]', $('.note-editable').eq(2).html())
            formData.append('translations[english][long_description]', $('.note-editable').eq(3).html())
            formData.append('translations[english][meta_title]', $('#meta_title_english').val())
            formData.append('translations[english][meta_description]', $('#meta_description_english').val())
            $('#english input.station').map(function(){
                formData.append('stations[english][]', this.value)
            })
        }
        if ($('#name_french').val() != "" && $('#address_french').val() != "" && $('#meta_title_french').val() != "" && $('#meta_description_french').val() != "" && $('.note-editable').eq(4).html() != '' && $('.note-editable').eq(5).html() != '') {
            formData.append('translations[french][name]', $('#name_french').val())
            formData.append('translations[french][address]', $('#address_french').val())
            formData.append('translations[french][short_description]', $('.note-editable').eq(4).html())
            formData.append('translations[french][long_description]', $('.note-editable').eq(5).html())
            formData.append('translations[french][meta_title]', $('#meta_title_french').val())
            formData.append('translations[french][meta_description]', $('#meta_description_french').val())
            $('#french input.station').map(function(){
                formData.append('stations[french][]', this.value)
            })
        }
        formData.append('count', (myDropzone.getAcceptedFiles().length+myDropzone.getRejectedFiles().length))
        for (var i = 0 ; i < myDropzone.getAcceptedFiles().length; i++) {
            if (myDropzone.getAcceptedFiles()[i].dataURL != undefined) {
                formData.append('file[]', myDropzone.getAcceptedFiles()[i])
            }
        }
        if (Number.isInteger(id)) {
            for(i = 0; i < myDropzone.files.length; i++) {
                let found = files_to_edit.find(function(element) {
                    return element == myDropzone.files[i].name
                });
                if (found != undefined) {
                    formData.append('files_already[]', myDropzone.files[i].name)
                }
            }
        }
        formData.append('characteristics[]', $('input:radio[name=types]:checked').val());
        formData.append('characteristics[]', $('input:radio[name=level]:checked').val());
        message = translations['message_route'];
        // var type_schedule_word = (type_schedule == 1)?'recurrent':'sporadic';
        // for(let i of formData.entries()) {
        //     console.log(i[0] + ' ' + i[1])
        // }
        $.ajax({
            url: url_place,
            headers: authorization(),
            type: method_form,
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(data) {
                $('#loading_modal').modal('show')
            },
            statusCode: {
                201: function(data) {
                    $('#loading_modal').modal('hide')
                    $('form .well').empty('')
                    $('form .well').css('display', 'none')
                    $.niftyNoty({
                        type: "success",
                        container: "floating",
                        html: default_prefix+''+message,
                        closeBtn: true,
                        floating: {
                            position: "bottom-right",
                            animationIn: "jellyIn",
                            animationOut: "fadeOut"
                        },
                        focus: true,
                        timer: 2500
                    });
                    $("#form_site input[type='text']").val('')
                    $("#form_site textarea").val('')
                    $("#form_site input[type='number']").val('')
                    $("#form_site input[type='radio']").prop('checked', false)
                    $("#form_site input[type='checkbox']").prop('checked', false)
                    $('.note-editable').eq(0).html('')
                    $('.note-editable').eq(1).html('')
                    markers= []
                    // $('.schedules').html('');
                    // if ($('#type_event').prop('checked') == true && $('#type_event').attr('disabled') == "disabled") {
                    //     $('#type_event').trigger('click');
                    //     $('#type_event').removeAttr('disabled')
                    // }
                    // for (var j = 0; j < days.length; j++) {
                    //     $('option[value="'+days[j]+'"]').removeAttr('disabled');
                    // }
                    // $('option[value="Lun-Dom"]').removeAttr('disabled');
                    // $('option[value="Mon-Sun"]').removeAttr('disabled');
                    mymap.removeLayer(marker)
                    marker = undefined
                    Dropzone.forElement("div#dropzoneFiles").removeAllFiles(true)
                    $("#submit_btn").removeAttr('disabled')
                },
                400: function(data) {
                    $('#loading_modal').modal('hide')
                    var response = data.responseJSON
                    var errors = response.message
                    $('form .well').html('')
                    for (var indice in errors) {
                        $('form .well').append('<p>'+errors[indice]+'</p>')
                    }
                    $('form .well').css('display', 'block')
                    $("#submit_btn").removeAttr('disabled')
                },
                404: function(data) {
                    $('#loading_modal').modal('hide')
                    var response = data.responseJSON
                    var errors = response.message
                    $('form .well').html('')
                    for (var indice in errors) {
                        $('form .well').append('<p>'+errors[indice]+'</p>')
                    }
                    $('form .well').css('display', 'block')
                    $("#submit_btn").removeAttr('disabled')
                },
                401: function(data) {
                    $('#loading_modal').modal('hide')
                    $.niftyNoty({
                        type: "danger",
                        container: "floating",
                        html: data.responseJSON.message,
                        closeBtn: true,
                        floating: {
                            position: "bottom-right",
                            animationIn: "jellyIn",
                            animationOut: "fadeOut"
                        },
                        focus: true,
                        timer: 2500
                    })
                    setTimeout(function() {
                        window.history.back()
                    }, 3000)
                }
            }
        });
    } else if (marker == undefined && files_in_dropzone == 0 && fields_in_form == false) {
        $('form .well').css('display', 'block');
        $('form .well').html(translations['message_no_marker_no_images_no_form']);
    } else if (marker == undefined && files_in_dropzone < 2 && fields_in_form == true) {
        $('form .well').css('display', 'block');
        $('form .well').html(translations['message_no_marker_no_images_form']);
    } else if (marker == undefined && files_in_dropzone >= 2 && fields_in_form == false) {
        $('form .well').css('display', 'block');
        $('form .well').html(translations['message_no_marker_images_no_form']);
    } else if (marker == undefined && files_in_dropzone >= 2 && fields_in_form == true) {
        $('form .well').css('display', 'block');
        $('form .well').html(translations['message_no_marker_images_form']);
    } else if (marker != undefined && files_in_dropzone < 2  && fields_in_form == false) {
        $('form .well').css('display', 'block');
        $('form .well').html(translations['message_marker_no_images_no_form']);
    } else if (marker != undefined && files_in_dropzone < 2 && fields_in_form == true) {
        $('form .well').css('display', 'block');
        $('form .well').html(translations['message_marker_no_images_form']);
    } else if (marker != undefined && files_in_dropzone >= 2 && fields_in_form == false) {
        $('form .well').css('display', 'block');
        $('form .well').html(translations['message_marker_images_no_form']);
    }
    // if (schedule.length == 0) {
    //     $('.well-schedule').css('display', 'block');
    //     $('.well-schedule').html(translations['message_schedule']);
    // } else if (schedule.length > 0) {
    //     $('.well-schedule').css('display', 'none');
    //     $('.well-schedule').html('');
    // }
});


function latlngInput(markers, marker_dragend) 
{
    var marker = markers[marker_dragend].getLatLng()
    var i = marker_dragend - 2
    
    if(marker_dragend == 0) { 
        $('#latitude_exit').val(marker.lat.toFixed(4))
        $('#longitude_exit').val(marker.lng.toFixed(4))
    }

    if(marker_dragend == 1) { 
        $('#latitude_arrival').val(marker.lat.toFixed(4))
        $('#longitude_arrival').val(marker.lng.toFixed(4))
    }

    if(marker_dragend > 1) { 
        $('#name_lat_station_' + i).val(marker.lat.toFixed(4))
        $('#name_lng_station_' + i).val(marker.lng.toFixed(4))
    }

}

function updateCoordenate()
{
    var markers_bound= []

    for (var i = 0; i < markers.length; i++) {
        let position = markers[i].getLatLng()
        let array = []
        if(i == 0) {
            if(position.lat.toFixed(4) != $('#latitud_exit').val() || position.lng.toFixed(4) != $('#longitud_exit').val()) {
                markers[i].getLatLng().lat = parseFloat($('#latitude_exit').val())
                markers[i].getLatLng().lng = parseFloat($('#longitude_exit').val())
            }
        }

        if(i == 1) {
            if(position.lat.toFixed(4) != $('#latitud_arrival').val() || position.lng.toFixed(4) != $('#longitud_arrival').val()) {
                markers[i].getLatLng().lat = parseFloat($('#latitude_arrival').val())
                markers[i].getLatLng().lng = parseFloat($('#longitude_arrival').val())
            }
        }

        if(i > 1) {
            if(position.lat.toFixed(4) != $('#name_lat_station_' + (i - 2).toString()).val() || position.lat.toFixed(4) != $('#name_lng_station_' + (i - 2).toString()).val()) {
                markers[i].getLatLng().lat = parseFloat($('#name_lat_station_' + (i - 2).toString()).val())
                markers[i].getLatLng().lng = parseFloat($('#name_lng_station_' + (i - 2).toString()).val())
            } 
        }
        array[0] = position.lat.toFixed(4) 
        array[1] = position.lng.toFixed(4) 
        markers_bound.push(array)
    }
    mymap.fitBounds(markers_bound);
}