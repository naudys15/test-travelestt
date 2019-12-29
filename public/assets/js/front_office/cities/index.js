//imagen de ciudad
function imageCity(slug)
{
    var city = sessionStorage.getItem(slug)
    if (city) {
        city = JSON.parse(descrypt(sessionStorage.getItem(slug)))
        changeCity(city.name)
        if(city.image) {
            changeImage(city.image)
        }
        
        return city;
    } else {
        var result = getCity(slug)
        result.done(function(data){
            var resource = data.message[0]
            changeCity(resource.name)
            sessionStorage.setItem(slug, encrypt(JSON.stringify(resource)));
            if(resource.image) {
                changeImage(resource.image)
            }
            return city;
        })
        .fail(function() {
            window.history.back()
        })

    }
}

function getCity(slug)
{
    return $.ajax({
        url: "/api/cities/slug/"+ slug,
        method: 'GET'
    })
}

function changeImage(image)
{
    var route_image = routeImage('cities')
    if ($('#lugares_cercanos_hero').length > 0) {
        $('#lugares_cercanos_hero').css('background-image', 'url(' + route_image + image + ')')
    } else if ($('#hero_home').length > 0) {
        $('#hero_home').css('background-image', 'url(' + route_image + image + ')')
    }
}

function changeCity(name) {
    $('#city').append('')
    $('#city').append(upperPrimary(name))
}