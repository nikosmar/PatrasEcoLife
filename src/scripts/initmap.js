let mymap= L.map('mapid');

let osmUrl = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
let osmAttrib = 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
let osm = new L.TileLayer(osmUrl, {attribution: osmAttrib});

mymap.pm.addControls({
    position: 'topleft',
    drawCircle: false,
    drawCircleMarker: false,
});

mymap.addLayer(osm);

mymap.setView([38.2462420, 21.7350847], 16);

//L.circle([38.2462420, 21.7350847], 10000).addTo(mymap);
