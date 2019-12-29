$("#demo-gallery").unitegallery({
    tiles_type: "justified"
});

var latitude=$('#map_destino').attr('lat');
var longitude=$('#map_destino').attr('lon');

var mymap = L.map('map_destino').setView([latitude, longitude], 14);

L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(mymap);

L.marker([latitude, longitude]).addTo(mymap);
