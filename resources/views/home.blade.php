<!DOCTYPE html>
<html>
<head>
    <title>CIS CIS CIS</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

     <script
     src="https://unpkg.com/esri-leaflet@3.0.14/dist/esri-leaflet.js"
     integrity="sha512-3fIGJwUOdCnUZPv8vIk8CMi3baMSaQp/zozG6kRGM4f5NvSXKBRNf4ufcdP94Nii510v1tfsXR1HScEg7WU3Pg=="
     crossorigin=""></script>

     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
    #map { height: 600px; }

    .Label-bidang {
        font: size 10pt;
        color: #400ee6;
        text-align: center;
    }

    body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }
        #map {
            flex: 1;
            border: 2px solid #4CAF50;
            border-radius: 8px;
            margin-top: 20px;
        }
        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-button:hover {
            background-color: #d32f2f;
        }
    </style>   
</head>
<body>
<div id="map"></div>
</body>
    <header>
        <h1>CIS CIS CIS</h1>
    </header>
    <div class="cta-cta">
		<a class="button button-primary button-wide-mobile" href="/logout">Logout</a>
	</div>

<script>
// Map
var map = L.map('map').setView([51.505, -0.09], 13);

// Google Hybrid
L.tileLayer('http://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
}).addTo(map);

// Array to store markers
var markers = [];

// Function to add a marker
function addMarker(latlng) {
    var marker = L.marker(latlng, {draggable: true}).addTo(map);
    marker.on('dragend', function(event) {
        var newLatLng = marker.getLatLng();
        marker.bindPopup("Coordinates: " + newLatLng.lat.toFixed(6) + ", " + newLatLng.lng.toFixed(6)).openPopup();
        updateMarkerCoordinates(marker, newLatLng);
    });
    marker.bindPopup("Coordinates: " + latlng.lat.toFixed(6) + ", " + latlng.lng.toFixed(6)).openPopup();
    markers.push(marker);
}

// Add marker on map click
map.on('click', function(e) {
    addMarker(e.latlng);
});

// Get Koordinat dari Database
$(document).ready(function() {
    $.getJSON('koordinat/json', function(data) {
        console.log(data);
        $.each(data, function (index) {
            var latitude = parseFloat(data[index].latitude);
            var longitude = parseFloat(data[index].longitude);

            if (!isNaN(latitude) && !isNaN(longitude)) {
                addMarker([latitude, longitude]);
                map.setView([latitude, longitude], 16);
            } else {
                console.error("Koordinat anda tidak valid " + index + ": ", data[index]);
            }
        });
    });
});

// Get koordinat dari GeoJson
$.getJSON('assets/map.geojson', function(data) {
    console.log(data);
    var geoJsonLayer = L.geoJSON(data, {
        onEachFeature: function(feature, layer) {
            if (feature.properties && feature.properties.nama) {
                layer.bindPopup(feature.properties.nama);
            }

            var center = layer.getBounds().getCenter();
            addMarker(center);

            var iconLabel = L.divIcon({
                className: 'label-bidang',
                html: '<b>'+feature.properties.nama+'</b>',
                iconSize: [100, 20]
            });
            L.marker(center, {icon: iconLabel}).addTo(map);
        }
    }).addTo(map);

    map.fitBounds(geoJsonLayer.getBounds());
});

// Function to update marker coordinates in the markers array
function updateMarkerCoordinates(marker, newLatLng) {
    for (var i = 0; i < markers.length; i++) {
        if (markers[i] === marker) {
            markers[i].latlng = newLatLng;
            break;
        }
    }
}

</script>    

</html>
