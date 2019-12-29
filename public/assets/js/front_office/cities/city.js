setLanguage()
var slug = location.href.split('/').pop()
var city
var coasts, festivals, museums, night_spots, point_of_interest, routes, street_markets
var resoruce = []

$.ajax({
    async: false,
    url: "/api/cities/slug/"+slug,
    type: 'GET',
    dataType: 'json',
    statusCode: {
        200: function(data) {
            city = data.message[0]
            $('html>head>title').html('Travelestt | '+city.name)
            $('.hero_content>h1').html(city.name)
            $('.hero_content>span').html(city.country.name)
            let placebolder = $('#search_hero_input').attr('placeholder')
            placebolder += city.name+'?'
            $('#search_hero_input').attr('placeholder', placebolder)
            if (city.image != null) {
                changeImage(city.image)
            }
            let title_sections = $('#lugares_ciudad h2')
            $.each(title_sections, function(a, b){
                let content = $(b).html()
                $(b).html(content+city.name)
            })
        },
        404: function(data) {
            // console.log(data)
            window.history.back()
        }
    }
})

var mapa_cerca_de_mi = $('#mapid')
if (mapa_cerca_de_mi.length > 0) {
    var mymap = L.map('mapid').setView([city.latitude, city.longitude], 14)
    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mymap);
}
$.ajax({
    async: false,
    url: "/api/cities/"+city.id+"/resources",
    type: 'GET',
    dataType: 'json',
    statusCode: {
        200: function(data) {
            let content = false
            let resoruces = data.message
            $.each(resoruces, function(a, b){
                let resource = $('#'+a)
                let i = 0
                if (resource.length > 0) {
                    $.each(b, function(c, d){
                        if (d.translations[language] != undefined) {
                            let output = '<div class="destino mb-3"><div class="destino_img position-relative">'
                            if (a == 'coasts') {
                                output += '<a href="'+location.origin+'/'+language+'/'+city.slug+'/'+translations['url_coasts']+'/'+d.slug+'">'
                                output += '<div class="bg_img" style="height:200px; background-image: url('+location.origin+'/assets/images/coasts/'+d.media[0]+')"></div><div class="destino_info mt-1 d-flex p-3"><div><p class="font-weight-bold mb-0 text-dark">'+d.translations[language].name+'</p>'
                            } else if (a == 'festivals') {
                                if (d.media[0].indexOf('festival') > -1) {
                                    output += '<a href="'+location.origin+'/'+language+'/'+city.slug+'/'+translations['url_festivals']+'/'+d.slug+'">'
                                    output += '<div class="bg_img" style="height:200px; background-image: url('+location.origin+'/assets/images/festivals/'+d.media[0]+')"></div><div class="destino_info mt-1 d-flex p-3"><div><p class="font-weight-bold mb-0 text-dark">'+d.translations[language].name+'</p>'
                                } else if (d.media[0].indexOf('show') > -1) {
                                    output += '<a href="'+location.origin+'/'+language+'/'+city.slug+'/'+translations['url_shows']+'/'+d.slug+'">'
                                    output += '<div class="bg_img" style="height:200px; background-image: url('+location.origin+'/assets/images/shows/'+d.media[0]+')"></div><div class="destino_info mt-1 d-flex p-3"><div><p class="font-weight-bold mb-0 text-dark">'+d.translations[language].name+'</p>'
                                }
                            } else if (a == 'museums') {
                                output += '<a href="'+location.origin+'/'+language+'/'+city.slug+'/'+translations['url_museums']+'/'+d.slug+'">'
                                output += '<div class="bg_img" style="height:200px; background-image: url('+location.origin+'/assets/images/museums/'+d.media[0]+')"></div><div class="destino_info mt-1 d-flex p-3"><div><p class="font-weight-bold mb-0 text-dark">'+d.translations[language].name+'</p>'
                            } else if (a == 'night_spots') {
                                output += '<a href="'+location.origin+'/'+language+'/'+city.slug+'/'+translations['url_night_spots']+'/'+d.slug+'">'
                                output += '<div class="bg_img" style="height:200px; background-image: url('+location.origin+'/assets/images/night_spots/'+d.media[0]+')"></div><div class="destino_info mt-1 d-flex p-3"><div><p class="font-weight-bold mb-0 text-dark">'+d.translations[language].name+'</p>'
                            } else if (a == 'points_of_interest') {
                                output += '<a href="'+location.origin+'/'+language+'/'+city.slug+'/'+translations['url_points_of_interest']+'/'+d.slug+'">'
                                output += '<div class="bg_img" style="height:200px; background-image: url('+location.origin+'/assets/images/points_of_interest/'+d.media[0]+')"></div><div class="destino_info mt-1 d-flex p-3"><div><p class="font-weight-bold mb-0 text-dark">'+d.translations[language].name+'</p>'
                            } else if (a == 'routes_hiker' || a == 'routes_btt' || a == 'routes_highway') {
                                output += '<a href="'+location.origin+'/'+language+'/'+city.slug+'/'+translations['url_routes']+'/'+d.slug+'">'
                                output += '<div class="bg_img" style="height:200px; background-image: url('+location.origin+'/assets/images/routes/'+d.media[0]+')"></div><div class="destino_info mt-1 d-flex p-3"><div><p class="font-weight-bold mb-0 text-dark">'+d.translations[language].name+'</p>'
                            } else if (a == 'street_markets') {
                                output += '<a href="'+location.origin+'/'+language+'/'+city.slug+'/'+translations['url_street_markets']+'/'+d.slug+'">'
                                output += '<div class="bg_img" style="height:200px; background-image: url('+location.origin+'/assets/images/street_markets/'+d.media[0]+')"></div><div class="destino_info mt-1 d-flex p-3"><div><p class="font-weight-bold mb-0 text-dark">'+d.translations[language].name+'</p>'
                            } else if (a == 'experiences') {
                                output += '<a href="'+location.origin+'/'+language+'/'+city.slug+'/'+translations['url_experiences']+'/'+d.slug+'">'
                                output += '<div class="bg_img" style="height:200px; background-image: url('+location.origin+'/assets/images/experiences/'+d.media[0]+')"></div><div class="destino_info mt-1 d-flex p-3"><div><p class="font-weight-bold mb-0 text-dark">'+d.translations[language].name+'</p>'
                            }
                            if (d.valorations) {
                                if (d.valorations.ratings == 1) {
                                    output += '<div class="text-secondary"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>'+d.valorations.ratings+translations['opinion']+'</small></div>'
                                } else {
                                    output += '<div class="text-secondary"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>'+d.valorations.ratings+translations['opinions']+'</small></div>'
                                }
                            } else {
                                output += '<div class="text-secondary"><small><span class="calificacion mr-1"></span></small></div>'
                            }
                            output += '</div><div class="precio ml-auto align-self-center mr-2 text-main"><strong>120$</strong></div></div><div class="destino_resume position-absolute text-white p-3 d-flex flex-column justify-content-end"><p class="font-weight-bold mb-1">'+d.translations[language].name+'</p>'
                            if (d.valorations) {
                                if (d.valorations.ratings == 1) {
                                    output += '<div class="text-secondary"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>'+d.valorations.ratings+translations['opinion']+'</small></div>'
                                } else {
                                    output += '<div class="text-secondary"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>'+d.valorations.ratings+translations['opinions']+'</small></div>'
                                }
                            }
                            output += '<p class="destino_descripcion mt-1">'+d.translations[language].short_description+'<p>'
                            output += '</div></a></div></div>'
                            $('#'+a+'>.carrousel-'+a+'>.destinos_container').append(output)
                            i++
                        }
                    })
                }
                let href
                if (a == 'routes_hiker' || a == 'routes_btt' || a == 'routes_highway') {
                    href = location.origin+'/'+language+'/'+city.slug+'/'+translations['url_routes']
                } else {
                    href = location.origin+'/'+language+'/'+city.slug+'/'+translations['url_'+a]
                }
                $('#'+a+' .btn-outline-main').attr('href', href)
                if (i > 0) {
                    $('#'+a+' .count-'+a).html(i+translations['found_in']+city.name)
                } else {
                    $('#'+a).css('display', 'none')
                }
            })
            $.each(resoruces, function(a, b){
                if (!content) {
                    if ($('#'+a+'>.carrousel-'+a+'>.destinos_container').children().length > 0) {
                        content = true
                    }
                }
            })
            if (!content) {
                window.history.back()
            }
        },
        404: function(data) {
            // console.log(data)
            window.history.back()
        }
    }
})

$(document).ready(function(){
    $(".destinos_container").owlCarousel({
        loop:true,
        margin:10,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:2,
                nav:true
            },
            1000:{
                items:3,
                // nav:true,
                loop:false,
                dots:true
            }
        }
    });
  });