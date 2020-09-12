let mymap= L.map('mapid');

let osmUrl = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
let osmAttrib = 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
let osm = new L.TileLayer(osmUrl, {attribution: osmAttrib});

mymap.pm.addControls({
    position: 'topleft',
    drawPolyline: false,
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

var locationHistory = {"locations" : []};
var selectedFile;

function updateJObject(location, lat, lng) {
    var jsonObject = {
        "timestampMs" : location.timestampMs.toString(),
        "latitudeE7" : lat,
        "longitudeE7" : lng,
        "accuracy" : location.accuracy,
        "activity" : []
    };

    var i;
    if (location.activity !== undefined) {
        for (i = 0; i < location.activity.length; i++) {
            var activityObject = {
                "timestampMs" : location.activity[i].timestampMs.toString(),
                "activity" : []
            };
    
            activityObject.activity.push(location.activity[i].activity[0]);
            jsonObject.activity.push(activityObject);
    
            if (jsonObject.activity[i].activity[0].type == "UNKNOWN") {
                jsonObject.activity[i].activity[0].type = "STILL";
            }
        }
    }

    locationHistory.locations.push(jsonObject);
}

function loadData(event) {
    let patrasCenter = L.latLng(38.230462, 21.753150);

    selectedFile = event.target.files[0];
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

            curLat = curLat.toFixed(5);
            curLng = curLng.toFixed(5);

            let currentPoint = L.latLng(curLat, curLng);

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

$("#upload_btn").on('click', function(e) {
    prunSensitiveLocations();
});

function prunSensitiveLocations() {
    var prunedLocationHistory = {"locations" : []};
    var polygons = [];
    var coordinates = [];
    var polygonCornerLat;
    var polygonCornerLng;
    
    var i;
    var j;

    var shapes = L.PM.Utils.findLayers(mymap);

    for (i = 0; i < shapes.length; i++) {
        coordinates = [];
        for (j = 0; j < shapes[i]._latlngs[0].length; j++) {
            polygonCornerLat = shapes[i]._latlngs[0][j].lat;
            polygonCornerLng = shapes[i]._latlngs[0][j].lng;

            coordinates.push([polygonCornerLat, polygonCornerLng]);
        }
        polygons.push(L.polygon(coordinates));
    }

    for (i = 0; i < locationHistory.locations.length; i++) {
        var deletePoint = false;

        for (j = 0; j < polygons.length; j++) {
            if (polygons[j].contains(L.marker([locationHistory.locations[i].latitudeE7, locationHistory.locations[i].longitudeE7]).getLatLng())) {
                deletePoint = true;
                break;
            }
        }

        if (!deletePoint) {
            prunedLocationHistory.locations.push(locationHistory.locations[i]);
        }
    }

    $.ajax({
        type: 'POST',
        url: 'scripts/upload.php',
        data: {prunedLocationHistory: JSON.stringify(prunedLocationHistory), fileName: selectedFile["name"]},
        success: function() {
            alert('File uploaded successfully.');
        },
        error: function() {
            alert('An error occured.');
        }
    });
}
