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

let southWest = L.latLng(38.02, 21,3);
let northEast = L.latLng(38.32, 22.12);
let bounds = L.latLngBounds(southWest, northEast);

mymap.setMaxBounds(bounds);
mymap.setMinZoom(11);

mymap.setView([38.230462, 21.753150], 14);

// L.circle([38.230462, 21.753150], 10000).addTo(mymap);

function deleteDistantPoints() {
    let patrasCenter = L.latLng(38.230462, 21.753150);

    let shapes = mymap.pm.Draw.getActiveShape();

    console.log(L.PM.Utils.findLayers(mymap));
    console.log(L.PM.Utils.findLayers(mymap)[1]._latlngs[0][0].lat);
    console.log(L.PM.Utils.findLayers(mymap)[1]._latlngs[0][0].lng);

    let currentPoint = L.latLng(39.230462, 21.753150);



    if (currentPoint.distanceTo(patrasCenter) > 10000) {
        console.log(currentPoint.distanceTo(patrasCenter));
        // delete point
    }

    return false;
}


/*

var txt = '{"locations" : [ {"timestampMs" : "1583970659263", "latitudeE7" : 382318443, "longitudeE7" : 217366993, "accuracy" : 1924, "activity" : [ {"timestampMs" : "1583970659529", "activity" : [ { "type" : "STILL", "confidence" : 97}, {"type" : "UNKNOWN", "confidence" : 2}]} ] }] }'
var obj = JSON.parse(txt);
document.getElementById("demo").innerHTML = obj.locations[0].activity[0].timestampMs;

*/

