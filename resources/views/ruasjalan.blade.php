<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Peta Ruas Jalan</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(45deg, #667eea, #764ba2);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }
        .btn-back {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        .btn-logout {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            border: none;
            color: white;
        }
        .btn-logout:hover {
            background: linear-gradient(45deg, #ee5a52, #dc3545);
            color: white;
        }
        #map {
            height: 500px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .info-card {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        .loading-spinner {
            display: none;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .road-marker {
            background-color: #667eea;
            color: white;
            border-radius: 50%;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }
        .form-floating label {
            color: #6c757d;
        }
        .modal-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #5a6fd8, #6a4190);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-map me-2"></i>Peta Ruas Jalan
            </a>
            <div class="navbar-nav ms-auto d-flex flex-row gap-2">
                <a href="{{ route('dashboardruasjalan') }}" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                <form method="POST" action="{{ route('logoutakun') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card info-card">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-1">
                                    <i class="fas fa-road me-2"></i>
                                    Data Ruas Jalan
                                </h4>
                                <p class="mb-0 opacity-75">
                                    Visualisasi dan informasi lengkap ruas jalan dalam peta interaktif
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="loading-spinner">
                                    <div class="spinner-border text-light" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <button class="btn btn-light btn-sm me-2" onclick="openAddModal()">
                                    <i class="fas fa-plus me-1"></i>Tambah Ruas
                                </button>
                                <span class="badge bg-light text-dark" id="road-count">
                                    <i class="fas fa-road me-1"></i>0 Ruas Jalan
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map and Data Row -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marked-alt me-2"></i>
                            Peta Interaktif
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Daftar Ruas Jalan
                        </h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Ruas</th>
                                        <th>Panjang</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="road-table-body">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                            Memuat data...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Statistik
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-0" id="total-roads">0</h4>
                                    <small class="text-muted">Total Ruas</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-0" id="total-length">0 km</h4>
                                <small class="text-muted">Total Panjang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Road Modal -->
    <div class="modal fade" id="addRoadModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Tambah Ruas Jalan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addRoadForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="kode_ruas" name="kode_ruas" required>
                                    <label for="kode_ruas">Kode Ruas *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="nama_ruas" name="nama_ruas" required>
                                    <label for="nama_ruas">Nama Ruas *</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="panjang" name="panjang" step="0.01" required>
                                    <label for="panjang">Panjang (meter) *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="lebar" name="lebar" step="0.01" required>
                                    <label for="lebar">Lebar (meter) *</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-control" id="desa_id" name="desa_id" required>
                                        <option value="">Pilih Desa</option>
                                    </select>
                                    <label for="desa_id">Desa *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-control" id="eksisting_id" name="eksisting_id" required>
                                        <option value="">Pilih Eksisting</option>
                                    </select>
                                    <label for="eksisting_id">Eksisting *</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-control" id="kondisi_id" name="kondisi_id" required>
                                        <option value="">Pilih Kondisi</option>
                                    </select>
                                    <label for="kondisi_id">Kondisi *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-control" id="jenisjalan_id" name="jenisjalan_id" required>
                                        <option value="">Pilih Jenis Jalan</option>
                                    </select>
                                    <label for="jenisjalan_id">Jenis Jalan *</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <textarea class="form-control" id="keterangan" name="keterangan" style="height: 100px"></textarea>
                                <label for="keterangan">Keterangan</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="paths" name="paths" placeholder="Akan diisi otomatis dari peta">
                                <label for="paths">Paths (Koordinat)</label>
                            </div>
                            <small class="text-muted">Klik pada peta untuk menandai lokasi ruas jalan</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveNewRoad()">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0" id="loadingText">Memuat data ruas jalan...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Alerts -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div id="successAlert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
            <i class="fas fa-check-circle me-2"></i>
            <span id="successMessage"></span>
            <button type="button" class="btn-close" onclick="hideSuccess()"></button>
        </div>
        <div id="errorAlert" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
            <i class="fas fa-exclamation-circle me-2"></i>
            <span id="errorMessage"></span>
            <button type="button" class="btn-close" onclick="hideError()"></button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        let map;
        let roadMarkers = [];
        let roadData = [];
        let clickMarker = null;
        let masterData = {
            desa: [],
            eksisting: [],
            kondisi: [],
            jenisjalan: []
        };

        function initMap() {
            map = L.map('map').setView([-6.2088, 106.8456], 10);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            L.control.scale().addTo(map);

            // Add click event for adding new roads
            map.on('click', function(e) {
                if (document.getElementById('addRoadModal').classList.contains('show')) {
                    if (clickMarker) {
                        map.removeLayer(clickMarker);
                    }
                    
                    clickMarker = L.marker([e.latlng.lat, e.latlng.lng], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).addTo(map);
                    
                    // Simple path encoding (you might want to use a proper polyline encoding)
                    const paths = `${e.latlng.lat},${e.latlng.lng}`;
                    document.getElementById('paths').value = paths;
                }
            });
        }

        async function loadMasterData() {
            try {
                // Load all master data
                const [desaRes, eksistingRes, kondisiRes, jenisjalanRes] = await Promise.all([
                    fetch('/api/desa/1'),
                    fetch('/api/master/meksisting'),
                    fetch('/api/master/mkondisi'),
                    fetch('/api/master/mjenisjalan')
                ]);

                if (desaRes.ok) {
                    masterData.desa = await desaRes.json();
                    populateSelect('desa_id', masterData.desa, 'nama_desa');
                }

                if (eksistingRes.ok) {
                    masterData.eksisting = await eksistingRes.json();
                    populateSelect('eksisting_id', masterData.eksisting, 'nama_eksisting');
                }

                if (kondisiRes.ok) {
                    masterData.kondisi = await kondisiRes.json();
                    populateSelect('kondisi_id', masterData.kondisi, 'nama_kondisi');
                }

                if (jenisjalanRes.ok) {
                    masterData.jenisjalan = await jenisjalanRes.json();
                    populateSelect('jenisjalan_id', masterData.jenisjalan, 'nama_jenis');
                }

            } catch (error) {
                console.error('Error loading master data:', error);
            }
        }

        function populateSelect(selectId, data, nameField) {
            const select = document.getElementById(selectId);
            const currentValue = select.value;
            
            // Clear existing options except the first one
            while (select.children.length > 1) {
                select.removeChild(select.lastChild);
            }

            if (Array.isArray(data)) {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item[nameField] || item.nama || item.name || `Item ${item.id}`;
                    select.appendChild(option);
                });
            }

            // Restore selected value
            if (currentValue) {
                select.value = currentValue;
            }
        }

        async function loadRoadData() {
            try {
                showLoading(true, 'Memuat data ruas jalan...');
                
                const response = await fetch('{{ route("api.ruas-jalan") }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(`${data.message || 'HTTP error'} (Status: ${response.status})`);
                }
                
                if (data.error) {
                    throw new Error(data.message || 'Gagal memuat data');
                }

                let roads = [];
                if (data.ruasjalan) {
                    roads = data.ruasjalan;
                } else if (data.data) {
                    roads = data.data;
                } else if (Array.isArray(data)) {
                    roads = data;
                } else {
                    console.log('API Response format:', data);
                    throw new Error('Format response API tidak dikenali');
                }

                roadData = roads;
                displayRoadData(roadData);
                updateStatistics();
                
                console.log('Road data loaded:', roadData.length, 'items');
                
            } catch (error) {
                console.error('Error loading road data:', error);
                showError('Gagal memuat data ruas jalan: ' + error.message);
                
                const tableBody = document.getElementById('road-table-body');
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error: ${error.message}
                        </td>
                    </tr>
                `;
            } finally {
                showLoading(false);
            }
        }

        function displayRoadData(roads) {
            roadMarkers.forEach(marker => map.removeLayer(marker));
            roadMarkers = [];

            const tableBody = document.getElementById('road-table-body');
            tableBody.innerHTML = '';

            if (!roads || roads.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada data ruas jalan
                        </td>
                    </tr>
                `;
                return;
            }

            let bounds = [];

            roads.forEach((road, index) => {
                if (road.lat && road.lng) {
                    const lat = parseFloat(road.lat);
                    const lng = parseFloat(road.lng);
                    
                    if (!isNaN(lat) && !isNaN(lng)) {
                        const marker = L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup(`
                                <div class="p-2">
                                    <h6 class="mb-2">${road.nama_ruas || 'Nama tidak tersedia'}</h6>
                                    <p class="mb-1"><strong>Kode:</strong> ${road.kode_ruas || 'N/A'}</p>
                                    <p class="mb-1"><strong>Panjang:</strong> ${road.panjang || 'N/A'} km</p>
                                    <p class="mb-1"><strong>Lebar:</strong> ${road.lebar || 'N/A'} m</p>
                                    <p class="mb-0"><strong>Kondisi:</strong> ${road.kondisi || 'N/A'}</p>
                                </div>
                            `);
                        
                        roadMarkers.push(marker);
                        bounds.push([lat, lng]);
                    }
                }

                const row = `
                    <tr onclick="focusRoad(${index})" style="cursor: pointer;" class="road-row" data-index="${index}">
                        <td>${road.nama_ruas || 'N/A'}</td>
                        <td>${road.panjang || 'N/A'} km</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="event.stopPropagation(); viewRoadDetail(${index})" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); deleteRoad(${index})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [20, 20] });
            }

            document.getElementById('road-count').innerHTML = `<i class="fas fa-road me-1"></i>${roads.length} Ruas Jalan`;
        }

        function focusRoad(index) {
            if (roadData[index] && roadData[index].lat && roadData[index].lng) {
                const lat = parseFloat(roadData[index].lat);
                const lng = parseFloat(roadData[index].lng);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    map.setView([lat, lng], 15);
                    roadMarkers[index].openPopup();
                }
            }

            document.querySelectorAll('.road-row').forEach(row => row.classList.remove('table-active'));
            document.querySelector(`[data-index="${index}"]`).classList.add('table-active');
        }

        function viewRoadDetail(index) {
            const road = roadData[index];
            if (road) {
                alert(`Detail Ruas Jalan:\n\nKode: ${road.kode_ruas || 'N/A'}\nNama: ${road.nama_ruas || 'N/A'}\nPanjang: ${road.panjang || 'N/A'} km\nLebar: ${road.lebar || 'N/A'} m\nKondisi: ${road.kondisi || 'N/A'}\nKeterangan: ${road.keterangan || 'N/A'}`);
            }
        }

        async function deleteRoad(index) {
            const road = roadData[index];
            if (!road || !road.id) {
                showError('Data ruas jalan tidak valid');
                return;
            }

            if (!confirm(`Apakah Anda yakin ingin menghapus ruas jalan "${road.nama_ruas || 'N/A'}"?`)) {
                return;
            }

            try {
                showLoading(true, 'Menghapus ruas jalan...');

                const response = await fetch(`/api/ruas-jalan/${road.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        paths: road.paths || '',
                        desa_id: road.desa_id || '',
                        kode_ruas: road.kode_ruas || '',
                        nama_ruas: road.nama_ruas || '',
                        panjang: road.panjang || '',
                        lebar: road.lebar || '',
                        eksisting_id: road.eksisting_id || '',
                        kondisi_id: road.kondisi_id || '',
                        jenisjalan_id: road.jenisjalan_id || '',
                        keterangan: road.keterangan || ''
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showSuccess('Ruas jalan berhasil dihapus');
                    await loadRoadData(); // Reload data
                } else {
                    throw new Error(result.message || 'Gagal menghapus ruas jalan');
                }

            } catch (error) {
                console.error('Error deleting road:', error);
                showError('Gagal menghapus ruas jalan: ' + error.message);
            } finally {
                showLoading(false);
            }
        }

        function openAddModal() {
            document.getElementById('addRoadForm').reset();
            if (clickMarker) {
                map.removeLayer(clickMarker);
                clickMarker = null;
            }
            
            const modal = new bootstrap.Modal(document.getElementById('addRoadModal'));
            modal.show();
        }

        async function saveNewRoad() {
            const form = document.getElementById('addRoadForm');
            const formData = new FormData(form);
            
            // Validation
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (!formData.get('paths')) {
                showError('Silakan klik pada peta untuk menandai lokasi ruas jalan');
                return;
            }

            try {
                showLoading(true, 'Menyimpan ruas jalan baru...');

                const data = {
                    paths: formData.get('paths'),
                    desa_id: formData.get('desa_id'),
                    kode_ruas: formData.get('kode_ruas'),
                    nama_ruas: formData.get('nama_ruas'),
                    panjang: formData.get('panjang'),
                    lebar: formData.get('lebar'),
                    eksisting_id: formData.get('eksisting_id'),
                    kondisi_id: formData.get('kondisi_id'),
                    jenisjalan_id: formData.get('jenisjalan_id'),
                    keterangan: formData.get('keterangan')
                };

                const response = await fetch('/api/ruas-jalan', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showSuccess('Ruas jalan baru berhasil ditambahkan');
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addRoadModal'));
                    modal.hide();
                    
                    // Clean up
                    if (clickMarker) {
                        map.removeLayer(clickMarker);
                        clickMarker = null;
                    }
                    
                    // Reload data
                    await loadRoadData();
                } else {
                    throw new Error(result.message || 'Gagal menambahkan ruas jalan');
                }

            } catch (error) {
                console.error('Error saving road:', error);
                showError('Gagal menyimpan ruas jalan: ' + error.message);
            } finally {
                showLoading(false);
            }
        }

        function updateStatistics() {
            const totalRoads = roadData.length;
            const totalLength = roadData.reduce((sum, road) => {
                const length = parseFloat(road.panjang) || 0;
                return sum + length;
            }, 0);

            document.getElementById('total-roads').textContent = totalRoads;
            document.getElementById('total-length').textContent = totalLength.toFixed(2) + ' km';
        }

        function showLoading(show, text = 'Memuat...') {
            const modal = document.getElementById('loadingModal');
            const loadingText = document.getElementById('loadingText');
            
            if (show) {
                loadingText.textContent = text;
                new bootstrap.Modal(modal).show();
            } else {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        }

        function showSuccess(message) {
            document.getElementById('successMessage').textContent = message;
            document.getElementById('successAlert').style.display = 'block';
            document.getElementById('successAlert').classList.add('show');
            
            setTimeout(() => {
                hideSuccess();
            }, 5000);
        }

        function hideSuccess() {
            document.getElementById('successAlert').classList.remove('show');
            setTimeout(() => {
                document.getElementById('successAlert').style.display = 'none';
            }, 150);
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorAlert').style.display = 'block';
            document.getElementById('errorAlert').classList.add('show');
        }

        function hideError() {
            document.getElementById('errorAlert').classList.remove('show');
            setTimeout(() => {
                document.getElementById('errorAlert').style.display = 'none';
            }, 150);
        }

        function refreshData() {
            loadRoadData();
        }

        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            loadMasterData();
            loadRoadData();
        });
    </script>
</body>
</html>