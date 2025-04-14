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
    
    .main-container {
        flex: 1;
        display: flex;
        flex-direction: row;
        height: calc(100vh - 70px);
    }
    
    .sidebar {
        width: 300px;
        background-color: #f8f9fa;
        overflow-y: auto;
        border-right: 1px solid #ddd;
        padding: 10px;
    }
    
    .sidebar h3 {
        margin-top: 0;
        border-bottom: 2px solid #4CAF50;
        padding-bottom: 10px;
    }
    
    .marker-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .marker-item {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
    }
    
    .marker-item:hover {
        background-color: #f1f1f1;
    }
    
    .marker-item.active {
        background-color: #e8f5e9;
        border-left: 4px solid #4CAF50;
    }
    
    .marker-name {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .marker-description {
        color: #666;
        font-size: 0.9em;
        margin-bottom: 5px;
    }
    
    .marker-coordinates {
        color: #888;
        font-size: 0.8em;
        margin-bottom: 10px;
    }
    
    .marker-actions {
        display: flex;
        justify-content: space-between;
    }
    
    .btn {
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 0.8em;
    }
    
    .btn-edit {
        background-color: #2196F3;
        color: white;
    }
    
    .btn-edit:hover {
        background-color: #0b7dda;
    }
    
    .btn-delete {
        background-color: #f44336;
        color: white;
    }
    
    .btn-delete:hover {
        background-color: #d32f2f;
    }
    
    .map-container {
        flex: 1;
    }
    
    #map {
        height: 100%;
        width: 100%;
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
    
    .marker-form input, .marker-form textarea, .marker-form button {
        margin: 5px 0;
        padding: 8px;
        width: 100%;
        box-sizing: border-box;
    }
    
    .marker-form button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 3px;
        cursor: pointer;
    }
    
    .marker-form button:hover {
        background-color: #45a049;
    }
    
    .edit-form {
        margin-top: 20px;
        padding: 15px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        display: none;
    }
    
    .edit-form h4 {
        margin-top: 0;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
    }
    
    .form-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }
    
    .notification {
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
    
    .notification.error {
        background-color: #f44336;
    }
    
    textarea {
        resize: vertical;
        min-height: 60px;
    }

    .search-box {
        margin-bottom: 15px;
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }
    </style>   
</head>
<body>
    <header>
        <h1>CIS CIS CIS</h1>
        <a class="logout-button" href="/logout">Logout</a>
    </header>
    
    <div class="main-container">
        <div class="sidebar">
            <h3>Daftar Marker</h3>
            <input type="text" id="search-marker" class="search-box" placeholder="Cari marker...">
            <ul class="marker-list" id="marker-list">
            </ul>
            
            <div class="edit-form" id="edit-form">
                <h4>Edit Marker</h4>
                <input type="hidden" id="edit-marker-id">
                <input type="text" id="edit-marker-name" placeholder="Nama Marker">
                <textarea id="edit-marker-description" placeholder="Deskripsi (opsional)"></textarea>
                <div class="form-buttons">
                    <button class="btn btn-edit" onclick="updateMarkerDetails()">Simpan</button>
                    <button class="btn btn-delete" onclick="hideEditForm()">Batal</button>
                </div>
            </div>
        </div>
        
        <div class="map-container">
            <div id="map"></div>
        </div>
    </div>
    
    <div class="notification" id="notification">Marker berhasil disimpan!</div>

<script>
// Map initialization
var map = L.map('map').setView([51.505, -0.09], 13);

// Google Hybrid
L.tileLayer('http://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
}).addTo(map);

var markers = [];
var tempMarker = null;
var activeMarkerId = null;

function addMarkerWithForm(latlng) {
    if (tempMarker) {
        map.removeLayer(tempMarker);
    }
    
    tempMarker = L.marker(latlng).addTo(map);
    
    var popupContent = `
        <div class="marker-form">
            <h4>Tambah Titik Baru</h4>
            <input type="text" id="marker-name" placeholder="Nama Titik">
            <textarea id="marker-description" placeholder="Deskripsi (opsional)"></textarea>
            <div class="marker-actions">
                <button onclick="saveMarker('${latlng.lat}', '${latlng.lng}')">Simpan</button>
                <button onclick="cancelMarker()">Batal</button>
            </div>
        </div>
    `;
    
    tempMarker.bindPopup(popupContent).openPopup();
}

function saveMarker(lat, lng) {
    var name = document.getElementById('marker-name').value;
    var description = document.getElementById('marker-description').value;
    
    if (!name) {
        alert('Silakan isi nama titik terlebih dahulu!');
        return;
    }
    
    var marker = L.marker([parseFloat(lat), parseFloat(lng)], {draggable: true}).addTo(map);
    
    var popupContent = `<b>${name}</b><br>${description || 'Tidak ada deskripsi'}<br>Koordinat: ${parseFloat(lat).toFixed(6)}, ${parseFloat(lng).toFixed(6)}`;
    marker.bindPopup(popupContent);
    
    marker.on('dragend', function(event) {
        var newLatLng = marker.getLatLng();
        
        marker.bindPopup(`<b>${name}</b><br>${description || 'Tidak ada deskripsi'}<br>Koordinat: ${newLatLng.lat.toFixed(6)}, ${newLatLng.lng.toFixed(6)}`);
        
        updateMarkerInDatabase(marker.pointmapId, newLatLng.lat, newLatLng.lng);
        
        updateMarkerSidebarItem(marker.pointmapId, name, description, newLatLng.lat, newLatLng.lng);
    });
    
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
            
            if (response && response.pointmap_id) {
                marker.pointmapId = response.pointmap_id;
                marker.markerName = name;
                marker.markerDescription = description;
                
                markers.push(marker);
                
                addMarkerToSidebar(response.pointmap_id, name, description, parseFloat(lat), parseFloat(lng));
            }
            
            showNotification('Marker berhasil disimpan!');
            
            map.removeLayer(tempMarker);
            tempMarker = null;
        },
        error: function(error) {
            console.error('Error saving marker:', error);
            showNotification('Gagal menyimpan titik. Silakan coba lagi.', true);
        }
    });
}

function addMarkerToSidebar(pointmapId, name, description, lat, lng) {
    var markerList = document.getElementById('marker-list');
    
    var listItem = document.createElement('li');
    listItem.className = 'marker-item';
    listItem.id = 'marker-item-' + pointmapId;
    listItem.setAttribute('data-id', pointmapId);
    
    listItem.innerHTML = `
        <div class="marker-name">${name}</div>
        <div class="marker-description">${description || 'Tidak ada deskripsi'}</div>
        <div class="marker-coordinates">Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}</div>
        <div class="marker-actions">
            <button class="btn btn-edit" onclick="showEditForm(${pointmapId})">Edit</button>
            <button class="btn btn-delete" onclick="confirmDeleteMarker(${pointmapId})">Hapus</button>
        </div>
    `;
    
    listItem.addEventListener('click', function(e) {
        if (e.target.tagName !== 'BUTTON') {
            zoomToMarker(pointmapId);
        }
    });
    
    markerList.appendChild(listItem);
}

function updateMarkerSidebarItem(pointmapId, name, description, lat, lng) {
    var markerItem = document.getElementById('marker-item-' + pointmapId);
    if (markerItem) {
        markerItem.innerHTML = `
            <div class="marker-name">${name}</div>
            <div class="marker-description">${description || 'Tidak ada deskripsi'}</div>
            <div class="marker-coordinates">Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}</div>
            <div class="marker-actions">
                <button class="btn btn-edit" onclick="showEditForm(${pointmapId})">Edit</button>
                <button class="btn btn-delete" onclick="confirmDeleteMarker(${pointmapId})">Hapus</button>
            </div>
        `;
    }
}

function zoomToMarker(pointmapId) {
    var marker = markers.find(m => m.pointmapId === pointmapId);
    if (marker) {
        map.setView(marker.getLatLng(), 18);
        marker.openPopup();
        
        $('.marker-item').removeClass('active');
        $('#marker-item-' + pointmapId).addClass('active');
        activeMarkerId = pointmapId;
    }
}

function showEditForm(pointmapId) {
    var marker = markers.find(m => m.pointmapId === pointmapId);
    if (!marker) return;
    
    document.getElementById('edit-marker-id').value = pointmapId;
    document.getElementById('edit-marker-name').value = marker.markerName || '';
    document.getElementById('edit-marker-description').value = marker.markerDescription || '';
    
    document.getElementById('edit-form').style.display = 'block';
    
    document.getElementById('edit-form').scrollIntoView({ behavior: 'smooth' });
}

function hideEditForm() {
    document.getElementById('edit-form').style.display = 'none';
}

function updateMarkerDetails() {
    var pointmapId = document.getElementById('edit-marker-id').value;
    var name = document.getElementById('edit-marker-name').value;
    var description = document.getElementById('edit-marker-description').value;
    
    if (!name) {
        alert('Silakan isi nama titik terlebih dahulu!');
        return;
    }
    
    var marker = markers.find(m => m.pointmapId === parseInt(pointmapId));
    if (!marker) return;
    
    marker.markerName = name;
    marker.markerDescription = description;
    
    var latlng = marker.getLatLng();
    marker.bindPopup(`<b>${name}</b><br>${description || 'Tidak ada deskripsi'}<br>Koordinat: ${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`);
    
    $.ajax({
        url: 'koordinat/update-details',
        type: 'POST',
        data: {
            pointmap_id: pointmapId,
            name: name,
            description: description,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Marker details updated:', response);
            
            updateMarkerSidebarItem(parseInt(pointmapId), name, description, latlng.lat, latlng.lng);
            
            hideEditForm();
            
            showNotification('Marker berhasil diperbarui!');
        },
        error: function(error) {
            console.error('Error updating marker details:', error);
            showNotification('Gagal memperbarui titik. Silakan coba lagi.', true);
        }
    });
}

function confirmDeleteMarker(pointmapId) {
    if (confirm('Apakah Anda yakin ingin menghapus titik ini?')) {
        var marker = markers.find(m => m.pointmapId === pointmapId);
        if (marker) {
            deleteMarker(pointmapId, marker);
        }
    }
}

function cancelMarker() {
    if (tempMarker) {
        map.removeLayer(tempMarker);
        tempMarker = null;
    }
}

function updateMarkerInDatabase(pointmapId, lat, lng) {
    if (!pointmapId) return;
    
    $.ajax({
        url: 'koordinat/update',
        type: 'POST',
        data: {
            pointmap_id: pointmapId,
            latitude: lat,
            longitude: lng,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Marker coordinates updated:', response);
            showNotification('Koordinat marker berhasil diperbarui!');
        },
        error: function(error) {
            console.error('Error updating marker coordinates:', error);
            showNotification('Gagal memperbarui koordinat. Silakan coba lagi.', true);
        }
    });
}

function deleteMarker(pointmapId, marker) {
    if (!pointmapId) return;
    
    $.ajax({
        url: 'koordinat/delete',
        type: 'POST',
        data: {
            pointmap_id: pointmapId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Marker deleted:', response);
            
            map.removeLayer(marker);
            
            markers = markers.filter(m => m.pointmapId !== pointmapId);
            
            var markerItem = document.getElementById('marker-item-' + pointmapId);
            if (markerItem) {
                markerItem.remove();
            }
            
            if (document.getElementById('edit-marker-id').value == pointmapId) {
                hideEditForm();
            }
            
            showNotification('Marker berhasil dihapus!');
        },
        error: function(error) {
            console.error('Error deleting marker:', error);
            showNotification('Gagal menghapus titik. Silakan coba lagi.', true);
        }
    });
}

function showNotification(message, isError = false) {
    var notification = document.getElementById('notification');
    notification.textContent = message;
    
    if (isError) {
        notification.classList.add('error');
    } else {
        notification.classList.remove('error');
    }
    
    notification.style.display = 'block';
    
    setTimeout(function() {
        notification.style.display = 'none';
    }, 3000);
}

map.on('click', function(e) {
    addMarkerWithForm(e.latlng);
});

$(document).ready(function() {
    $('#search-marker').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.marker-item').filter(function() {
            $(this).toggle(
                $(this).text().toLowerCase().indexOf(value) > -1
            );
        });
    });
});

$(document).ready(function() {
    $.getJSON('koordinat/json', function(data) {
        console.log(data);
        
        $('#marker-list').empty();
        
        $.each(data, function (index) {
            var latitude = parseFloat(data[index].latitude);
            var longitude = parseFloat(data[index].longitude);
            var name = data[index].name || "Titik " + (index + 1);
            var description = data[index].description || "";
            var pointmapId = data[index].pointmap_id;

            if (!isNaN(latitude) && !isNaN(longitude)) {
                var marker = L.marker([latitude, longitude], {draggable: true}).addTo(map);
                marker.pointmapId = pointmapId;
                marker.markerName = name;
                marker.markerDescription = description;
                
                marker.bindPopup(`<b>${name}</b><br>${description || 'Tidak ada deskripsi'}<br>Koordinat: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}`);
                
                marker.on('dragend', function(event) {
                    var newLatLng = marker.getLatLng();
                    marker.bindPopup(`<b>${name}</b><br>${description || 'Tidak ada deskripsi'}<br>Koordinat: ${newLatLng.lat.toFixed(6)}, ${newLatLng.lng.toFixed(6)}`);
                    
                    updateMarkerInDatabase(marker.pointmapId, newLatLng.lat, newLatLng.lng);
                    
                    updateMarkerSidebarItem(marker.pointmapId, name, description, newLatLng.lat, newLatLng.lng);
                });
                
                markers.push(marker);
                
                addMarkerToSidebar(pointmapId, name, description, latitude, longitude);
                
                if (index === 0) {
                    map.setView([latitude, longitude], 16);
                }
            } else {
                console.error("Koordinat tidak valid " + index + ": ", data[index]);
            }
        });
    });
});
</script>    
</body>
</html>