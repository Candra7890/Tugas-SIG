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
        font-size: 10pt;
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
    
    .marker-form {
        padding: 10px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .marker-form input, .marker-form button {
        margin: 5px 0;
        padding: 5px;
        width: 100%;
    }
    
    .marker-form button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 8px;
        border-radius: 3px;
        cursor: pointer;
    }
    
    .marker-form button:hover {
        background-color: #45a049;
    }
    
    .marker-actions {
        margin-top: 5px;
        display: flex;
        justify-content: space-between;
    }
    
    .saved-notification {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        position: fixed;
        top: 20px;
        right: 20px;
        border-radius: 5px;
        display: none;
        z-index: 1000;
    }
    </style>   
</head>
<body>
    <header>
        <h1>CIS CIS CIS</h1>
        <a class="logout-button" href="/logout">Logout</a>
    </header>
    
    <div class="container">
        <div id="map"></div>
        <div id="saved-notification" class="saved-notification">Marker berhasil disimpan!</div>
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
var tempMarker = null;

// Function to add a marker with form
function addMarkerWithForm(latlng) {
    // Remove temporary marker if it exists
    if (tempMarker) {
        map.removeLayer(tempMarker);
    }
    
    // Create a temporary marker
    tempMarker = L.marker(latlng).addTo(map);
    
    // Create a popup with a form for naming the marker
    var popupContent = `
        <div class="marker-form">
            <h4>Tambah Titik Baru</h4>
            <input type="text" id="marker-name" placeholder="Nama Titik">
            <input type="text" id="marker-description" placeholder="Deskripsi (opsional)">
            <div class="marker-actions">
                <button onclick="saveMarker('${latlng.lat}', '${latlng.lng}')">Simpan</button>
                <button onclick="cancelMarker()">Batal</button>
            </div>
        </div>
    `;
    
    tempMarker.bindPopup(popupContent).openPopup();
}

// Function to save marker to database
function saveMarker(lat, lng) {
    var name = document.getElementById('marker-name').value;
    var description = document.getElementById('marker-description').value;
    
    if (!name) {
        alert('Silakan isi nama titik terlebih dahulu!');
        return;
    }
    
    // Create the permanent marker
    var marker = L.marker([parseFloat(lat), parseFloat(lng)], {draggable: true}).addTo(map);
    marker.bindPopup("<b>" + name + "</b><br>" + description + "<br>Koordinat: " + parseFloat(lat).toFixed(6) + ", " + parseFloat(lng).toFixed(6));
    markers.push(marker);
    
    // Set up drag event for the permanent marker
    marker.on('dragend', function(event) {
        var newLatLng = marker.getLatLng();
        marker.bindPopup("<b>" + name + "</b><br>" + description + "<br>Koordinat: " + newLatLng.lat.toFixed(6) + ", " + newLatLng.lng.toFixed(6)).openPopup();
        updateMarkerCoordinates(marker, newLatLng);
        
        // Update coordinates in database
        updateMarkerInDatabase(marker.markerId, newLatLng.lat, newLatLng.lng);
    });
    
    // Send data to server
    $.ajax({
        url: 'koordinat/save',
        type: 'POST',
        data: {
            name: name,
            description: description,
            latitude: parseFloat(lat),
            longitude: parseFloat(lng),
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Marker saved:', response);
            
            // Store the marker ID returned from server
            if (response && response.id) {
                marker.markerId = response.id;
            }
            
            // Show saved notification
            showSavedNotification();
            
            // Remove the temporary marker
            map.removeLayer(tempMarker);
            tempMarker = null;
        },
        error: function(error) {
            console.error('Error saving marker:', error);
            alert('Gagal menyimpan titik. Silakan coba lagi.');
        }
    });
}

// Function to cancel marker creation
function cancelMarker() {
    if (tempMarker) {
        map.removeLayer(tempMarker);
        tempMarker = null;
    }
}

// Function to update marker in database
function updateMarkerInDatabase(markerId, lat, lng) {
    if (!markerId) return;
    
    $.ajax({
        url: 'koordinat/update',
        type: 'POST',
        data: {
            id: markerId,
            latitude: lat,
            longitude: lng,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Marker updated:', response);
            showSavedNotification();
        },
        error: function(error) {
            console.error('Error updating marker:', error);
        }
    });
}

// Function to delete marker from database
function deleteMarker(markerId, marker) {
    if (!markerId) return;
    
    $.ajax({
        url: 'koordinat/delete',
        type: 'POST',
        data: {
            id: markerId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Marker deleted:', response);
            map.removeLayer(marker);
            
            // Remove from markers array
            markers = markers.filter(m => m !== marker);
        },
        error: function(error) {
            console.error('Error deleting marker:', error);
        }
    });
}

// Function to show saved notification
function showSavedNotification() {
    var notification = document.getElementById('saved-notification');
    notification.style.display = 'block';
    
    setTimeout(function() {
        notification.style.display = 'none';
    }, 3000);
}

// Add marker on map click
map.on('click', function(e) {
    addMarkerWithForm(e.latlng);
});

// Get Koordinat dari Database
$(document).ready(function() {
    $.getJSON('koordinat/json', function(data) {
        console.log(data);
        $.each(data, function (index) {
            var latitude = parseFloat(data[index].latitude);
            var longitude = parseFloat(data[index].longitude);
            var name = data[index].name || "Titik " + (index + 1);
            var description = data[index].description || "";
            var id = data[index].id;

            if (!isNaN(latitude) && !isNaN(longitude)) {
                var marker = L.marker([latitude, longitude], {draggable: true}).addTo(map);
                marker.markerId = id;
                marker.bindPopup("<b>" + name + "</b><br>" + description + "<br>Koordinat: " + latitude.toFixed(6) + ", " + longitude.toFixed(6));
                
                // Setup drag event
                marker.on('dragend', function(event) {
                    var newLatLng = marker.getLatLng();
                    marker.bindPopup("<b>" + name + "</b><br>" + description + "<br>Koordinat: " + newLatLng.lat.toFixed(6) + ", " + newLatLng.lng.toFixed(6)).openPopup();
                    updateMarkerCoordinates(marker, newLatLng);
                    
                    // Update coordinates in database
                    updateMarkerInDatabase(marker.markerId, newLatLng.lat, newLatLng.lng);
                });
                
                markers.push(marker);
                
                if (index === 0) {
                    map.setView([latitude, longitude], 16);
                }
            } else {
                console.error("Koordinat tidak valid " + index + ": ", data[index]);
            }
        });
    });
});

// Get koordinat dari GeoJson
// $.getJSON('assets/map.geojson', function(data) {
//     console.log(data);
//     var geoJsonLayer = L.geoJSON(data, {
//         onEachFeature: function(feature, layer) {
//             if (feature.properties && feature.properties.nama) {
//                 layer.bindPopup(feature.properties.nama);
//             }

//             var center = layer.getBounds().getCenter();
            
//             var iconLabel = L.divIcon({
//                 className: 'label-bidang',
//                 html: '<b>'+feature.properties.nama+'</b>',
//                 iconSize: [100, 20]
//             });
//             L.marker(center, {icon: iconLabel}).addTo(map);
//         }
//     }).addTo(map);

//     map.fitBounds(geoJsonLayer.getBounds());
// });

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
</body>
</html>