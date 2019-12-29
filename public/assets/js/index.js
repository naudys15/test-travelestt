$(document).ready(function() {
    $('#logout').on('click', function() {
        $.ajax({
            async: false,
            url: location.origin+"/api/logout",
            type: 'POST',
            dataType: 'json',
            headers: {
                'Accept-Language': $('html').attr('lang'),
                'Authorization': 'Bearer '+localStorage.getItem("travelesttAccess")
            },  
            data: {
                token: localStorage.getItem("travelesttAccess")
            },
            statusCode: {
                200: function(data) {
                    localStorage.clear()
                    let url = location.origin
                    window.location.replace(url)
                }
            }
        });
    });
});