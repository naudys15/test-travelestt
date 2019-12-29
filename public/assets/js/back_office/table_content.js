if (jQuery.fn.bootstrapTable != undefined) {
    jQuery.fn.bootstrapTable.defaults.icons = {
        paginationSwitchDown: 'demo-pli-arrow-down',
        paginationSwitchUp: 'demo-pli-arrow-up',
        refresh: 'demo-pli-repeat-2',
        toggle: 'demo-pli-layout-grid',
        columns: 'demo-pli-check',
        detailOpen: 'demo-psi-add',
        detailClose: 'demo-psi-remove'
    }
}

// BOOTSTRAP TABLE - CUSTOM TOOLBAR
// =================================================================
// Require Bootstrap Table
// http://bootstrap-table.wenzhixin.net.cn/
// =================================================================
var $table = $('#table_content'),
    $remove = $('#btn_delete_rows');

$table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
    $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
});

function setBootstrapTable(entity) {
    var columns
    if (entity == 'countries') {
        if (language == 'es') {
            columns = ['Id', 'ISO', 'Nombre'];
        }
        if (language == 'en') {
            columns = ['Id', 'ISO', 'Name'];
        }
        if (language == 'fr') {
            columns = ['Id', 'ISO', 'Nom'];
        }
        $table.bootstrapTable({
            pagination: true,
            search: true,
            columns: [{
                field: 'check',
                title: ''
            }, {
                field: 'id',
                title: 'Id',
            }, {
                field: 'iso',
                title: columns[1]
            }, {
                field: 'name',
                title: columns[2]
            }, {
                field: 'actions',
                title: ''
            }],
        })
    } else if (entity == 'provinces') {
        if (language == 'es') {
            columns = ['Id', 'Nombre'];
        }
        if (language == 'en') {
            columns = ['Id', 'Name'];
        }
        if (language == 'fr') {
            columns = ['Id', 'Nom'];
        }
        $table.bootstrapTable({
            pagination: true,
            search: true,
            columns: [{
                field: 'check',
                title: ''
            }, {
                field: 'id',
                title: 'Id',
            }, {
                field: 'name',
                title: columns[1]
            }, {
                field: 'actions',
                title: ''
            }],
        })
    } else if (entity == 'cities') {
        if (language == 'es') {
            columns = ['Id', 'Nombre', 'Destacado', 'Top Destino' ];
        }
        if (language == 'en') {
            columns = ['Id', 'Name', 'Outstanding', 'Top Destination'];
        }
        if (language == 'fr') {
            columns = ['Id', 'Nom', 'En Vedette', 'Première Destination'];
        }
        $table.bootstrapTable({
            pagination: true,
            search: true,
            columns: [{
                field: 'check',
                title: ''
            }, {
                field: 'id',
                title: 'Id',
            }, {
                field: 'name',
                title: '<div class="d-flex justify-content-center">'+columns[1]+'</div>'
            }, {
                field: 'outstanding',
                title: '<div class="d-flex justify-content-center">'+columns[2]+'</div>'
            }, {
                field: 'top_destination',
                title: '<div class="d-flex justify-content-center">'+columns[3]+'</div>'
            }, {
                field: 'actions',
                title: ''
            }],
        })
    } else if (entity == 'coasts' || entity == 'festivals' || entity == 'museums' || entity == 'points_of_interest' || entity == 'routes' || entity == 'night_spots' || entity == 'street_markets' || entity == 'shows' || entity == 'natural_spaces' || entity == 'experiences') {
        if (language == 'es') {
            columns = ['Id', 'Nombre', 'Estado', 'Ciudad', 'Estatus', 'Destacado'];
        }
        if (language == 'en') {
            columns = ['Id', 'Name', 'State', 'City', 'Status', 'Outstanding'];
        }
        if (language == 'fr') {
            columns = ['Id', 'Nom', 'Été', 'Ville', 'Statut' , 'En Vedette'];
        }
        $table.bootstrapTable({
            pagination: true,
            search: true,
            columns: [{
                field: 'check',
                title: ''
            }, {
                field: 'id',
                title: 'Id',
            }, {
                field: 'name',
                title: '<div class="d-flex justify-content-center">'+columns[1]+'</div>'
            }, {
                field: 'state',
                title: '<div class="d-flex justify-content-center">'+columns[2]+'</div>'
            }, {
                field: 'city',
                title: '<div class="d-flex justify-content-center">'+columns[3]+'</div>'
            }, {
                field: 'status',
                title: '<div class="d-flex justify-content-center">'+columns[4]+'</div>'
            }, {
                field: 'outstanding',
                title: '<div class="d-flex justify-content-center">'+columns[5]+'</div>'
            }, {
                field: 'actions',
                title: ''
            }],
        })
    }
}

function loadMarkers(marcadores) {
    setTranslations();
    var band = false,
        band_two = false;
    var first_marker_route = "";
    var latitude_route = "";
    var longitude_route = "";
    var separated_markers = marcadores.split(':');
    if (separated_markers.length > 0) {
        first_marker_route = separated_markers[0].split(',');
        latitude_route = first_marker_route[0];
        longitude_route = first_marker_route[1];
        for (var i = 0; i < separated_markers.length; i++) {
            var marker_values = separated_markers[i].split(',');
            var latitude = marker_values[0];
            var longitude = marker_values[1];
            var popup = translations['coordinates'] + latitude + "," + longitude;
            if (band == false) {
                mymap.flyTo([latitude, longitude], 14);
                band = true;
            }
            marker = L.marker([latitude, longitude], {
                draggable: true,
                autoPan: true
            }).bindPopup(popup);

            if (typeof (markers) !== 'undefined') {
                markers.push(marker);
            }
            if (separated_markers.length > 1) {
                latitudes.push(latitude);
                longitudes.push(longitude);
                long_markers = markers.length;
                markers[i].on('dragend', function (event) {
                    let marker = event.target;
                    let position = marker.getLatLng();
                    var element_update_pos = 0;
                    var popUp = translations['coordinates'] + position.lat.toString() + "," + position.lng.toString();
                    for (var j = 0; j < markers.length; j++) {
                        let position2 = markers[j].getLatLng();
                        if (position.lat.toString() == position2.lat.toString() && position.lng.toString() == position2.lng.toString()) {
                            element_update_pos = j;
                            break;
                        }
                    }
                    latitudes[element_update_pos] = marker.getLatLng().lat.toString();
                    longitudes[element_update_pos] = marker.getLatLng().lng.toString();
                    if (element_update_pos == 0) {
                        getDirectionFromCoordinate(marker.getLatLng().lat.toString(), marker.getLatLng().lng.toString());
                    }
                    marker.setLatLng(position, {
                        draggable: true
                    }).bindPopup(popUp).update();
                    getElevationFromApi(position.lat.toString(), position.lng.toString(), 'tHXSNAGXRx6LAoBNdgjjLycOhGqJalg7', (element_update_pos));
                });
                markers[i].on('mousedown', function (event) {
                    esp_markers[i] = setTimeout(function () {
                        let marker = event.target;
                        let position2 = marker.getLatLng();
                        for (var j = 0; j < markers.length; j++) {
                            let position = markers[j].getLatLng();
                            if (position.lat.toString() == position2.lat.toString() && position.lng.toString() == position2.lng.toString()) {
                                marker.removeFrom(mymap);
                                markers.splice(j, 1);
                                latitudes.splice(j, 1);
                                longitudes.splice(j, 1);
                                elevations.splice(j, 1);
                                break;
                            }
                        }
                        if (cant_descrip_puntos_interes != 0) {
                            $('#name_' + (cant_descrip_puntos_interes - 1)).remove();
                            cant_descrip_puntos_interes--;
                        }
                        if (cant_descrip_puntos_interes == 0) {
                            $('#points_description').empty();
                        }
                    }, 2000);
                });
                markers[i].on('mouseup', function (event) {
                    clearTimeout(esp_markers[i]);
                });
                let pos = marker.getLatLng();
                getElevationFromApi(pos.lat.toString(), pos.lng.toString(), 'tHXSNAGXRx6LAoBNdgjjLycOhGqJalg7');
                marker.addTo(mymap);
                if (markers.length > 2) {
                    $('#points_description').append('<div class="row" id="name_' + cant_descrip_puntos_interes + '" style="margin-bottom:4%"><label class="col-md-3 control-label" for="stations_name[]">Nombre de la estación</label><div class="col-md-9"><input required type="text" id="stations_name_' + cant_descrip_puntos_interes + '" class="station_name form-control" placeholder="Nombre de la estación ' + (cant_descrip_puntos_interes + 1) + '" name="stations_name[]"></div></div>');
                    //$('#points_description').append('<div class="row" id="description_'+cant_descrip_puntos_interes+'" style="margin-bottom:4%"><label class="col-md-3 control-label" for="stations_information[]">Información de la estación</label><div class="col-md-9"><input required type="text" id="stations_information_'+cant_descrip_puntos_interes+'" class="form-control" placeholder="Información de la estación '+(cant_descrip_puntos_interes+1)+'" name="stations_information[]"></div></div>');
                    cant_descrip_puntos_interes++;
                }
            } else {
                marker.on('dragend', function (event) {
                    let marker = event.target;
                    let position = marker.getLatLng();
                    var popup = translations['coordinates'] + position.lat.toString() + "," + position.lng.toString();
                    getDirectionFromCoordinate(position.lat.toString(), position.lng.toString());
                    marker.setLatLng(position, {
                        draggable: true
                    }).bindPopup(popup).update();
                });
                mymap.addLayer(marker);
            }
        }
    } else {
        var marker_values = marcadores.split(',');
        var latitude = marcadores;
        var longitude = marcadores;
        var popup = translations['coordinates'] + latitude + "," + longitude;
        //getDirectionFromCoordinate(latitude, longitude);
        mymap.flyTo([latitude, longitude], 14);
        marker = L.marker([latitude, longitude], {
            draggable: true,
            autoPan: true
        }).bindPopup(popup);
        marker.addTo(mymap);
    }
    $('#eventMarker').attr('onclick', '');
}

function loadImages(place, images) {
    setTranslations();
    var place_url = '';
    var base_url = window.location.origin;
    if (place == 'playas') {
        place_url = 'coasts/';
    } else if (place == 'festivales') {
        place_url = 'festivals/';
    } else if (place == 'sitios_nocturnos') {
        place_url = 'night_spots/';
    } else if (place == 'museos') {
        place_url = 'museums/';
    } else if (place == 'puntos_de_interes') {
        place_url = 'points_of_interest/';
    } else if (place == 'mercadillos') {
        place_url = 'street_markets/';
    } else if (place == 'rutas') {
        place_url = 'routes/';
    }
    if (base_url.indexOf('localhost') > 0) {
        base_url += '/turismotoledo';
    }
    if (images != '') {
        var separated_images = images.split(',');
        for (var i = 0; i < separated_images.length; i++) {
            var nombre = separated_images[i];
            var ruta = "assets/images/" + place_url + '' + nombre;
            //var mockFile = { name: nombre, size: '1000', type: 'image/jpg',complete:'true'};
            var mockFile = {
                name: nombre,
                size: '1000',
                type: 'image/jpg'
            };
            // add to files array
            Dropzone.forElement("div#dropzoneFiles").emit("addedfile", mockFile);
            Dropzone.forElement("div#dropzoneFiles").emit("thumbnail", mockFile, base_url + '/' + ruta);
            //Dropzone.forElement("div#dropzoneFiles").emit("complete", mockFile);
            //Dropzone.forElement("div#dropzoneFiles").emit("success", mockFile);
            Dropzone.forElement("div#dropzoneFiles").files.push(mockFile);
            count_files_in_dropzone++;
            // The number of files already uploaded
            Dropzone.forElement("div#dropzoneFiles").options.maxFiles--;
        }
    }
    $('#eventImage').attr('onclick', '');
}

function loadDescriptions(short, long) {
    //Español
    $('.note-editable').eq(0).html(short);
    $('.note-editable').eq(1).html(long);
    //Inglés
    // $('.note-editable').eq(2).html(short);
    // $('.note-editable').eq(3).html(long);
    //Francés
    // $('.note-editable').eq(4).html(short);
    // $('.note-editable').eq(5).html(long);
    $('#eventDescription').attr('onclick', '');
}

function loadSchedules(type_schedule, schedule_to_load) {
    $('#eventSchedule').attr('onclick', '');
    if (type_schedule == "recurrent") {
        $('#type_event').trigger('click');
    }
    var schedules = schedule_to_load.split("/");
    for (var i = 0; i < schedules.length; i++) {
        var new_schedule = "";
        var split_schedule = schedules[i].split(" ");

        var split_opened_hour = split_schedule[1].split(":");
        var opened_hour_in_military_hour = 0;
        var opened_hour_ampm = (split_opened_hour[0] >= 12) ? 'PM' : 'AM';
        var new_opened_hour = split_opened_hour[0] + ":" + split_opened_hour[1] + " " + opened_hour_ampm;
        opened_hour_in_military_hour = split_opened_hour[0] + ":" + split_opened_hour[1] + " " + opened_hour_ampm;
        var object_date = new Date("1/1/2013 " + opened_hour_in_military_hour);
        opened_hour_in_military_hour = object_date.getHours() + ':' + object_date.getMinutes();

        var split_closed_hour = split_schedule[2].split(":");
        var closed_hour_in_military_hour = 0;
        var closed_hour_ampm = (split_closed_hour[0] >= 12) ? 'PM' : 'AM';
        var new_closed_hour = split_closed_hour[0] + ":" + split_closed_hour[1] + " " + closed_hour_ampm;
        closed_hour_in_military_hour = split_closed_hour[0] + ":" + split_closed_hour[1] + " " + closed_hour_ampm;
        var object_date = new Date("1/1/2013 " + closed_hour_in_military_hour);
        closed_hour_in_military_hour = object_date.getHours() + ':' + object_date.getMinutes();

        opened_hours[num_schedule] = new_opened_hour;
        closed_hours[num_schedule] = new_closed_hour;

        if (type_schedule == "sporadic") {
            var split_date = split_schedule[0].split("-");
            var new_date = split_date[2] + "-" + split_date[1] + "-" + split_date[0];
            new_schedule = "<div class='row mar-btm schedule'><div class='col-xs-11'><span class='text-muted'>" + new_date + ", " + new_opened_hour + " - " + new_closed_hour + "</span></div><div class='col-xs-1 text-center'><a href='#' class='btn-link btn_remove_schedule' data-id='" + new_date + "'><i class='ti-trash'></i></a></div></div>";
            date_schedule[num_schedule] = new_date;
            schedule[num_schedule] = new_date + " " + opened_hour_in_military_hour + ' ' + closed_hour_in_military_hour;
            dates_omitted[num_schedule] = new_date;
            num_schedule++;
        } else {
            var day = "";
            for (var k = 0; k < english_days_to_use_in_schedules.length; k++) {
                if (split_schedule[0].indexOf(english_days_to_use_in_schedules[k]) == 0) {
                    day = days[k];
                    break;
                }
            }
            if (day == "Lun" || day == "Mon") {
                $('.btn_add_schedule').attr('disabled', 'disabled');
            }
            $('option[value="' + day + '"]').attr('disabled', 'disabled');
            new_schedule = "<div class='row mar-btm schedule'><div class='col-xs-11'><span class='text-muted'>" + day + ", " + new_opened_hour + " - " + new_closed_hour + "</span></div><div class='col-xs-1 text-center'><a href='#' class='btn-link btn_remove_schedule' data-id='" + day + "'><i class='ti-trash'></i></a></div></div>";
            day_of_schedule[num_schedule] = day;
            schedule[num_schedule] = day_of_schedule[num_schedule] + ' ' + opened_hour_in_military_hour + ' ' + closed_hour_in_military_hour;
            num_schedule++;
            var all_schedule = $('option[value="Lun-Dom"]').attr('disabled');
            var all_schedule_two = $('option[value="Mon-Sun"]').attr('disabled');
            if (all_schedule == undefined) {
                $('option[value="Lun-Dom"]').attr('disabled', 'disabled');
            }
            if (all_schedule_two == undefined) {
                $('option[value="Mon-Sun"]').attr('disabled', 'disabled');
            }
        }
        $('.schedules').append(new_schedule);
        $('#type_event').attr('disabled', 'disabled');
    }
}

function erasePlace(place) {
    setTranslations()
    var message_delete = ''
    if (place == 'coasts') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_coast']
    } else if (place == 'festivals') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_festival']
    } else if (place == 'night_spots') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_night']
    } else if (place == 'museums') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_museum']
    } else if (place == 'points_of_interest') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_point_of_interest']
    } else if (place == 'street_markets') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_street_market']
    } else if (place == 'routes') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_route']
    } else if (place == 'shows') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_show']
    } else if (place == 'natural_spaces') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_natural_space']
    } else if (place == 'experiences') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_experience']
    } else if (place == 'countries') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_country']
    } else if (place == 'provinces') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_province']
    } else if (place == 'cities') {
        message_delete = translations['message_result_delete'] + ' ' + translations['message_city']
    }
    var selected_data = []
    var selected_rows = $('#table_content tr.selected')
    selected_rows.each(function () {
        selected_data.push($(this).children('td:nth-child(2)').html())
    })
    for (var i = 0; i < selected_data.length; i++) {
        var delete_url = '/api/' + place + '/' + selected_data[i]
        $.ajax({
            url: delete_url,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem("travelesttAccess"),
                'X-CSRF-Token': $('meta[name="csrf-token"').attr('content')
            },
            beforeSend: function (data) {
                $('#loading_modal').modal('show')
            },
            type: "DELETE",
            success: function (response) {
                $('#loading_modal').modal('hide')
                var $table = $('#table_content')
                var $remove = $('#btn_delete_rows')
                var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
                    return row.id
                });
                $table.bootstrapTable('remove', {
                    field: 'id',
                    values: ids
                });
                // $('#table_content tr').each(function () {
                //     if ($('th:nth-child(2)').length > 0) {
                //         $('th:nth-child(2)').css('display', 'none')
                //     }
                //     if ($('td:nth-child(2)').length > 0) {
                //         $('td:nth-child(2)').css('display', 'none')
                //     }
                // })
                $remove.prop('disabled', true);
                $.niftyNoty({
                    type: "success",
                    container: "floating",
                    html: message_delete,
                    closeBtn: true,
                    floating: {
                        position: "bottom-right",
                        animationIn: "jellyIn",
                        animationOut: "fadeOut"
                    },
                    focus: true,
                    timer: 2500
                });
            },
            error: function (response) {
                $('#loading_modal').modal('hide')
                response = response.responseJSON
                $.niftyNoty({
                    type: "danger",
                    container: "floating",
                    html: response.message,
                    closeBtn: true,
                    floating: {
                        position: "bottom-right",
                        animationIn: "jellyIn",
                        animationOut: "fadeOut"
                    },
                    focus: true,
                    timer: 2500
                });
            }
        });
    }
}
