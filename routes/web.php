<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KoordinatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PointMapController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ShapeController;
use App\Http\Controllers\RuasJalanController;

// Rute untuk halaman publik
Route::get('/', function () {
    return view('publicpage');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [KoordinatController::class, 'index']);
});

// Rute untuk halaman login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/loginakun', [AuthController::class, 'showLoginruasjalan'])->name('loginruasjalan');
Route::post('/loginakun', [AuthController::class, 'loginruasjalan'])->name('loginruasjalan');
Route::get('/registerruasjalan', [AuthController::class, 'showRegisterruasjalan'])->name('registerruasjalan');
Route::post('/registerakun', [AuthController::class, 'registerruasjalan'])->name('registerakun');;
Route::post('/logoutruasjalan', [AuthController::class, 'logoutruasjalan'])->name('logoutakun');
Route::get('/dashboardruasjalan', [AuthController::class, 'dashboardruasjalan'])->name('dashboardruasjalan');

Route::get('koordinat/json', [PointMapController::class, 'getAll']);
Route::post('koordinat/save', [PointMapController::class, 'store']);
Route::post('koordinat/update', [PointMapController::class, 'update']);
Route::post('koordinat/delete', [PointMapController::class, 'destroy']);
Route::post('koordinat/update-details', [PointMapController::class, 'updateDetails']);

Route::get('shape/json', [ShapeController::class, 'getShapesJson']);
Route::get('shape/search', [ShapeController::class, 'search']);
Route::get('shape/statistics', [ShapeController::class, 'getStatistics']);
Route::get('shape/{id}', [ShapeController::class, 'show']);
Route::post('shape/save', [ShapeController::class, 'store']);
Route::post('shape/update-details', [ShapeController::class, 'updateDetails']);
Route::post('shape/update-geometry', [ShapeController::class, 'updateGeometry']);
Route::post('shape/delete', [ShapeController::class, 'destroy']);

Route::get('/ruas-jalan', [RuasJalanController::class, 'ruasJalan'])->name('ruas-jalan');
Route::get('/api/ruas-jalan', [RuasJalanController::class, 'getRuasJalanData'])->name('api.ruas-jalan');
Route::post('/api/ruas-jalan', [RuasJalanController::class, 'addRuasJalan'])->name('api.ruas-jalan.store');
Route::delete('/api/ruas-jalan/{id}', [RuasJalanController::class, 'deleteRuasJalan'])->name('api.ruas-jalan.delete');

Route::get('/api/provinsi/{id?}', [RuasJalanController::class, 'getProvinsi'])->name('api.provinsi');
Route::get('/api/kabupaten/{id?}', [RuasJalanController::class, 'getKabupaten'])->name('api.kabupaten');
Route::get('/api/kecamatan/{id?}', [RuasJalanController::class, 'getKecamatan'])->name('api.kecamatan');
Route::get('/api/desa/{id?}', [RuasJalanController::class, 'getDesa'])->name('api.desa');

Route::get('/api/master/{type}', [RuasJalanController::class, 'getMasterData'])->name('api.master');

Route::get('/api/kecamatanbydesaid/{id}', function($id) {
    return app(RuasJalanController::class)->makeApiRequest('GET', '/kecamatanbydesaid/' . $id, [], Session::get('token'));
});

Route::get('/api/kabupatenbykecamatanid/{id}', function($id) {
    return app(RuasJalanController::class)->makeApiRequest('GET', '/kabupatenbykecamatanid/' . $id, [], Session::get('token'));
});

Route::get('/api/provinsibykabupatenid/{id}', function($id) {
    return app(RuasJalanController::class)->makeApiRequest('GET', '/provinsibykabupatenid/' . $id, [], Session::get('token'));
});