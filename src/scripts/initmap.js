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

mymap.setMaxBounds(bounds);
mymap.setMinZoom(11);

mymap.setView([38.230462, 21.753150], 14);

var locationHistory = {"locations" : []};

function updateJObject(location, lat, lng) {
    location.latitudeE7 = lat;
    location.longitudeE7 = lng;

    try {
        var activity = location.activity[0].activity[0];

        if (location.activity.activity[0].type == "UNKNOWN") {
            location.activity.activity[0].type = "STILL";
        }

        location.activity[0].activity.splice(1, 1);
    }
    catch {}

    locationHistory.locations.push(location);
}

function loadData(event) {
    let patrasCenter = L.latLng(38.230462, 21.753150);

    var selectedFile = event.target.files[0];
    var reader = new FileReader();

    let locations = {
        min: 0,
        max: 0,
        data: []
    };

    reader.onload = function(event) {
        var obj = JSON.parse(event.target.result);
        
        for (var i in obj.locations) {
            let curLat = obj.locations[i].latitudeE7 / 10000000;
            let curLng = obj.locations[i].longitudeE7 / 10000000;

            let currentPoint = L.latLng(Number.parseFloat(curLat).toFixed(5), Number.parseFloat(curLng).toFixed(5));

            if (currentPoint.distanceTo(patrasCenter) <= 10000) {
                updateJObject(obj.locations[i], curLat, curLng);
                var found = false;
                
                for (var j = 0; j < locations.data.length; j++) {
                    if (locations.data[j].lat == curLat && locations.data[j].lng == curLng) {
                        locations.data[j].count++;

                        if (locations.max < locations.data[j].count) {
                            locations.max = locations.data[j].count;
                        }

                        found = true;
                        break;
                    }
                }

                if (!found) {
                    locations.data.push({lat: curLat, lng: curLng, count: 1});
                }
            }
        }

        let cfg = {
            "radius": 30,
            "maxOpacity": 1,
            "scaleRadius": false,
            "useLocalExtrema": false,
            latField: 'lat',
            lngField: 'lng',
            valueField: 'count'
        };
        
        let heatmapLayer = new HeatmapOverlay(cfg);
        mymap.addLayer(heatmapLayer);
        heatmapLayer.setData(locations);
    };

    reader.readAsText(selectedFile);
}

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

