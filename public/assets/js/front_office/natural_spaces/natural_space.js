setLanguage()
$.ajax({
    async: false,
    url: location.origin+"/api/natural_spaces/slug/"+location.href.split('/').pop(),
    type: 'GET',
    dataType: 'json',
    headers :authorization(),
    statusCode: {
        200: function(data) {
            let resource = data.message[0]
            if ($('#comment_form').length > 0) {
                $('#comment_form').attr('href', location.origin+'/api/natural_spaces/'+resource.id+'/comment')
                $('#comment_form').attr('action', 'POST')
            }
            for (i = 0; i < resource.media.length; i++) {
                var imagen = location.origin+"/assets/images/natural_spaces/"+resource.media[i];
                if (i == 0) {
                    $('#first_img').css('background-image','url('+imagen+')')
                    $('#first_img a').attr('href', imagen)
                    $('#first_img a img').attr('src', imagen)
                }
                if (i == 1) {
                    $('#second_img').css('background-image','url('+imagen+')')
                    $('#second_img a').attr('href', imagen)
                    $('#second_img a img').attr('src', imagen)
                }
                if (i == 2) {
                    $('#third_img').css('background-image','url('+imagen+')')
                    $('#third_img a').attr('href', imagen)
                    $('#third_img a img').attr('src', imagen)
                }
                if (i == 3) {
                    $('#fourth_img').css('background-image','url('+imagen+')')
                    $('#fourth_img a').attr('href', imagen)
                    $('#fourth_img a img').attr('src', imagen)
                }
                if (i == 4) {
                    $('#fifth_img').css('background-image','url('+imagen+')');
                    $('#fifth_img a').attr('href', imagen);
                    $('#fifth_img a img').attr('src', imagen);
                }
            }
            if (resource.valorations) {
                if (resource.valorations.ratings == 1) {
                    $('.valoration').append('<div class="text-secondary ml-1"><small>'+resource.valorations.ratings+translations['opinion']+'</small></div>')
                } else {
                    $('.valoration').append('<div class="text-secondary ml-1"><small>'+resource.valorations.ratings+translations['opinions']+'</small></div>')
                }
            }
            $('#city').html(resource.location.city.name)
            if (resource.translations[language] != undefined ) {
                $('html>head>title').html(translations['natural_spaces']+' | '+resource.translations[language].name)
                $('#place_name').html(resource.translations[language].name)
                $('#place_address').html(resource.translations[language].address)
                $('#place_short_description').html(resource.translations[language].short_description)
            } else {
                window.history.back()
            }
            var mymap
            var marker
            var lat = resource.location.latitude
            var lng = resource.location.longitude
            var myCustomColour = '#2E85CB'
            var markerHtmlStyles = `
                background-color: ${myCustomColour};
                width: 2rem;
                height: 2rem;
                display: block;
                left: -0.9rem;
                top: -2rem;
                position: relative;
                border-radius: 3rem 3rem 0;
                transform: rotate(45deg);
                border: 1px solid #FFFFFF`
            var myIcon = L.divIcon({
                className: "my-custom-pin",
                iconAnchor: [0, 6],
                labelAnchor: [-3, 0],
                popupAnchor: [0, -9],
                html: `<span style="${markerHtmlStyles}" />`
            })
            mymap = L.map('map_destino').setView([lat, lng], 14);
            L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap)
            mymap.touchZoom.disable()
            mymap.doubleClickZoom.disable()
            mymap.scrollWheelZoom.disable()
            mymap.dragging.disable()
            mymap.boxZoom.disable()
            mymap.keyboard.disable()
            $(".leaflet-control-zoom").css("visibility", "hidden")
            marker = new L.marker([lat,lng], {
                draggable: false,
                autoPan: true,
                zoomControl: false,
                icon: myIcon,
            }).bindPopup("Coordenadas: <br> " + lat + "," + lng)
            mymap.addLayer(marker)
            var user = (!localStorage.getItem('travelesttUser')) ? false : JSON.parse(descrypt(localStorage.getItem('travelesttUser')))
            $.ajax({
                async: false,
                url: location.origin+"/api/natural_spaces/"+resource.id+"/comments",
                type: 'GET',
                dataType: 'json',
                statusCode: {
                    200: function(data) {
                        let valorations = data.message
                        let check_user = false
                        let comments = ''
                        $.each(valorations, function(a, b){
                            if (b.content) {
                                comments += '<div class="row"><div class="col-12 opinion_header d-flex mb-3 align-items-center"><div class="opinion_img mr-2">'
                                if (b.user.image != null) {
                                    let route_image = routeImage('users')
                                    comments += '<img class="img-fluid avatar" src="'+ route_image + b.user.image +'"></div>'
                                } else {
                                    comments += '<span class="img-fluid">'+initialString(b.user.name)+'</span></div>'
                                }
                                comments += '<div class="text-main"><p class="mb-0">'+b.user.name+' <small class="text-secondary">'
                                if (b.minutes > 0) {
                                    comments += translations['ago']
                                    if (b.months > 0) {
                                        if (b.years > 0) {
                                            if (b.years == 1) {
                                                comments += b.years+translations['year']+', '
                                            } else {
                                                comments += b.years+translations['years']+', '
                                            }
                                        }
                                        if (b.months > 0) {
                                            if (b.months == 1) {
                                                comments += b.months+translations['month']+', '
                                            } else {
                                                comments += b.months+translations['months']+', '
                                            }
                                        }
                                        if (b.days > 0) {
                                            if (b.days == 1) {
                                                comments += b.days+translations['day']
                                            } else {
                                                comments += b.days+translations['days']
                                            }
                                        }
                                    } else {
                                        if (b.days > 0) {
                                            if (b.days == 1) {
                                                comments += b.days+translations['day']+', '
                                            } else {
                                                comments += b.days+translations['days']+', '
                                            }
                                        }
                                        if (b.hours > 0) {
                                            if (b.hours == 1) {
                                                comments += b.hours+translations['hour']+', '
                                            } else {
                                                comments += b.hours+translations['hours']+', '
                                            }
                                        }
                                        if (b.minutes > 0) {
                                            if (b.minutes == 1) {
                                                comments += b.minutes+translations['minute']
                                            } else {
                                                comments += b.minutes+translations['minutes']
                                            }
                                        }
                                    }
                                } else {
                                    comments += translations['now']
                                }
                                comments += '</small></p><span><strong>'+b.title+' </strong></span></div>'
                                comments += '<div class="valoracion ml-auto"><i class="fas fa-star mr-1 text-warning"></i><i class="fas fa-star mr-1 text-warning"></i><i class="fas fa-star mr-1 text-warning"></i><i class="fas fa-star mr-1 text-warning"></i><i class="fas fa-star mr-1 text-warning"></i></div></div>'
                                comments += '<div class="col-12 opinion_body"><p class="text-secondary">'+b.content+'</p></div></div>'
                            }
                            if (user) {
                                if (b.user.email == user.email && !check_user) {
                                    check_user = true
                                }
                            }
                        })
                        if (!user) {
                            $('#comment_form').css('display', 'none')
                        } else {
                            if (check_user) {
                                $('#comment_form').css('display', 'none')
                            }
                        }
                        $('.valorations').append(comments)
                    },
                    404: function() {
                        if (!user) {
                            $('.comment_form').css('display', 'none')
                        }
                    }
                }
            })
        }
    }
});

var onSubmitCommentForm = function()
{
    handlerCommentResponse()
}