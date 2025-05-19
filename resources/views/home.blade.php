<!DOCTYPE html>
<html>
<head>
    <title>CIS CIS CIS - V 2.0</title>

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

     <!-- Leaflet.draw plugin for drawing tools -->
     <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css"/>
     <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

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
        width: 350px;
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
    
    .tab-container {
        margin-bottom: 20px;
    }
    
    .tab-buttons {
        display: flex;
        border-bottom: 1px solid #ddd;
    }
    
    .tab-button {
        flex: 1;
        padding: 10px;
        background-color: #f8f9fa;
        border: none;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        font-size: 12px;
    }
    
    .tab-button.active {
        background-color: white;
        border-bottom: 2px solid #4CAF50;
        font-weight: bold;
    }
    
    .tab-content {
        display: none;
        padding-top: 10px;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .marker-list, .shape-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .marker-item, .shape-item {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
        margin-bottom: 5px;
    }
    
    .marker-item:hover, .shape-item:hover {
        background-color: #f1f1f1;
    }
    
    .marker-item.active, .shape-item.active {
        background-color: #e8f5e9;
        border-left: 4px solid #4CAF50;
    }
    
    .marker-name, .shape-name {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .marker-description, .shape-description {
        color: #666;
        font-size: 0.9em;
        margin-bottom: 5px;
    }
    
    .marker-coordinates, .shape-info {
        color: #888;
        font-size: 0.8em;
        margin-bottom: 10px;
    }
    
    .marker-actions, .shape-actions {
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
    
    .btn-zoom {
        background-color: #4CAF50;
        color: white;
    }
    
    .btn-zoom:hover {
        background-color: #45a049;
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
    
    .marker-form, .shape-form {
        padding: 10px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .marker-form input, .marker-form textarea, .marker-form button,
    .shape-form input, .shape-form textarea, .shape-form button {
        margin: 5px 0;
        padding: 8px;
        width: 100%;
        box-sizing: border-box;
    }
    
    .marker-form button, .shape-form button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 3px;
        cursor: pointer;
    }
    
    .marker-form button:hover, .shape-form button:hover {
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

    .shape-type {
        font-size: 0.7em;
        background-color: #e0e0e0;
        padding: 2px 6px;
        border-radius: 3px;
        margin-left: 5px;
    }

    .shape-type.polyline { background-color: #e3f2fd; }
    .shape-type.polygon { background-color: #f3e5f5; }
    .shape-type.circle { background-color: #fff3e0; }
    .shape-type.rectangle { background-color: #e8f5e9; }

    /* Drawing controls customization */
    .leaflet-draw-toolbar a {
        background-color: #4CAF50;
    }
    
    .leaflet-draw-toolbar a:hover {
        background-color: #45a049;
    }
    
    .drawing-mode-indicator {
        position: absolute;
        top: 100px;
        left: 20px;
        background-color: rgba(76, 175, 80, 0.9);
        color: white;
        padding: 10px;
        border-radius: 5px;
        display: none;
        z-index: 1000;
    }
    </style>   
</head>
<body>
    <header>
        <h1>CIS CIS CIS - V 2.0</h1>
        <a class="logout-button" href="/logout">Logout</a>
    </header>
    
    <div class="main-container">
        <div class="sidebar">
            <div class="tab-container">
                <div class="tab-buttons">
                    <button class="tab-button active" onclick="switchTab('markers')">Markers</button>
                    <button class="tab-button" onclick="switchTab('shapes')">Shapes</button>
                </div>
                
                <div class="tab-content active" id="markers-tab">
                    <h3>Daftar Marker</h3>
                    <input type="text" id="search-marker" class="search-box" placeholder="Cari marker...">
                    <ul class="marker-list" id="marker-list">
                    </ul>
                    
                    <div class="edit-form" id="edit-marker-form">
                        <h4>Edit Marker</h4>
                        <input type="hidden" id="edit-marker-id">
                        <input type="text" id="edit-marker-name" placeholder="Nama Marker">
                        <textarea id="edit-marker-description" placeholder="Deskripsi (opsional)"></textarea>
                        <div class="form-buttons">
                            <button class="btn btn-edit" onclick="updateMarkerDetails()">Simpan</button>
                            <button class="btn btn-delete" onclick="hideEditForm('marker')">Batal</button>
                        </div>
                    </div>
                </div>
                
                <div class="tab-content" id="shapes-tab">
                    <h3>Daftar Shapes</h3>
                    <input type="text" id="search-shape" class="search-box" placeholder="Cari shape...">
                    <ul class="shape-list" id="shape-list">
                    </ul>
                    
                    <div class="edit-form" id="edit-shape-form">
                        <h4>Edit Shape</h4>
                        <input type="hidden" id="edit-shape-id">
                        <input type="hidden" id="edit-shape-type">
                        <input type="text" id="edit-shape-name" placeholder="Nama Shape">
                        <textarea id="edit-shape-description" placeholder="Deskripsi (opsional)"></textarea>
                        <input type="color" id="edit-shape-color" value="#3388ff">
                        <div class="form-buttons">
                            <button class="btn btn-edit" onclick="updateShapeDetails()">Simpan</button>
                            <button class="btn btn-delete" onclick="hideEditForm('shape')">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="map-container">
            <div id="map"></div>
            <div class="drawing-mode-indicator" id="drawing-indicator">
                Klik pada peta untuk menambah marker
            </div>
        </div>
    </div>
    
    <div class="notification" id="notification">Operasi berhasil!</div>

<script>
// Map initialization
var map = L.map('map').setView([51.505, -0.09], 13);

// Google Hybrid
L.tileLayer('http://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
}).addTo(map);

var drawControl = new L.Control.Draw({
    position: 'topleft',
    draw: {
        marker: true,
        polyline: true,
        polygon: true,
        circle: true,
        rectangle: true,
        circlemarker: false
    },
    edit: {
        featureGroup: new L.FeatureGroup()
    }
});
map.addControl(drawControl);

var drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

drawControl.options.edit.featureGroup = drawnItems;

var markers = [];
var shapes = [];
var tempMarker = null;
var activeMarkerId = null;
var activeShapeId = null;
var isDrawingMode = false;
var currentDrawType = null;

function switchTab(tabName) {
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    document.querySelector(`[onclick="switchTab('${tabName}')"]`).classList.add('active');
    document.getElementById(tabName + '-tab').classList.add('active');
}

map.on('draw:drawstart', function (e) {
    isDrawingMode = true;
    currentDrawType = e.layerType;
    
    var indicator = document.getElementById('drawing-indicator');
    indicator.textContent = getDrawingMessage(e.layerType);
    indicator.style.display = 'block';
});

map.on('draw:drawstop', function (e) {
    isDrawingMode = false;
    currentDrawType = null;
    document.getElementById('drawing-indicator').style.display = 'none';
});

map.on('draw:created', function (e) {
    var type = e.layerType;
    var layer = e.layer;
    
    if (type === 'marker') {
        addMarkerWithForm(layer.getLatLng());
    } else {
        addShapeWithForm(layer, type);
    }
});

function getDrawingMessage(type) {
    switch(type) {
        case 'marker': return 'Klik pada peta untuk menambah marker';
        case 'polyline': return 'Klik untuk membuat garis, klik ganda untuk selesai';
        case 'polygon': return 'Klik untuk membuat polygon, klik ganda untuk selesai';
        case 'circle': return 'Klik dan drag untuk membuat lingkaran';
        case 'rectangle': return 'Klik dan drag untuk membuat persegi panjang';
        default: return 'Mode menggambar aktif';
    }
}

function addMarkerWithForm(latlng) {
    if (tempMarker) {
        map.removeLayer(tempMarker);
    }
    
    tempMarker = L.marker(latlng).addTo(map);
    
    var popupContent = `
        <div class="marker-form">
            <h4>Tambah Marker Baru</h4>
            <input type="text" id="marker-name" placeholder="Nama Marker">
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
        alert('Silakan isi nama marker terlebih dahulu!');
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
            showNotification('Gagal menyimpan marker. Silakan coba lagi.', true);
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
            <button class="btn btn-zoom" onclick="zoomToMarker(${pointmapId})">Zoom</button>
            <button class="btn btn-edit" onclick="showEditForm('marker', ${pointmapId})">Edit</button>
            <button class="btn btn-delete" onclick="confirmDeleteMarker(${pointmapId})">Hapus</button>
        </div>
    `;
    
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
                <button class="btn btn-zoom" onclick="zoomToMarker(${pointmapId})">Zoom</button>
                <button class="btn btn-edit" onclick="showEditForm('marker', ${pointmapId})">Edit</button>
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

function showEditForm(type, id) {
    if (type === 'marker') {
        var marker = markers.find(m => m.pointmapId === id);
        if (!marker) return;
        
        document.getElementById('edit-marker-id').value = id;
        document.getElementById('edit-marker-name').value = marker.markerName || '';
        document.getElementById('edit-marker-description').value = marker.markerDescription || '';
        
        document.getElementById('edit-marker-form').style.display = 'block';
        document.getElementById('edit-marker-form').scrollIntoView({ behavior: 'smooth' });
    } else if (type === 'shape') {
        var shape = shapes.find(s => s.shapeId === id);
        if (!shape) return;
        
        document.getElementById('edit-shape-id').value = id;
        document.getElementById('edit-shape-type').value = shape.shapeType;
        document.getElementById('edit-shape-name').value = shape.shapeName || '';
        document.getElementById('edit-shape-description').value = shape.shapeDescription || '';
        document.getElementById('edit-shape-color').value = shape.shapeColor || '#3388ff';
        
        document.getElementById('edit-shape-form').style.display = 'block';
        document.getElementById('edit-shape-form').scrollIntoView({ behavior: 'smooth' });
    }
}

function hideEditForm(type) {
    if (type === 'marker') {
        document.getElementById('edit-marker-form').style.display = 'none';
    } else if (type === 'shape') {
        document.getElementById('edit-shape-form').style.display = 'none';
    }
}

function updateMarkerDetails() {
    var pointmapId = document.getElementById('edit-marker-id').value;
    var name = document.getElementById('edit-marker-name').value;
    var description = document.getElementById('edit-marker-description').value;
    
    if (!name) {
        alert('Silakan isi nama marker terlebih dahulu!');
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
            
            hideEditForm('marker');
            
            showNotification('Marker berhasil diperbarui!');
        },
        error: function(error) {
            console.error('Error updating marker details:', error);
            showNotification('Gagal memperbarui marker. Silakan coba lagi.', true);
        }
    });
}

function confirmDeleteMarker(pointmapId) {
    if (confirm('Apakah Anda yakin ingin menghapus marker ini?')) {
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
                hideEditForm('marker');
            }
            
            showNotification('Marker berhasil dihapus!');
        },
        error: function(error) {
            console.error('Error deleting marker:', error);
            showNotification('Gagal menghapus marker. Silakan coba lagi.', true);
        }
    });
}

function addShapeWithForm(layer, type) {
    var popupContent = `
        <div class="shape-form">
            <h4>Tambah ${type.charAt(0).toUpperCase() + type.slice(1)} Baru</h4>
            <input type="text" id="shape-name" placeholder="Nama ${type}">
            <textarea id="shape-description" placeholder="Deskripsi (opsional)"></textarea>
            <input type="color" id="shape-color" value="#3388ff">
            <div class="shape-actions">
                <button onclick="saveShape('${type}')">Simpan</button>
                <button onclick="cancelShape()">Batal</button>
            </div>
        </div>
    `;
    
    layer.bindPopup(popupContent).openPopup();
    layer.tempShape = true;
    drawnItems.addLayer(layer);
}

function saveShape(type) {
    var name = document.getElementById('shape-name').value;
    var description = document.getElementById('shape-description').value;
    var color = document.getElementById('shape-color').value;
    
    if (!name) {
        alert('Silakan isi nama shape terlebih dahulu!');
        return;
    }
    
    var tempLayer = null;
    drawnItems.eachLayer(function(layer) {
        if (layer.tempShape) {
            tempLayer = layer;
        }
    });
    
    if (!tempLayer) return;
    
    if (tempLayer.setStyle) {
        tempLayer.setStyle({ color: color });
    }
    
    var shapeData = prepareShapeData(tempLayer, type, name, description, color);
    
    $.ajax({
        url: 'shape/save',
        type: 'POST',
        data: shapeData,
        success: function(response) {
            console.log('Shape saved:', response);
            
            if (response && response.shape_id) {
                tempLayer.shapeId = response.shape_id;
                tempLayer.shapeName = name;
                tempLayer.shapeDescription = description;
                tempLayer.shapeType = type;
                tempLayer.shapeColor = color;
                tempLayer.tempShape = false;
                
                shapes.push(tempLayer);
                
                addShapeToSidebar(response.shape_id, name, description, type, color, tempLayer);
                
                updateShapePopup(tempLayer);
                
                enableShapeEditing(tempLayer);
            }
            
            showNotification('Shape berhasil disimpan!');
        },
        error: function(error) {
            console.error('Error saving shape:', error);
            showNotification('Gagal menyimpan shape. Silakan coba lagi.', true);
        }
    });
}

function prepareShapeData(layer, type, name, description, color) {
    var data = {
        nama: name,
        deskripsi: description,
        tipe: type,
        color: color,
        _token: '{{ csrf_token() }}'
    };
    
    switch(type) {
        case 'polyline':
        case 'polygon':
            var coords = layer.getLatLngs();
            if (type === 'polygon' && coords[0] && Array.isArray(coords[0])) {
                coords = coords[0];
            }
            data.koordinat = JSON.stringify(coords.map(function(latlng) {
                return [latlng.lat, latlng.lng];
            }));
            break;
        case 'circle':
            var center = layer.getLatLng();
            data.center_lat = center.lat;
            data.center_lng = center.lng;
            data.radius = layer.getRadius();
            break;
        case 'rectangle':
            var bounds = layer.getBounds();
            data.north = bounds.getNorth();
            data.south = bounds.getSouth();
            data.east = bounds.getEast();
            data.west = bounds.getWest();
            break;
    }
    
    return data;
}

function updateShapePopup(layer) {
    var name = layer.shapeName || 'Unnamed';
    var description = layer.shapeDescription || 'Tidak ada deskripsi';
    var type = layer.shapeType || 'shape';
    
    var popupContent = `<b>${name}</b><br>${description}<br>Tipe: ${type.charAt(0).toUpperCase() + type.slice(1)}`;
    layer.bindPopup(popupContent);
}

function searchShapes(searchParams) {
    $.ajax({
        url: 'shape/search',
        type: 'GET',
        data: searchParams,
        success: function(response) {
            if (response.success) {
                updateShapeList(response.data);
            }
        },
        error: function(error) {
            console.error('Error searching shapes:', error);
            showNotification('Gagal melakukan pencarian shapes.', true);
        }
    });
}

function updateShapeList(shapesData) {
    $('#shape-list').empty();
    shapes = [];
    drawnItems.clearLayers();
    
    $.each(shapesData, function (index, shapeData) {
        var shapeId = shapeData.shape_id;
        var name = shapeData.nama || "Shape " + (index + 1);
        var description = shapeData.deskripsi || "";
        var type = shapeData.tipe;
        var color = shapeData.color || '#3388ff';
        
        var layer = createShapeFromData(shapeData, color);
        
        if (layer) {
            layer.shapeId = shapeId;
            layer.shapeName = name;
            layer.shapeDescription = description;
            layer.shapeType = type;
            layer.shapeColor = color;
            
            updateShapePopup(layer);
            enableShapeEditing(layer);
            
            drawnItems.addLayer(layer);
            shapes.push(layer);
            
            addShapeToSidebar(shapeId, name, description, type, color, layer);
        }
    });
}

function enableShapeEditing(layer) {
    layer.on('click', function() {
        $('.shape-item').removeClass('active');
        $('#shape-item-' + layer.shapeId).addClass('active');
        activeShapeId = layer.shapeId;
    });
}

function addShapeToSidebar(shapeId, name, description, type, color, layer) {
    var shapeList = document.getElementById('shape-list');
    
    var listItem = document.createElement('li');
    listItem.className = 'shape-item';
    listItem.id = 'shape-item-' + shapeId;
    listItem.setAttribute('data-id', shapeId);
    
    var shapeInfo = getShapeInfo(layer, type);
    
    listItem.innerHTML = `
        <div class="shape-name">${name} <span class="shape-type ${type}">${type}</span></div>
        <div class="shape-description">${description || 'Tidak ada deskripsi'}</div>
        <div class="shape-info">${shapeInfo}</div>
        <div class="shape-actions">
            <button class="btn btn-zoom" onclick="zoomToShape(${shapeId})">Zoom</button>
            <button class="btn btn-edit" onclick="showEditForm('shape', ${shapeId})">Edit</button>
            <button class="btn btn-delete" onclick="confirmDeleteShape(${shapeId})">Hapus</button>
        </div>
    `;
    
    shapeList.appendChild(listItem);
}

function getShapeInfo(layer, type) {
    switch(type) {
        case 'circle':
            var center = layer.getLatLng();
            return `Pusat: ${center.lat.toFixed(6)}, ${center.lng.toFixed(6)}<br>Radius: ${layer.getRadius().toFixed(2)}m`;
        case 'rectangle':
            var bounds = layer.getBounds();
            return `Area: ${bounds.getNorth().toFixed(6)}, ${bounds.getWest().toFixed(6)} - ${bounds.getSouth().toFixed(6)}, ${bounds.getEast().toFixed(6)}`;
        case 'polyline':
            var coords = layer.getLatLngs();
            return `${coords.length} titik`;
        case 'polygon':
            var coords = layer.getLatLngs();
            if (coords[0] && Array.isArray(coords[0])) {
                coords = coords[0];
            }
            return `${coords.length} titik`;
        default:
            return 'Shape';
    }
}

function zoomToShape(shapeId) {
    var shape = shapes.find(s => s.shapeId === shapeId);
    if (shape) {
        if (shape.getBounds) {
            map.fitBounds(shape.getBounds());
        } else if (shape.getLatLng) {
            map.setView(shape.getLatLng(), 16);
        }
        
        shape.openPopup();
        
        $('.shape-item').removeClass('active');
        $('#shape-item-' + shapeId).addClass('active');
        activeShapeId = shapeId;
    }
}

function updateShapeDetails() {
    var shapeId = document.getElementById('edit-shape-id').value;
    var name = document.getElementById('edit-shape-name').value;
    var description = document.getElementById('edit-shape-description').value;
    var color = document.getElementById('edit-shape-color').value;
    
    if (!name) {
        alert('Silakan isi nama shape terlebih dahulu!');
        return;
    }
    
    var shape = shapes.find(s => s.shapeId === parseInt(shapeId));
    if (!shape) return;
    
    shape.shapeName = name;
    shape.shapeDescription = description;
    shape.shapeColor = color;
    
    if (shape.setStyle) {
        shape.setStyle({ color: color });
    }
    
    updateShapePopup(shape);
    
    $.ajax({
        url: 'shape/update-details',
        type: 'POST',
        data: {
            shape_id: shapeId,
            nama: name,
            deskripsi: description,
            color: color,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Shape details updated:', response);
            
            updateShapeSidebarItem(parseInt(shapeId), name, description, shape.shapeType, color, shape);
            
            hideEditForm('shape');
            
            showNotification('Shape berhasil diperbarui!');
        },
        error: function(error) {
            console.error('Error updating shape details:', error);
            showNotification('Gagal memperbarui shape. Silakan coba lagi.', true);
        }
    });
}

function updateShapeSidebarItem(shapeId, name, description, type, color, layer) {
    var shapeItem = document.getElementById('shape-item-' + shapeId);
    if (shapeItem) {
        var shapeInfo = getShapeInfo(layer, type);
        shapeItem.innerHTML = `
            <div class="shape-name">${name} <span class="shape-type ${type}">${type}</span></div>
            <div class="shape-description">${description || 'Tidak ada deskripsi'}</div>
            <div class="shape-info">${shapeInfo}</div>
            <div class="shape-actions">
                <button class="btn btn-zoom" onclick="zoomToShape(${shapeId})">Zoom</button>
                <button class="btn btn-edit" onclick="showEditForm('shape', ${shapeId})">Edit</button>
                <button class="btn btn-delete" onclick="confirmDeleteShape(${shapeId})">Hapus</button>
            </div>
        `;
    }
}

function confirmDeleteShape(shapeId) {
    if (confirm('Apakah Anda yakin ingin menghapus shape ini?')) {
        var shape = shapes.find(s => s.shapeId === shapeId);
        if (shape) {
            deleteShape(shapeId, shape);
        }
    }
}

function deleteShape(shapeId, shape) {
    if (!shapeId) return;
    
    $.ajax({
        url: 'shape/delete',
        type: 'POST',
        data: {
            shape_id: shapeId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Shape deleted:', response);
            
            drawnItems.removeLayer(shape);
            
            shapes = shapes.filter(s => s.shapeId !== shapeId);
            
            var shapeItem = document.getElementById('shape-item-' + shapeId);
            if (shapeItem) {
                shapeItem.remove();
            }
            
            if (document.getElementById('edit-shape-id').value == shapeId) {
                hideEditForm('shape');
            }
            
            showNotification('Shape berhasil dihapus!');
        },
        error: function(error) {
            console.error('Error deleting shape:', error);
            showNotification('Gagal menghapus shape. Silakan coba lagi.', true);
        }
    });
}

function cancelShape() {
    drawnItems.eachLayer(function(layer) {
        if (layer.tempShape) {
            drawnItems.removeLayer(layer);
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
    if (!isDrawingMode && !currentDrawType) {
        addMarkerWithForm(e.latlng);
    }
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
    
    $('#search-shape').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.shape-item').filter(function() {
            $(this).toggle(
                $(this).text().toLowerCase().indexOf(value) > -1
            );
        });
    });
});

$(document).ready(function() {
    $.getJSON('koordinat/json', function(data) {
        console.log('Loaded markers:', data);
        
        $('#marker-list').empty();
        
        $.each(data, function (index) {
            var latitude = parseFloat(data[index].latitude);
            var longitude = parseFloat(data[index].longitude);
            var name = data[index].name || "Marker " + (index + 1);
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
    
    $.getJSON('shape/json', function(data) {
        console.log('Loaded shapes:', data);
        
        $('#shape-list').empty();
        
        $.each(data, function (index, shapeData) {
            var shapeId = shapeData.shape_id;
            var name = shapeData.nama || "Shape " + (index + 1);
            var description = shapeData.deskripsi || "";
            var type = shapeData.tipe;
            var color = shapeData.color || '#3388ff';
            
            var layer = createShapeFromData(shapeData, color);
            
            if (layer) {
                layer.shapeId = shapeId;
                layer.shapeName = name;
                layer.shapeDescription = description;
                layer.shapeType = type;
                layer.shapeColor = color;
                
                updateShapePopup(layer);
                enableShapeEditing(layer);
                
                drawnItems.addLayer(layer);
                shapes.push(layer);
                
                addShapeToSidebar(shapeId, name, description, type, color, layer);
            }
        });
    });
});

function createShapeFromData(shapeData, color) {
    var layer = null;
    var style = { color: color };
    
        switch(shapeData.tipe) {
            case 'polyline':
                if (shapeData.koordinat) {
                    var coords = JSON.parse(shapeData.koordinat);
                    layer = L.polyline(coords, style);
                }
                break;
            case 'polygon':
                if (shapeData.koordinat) {
                    var coords = JSON.parse(shapeData.koordinat);
                    layer = L.polygon(coords, style);
                }
                break;
            case 'circle':
                if (shapeData.center_lat && shapeData.center_lng && shapeData.radius) {
                    layer = L.circle([shapeData.center_lat, shapeData.center_lng], {
                        radius: shapeData.radius,
                        ...style
                    });
                }
                break;
            case 'rectangle':
                if (shapeData.north && shapeData.south && shapeData.east && shapeData.west) {
                    var bounds = L.latLngBounds(
                        [shapeData.south, shapeData.west],
                        [shapeData.north, shapeData.east]
                    );
                    layer = L.rectangle(bounds, style);
                }
                break;
        }
    return layer;
}
</script>    
</body>
</html>