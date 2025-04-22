<?php

// use App\Models\RoomFacility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\RoomFacilityController;
use App\Http\Controllers\HotelFacilityController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/auth', [AdminController::class, 'showLoginForm'])->name('auth.index');
    Route::post('/auth/login', [AdminController::class, 'login'])->name('auth.login');
    Route::get('/dashboard', [RoomController::class, 'adminIndex'])->middleware('auth:admin')->name('admin.dashboard');
});

Route::post('/admin/logout', [AdminController::class, 'logout'])->name(
    'admin.logout'
)->middleware('auth:admin');

Route::get('/admin/dashboard', function () {
    $rooms = \App\Models\Room::all();
    return view('admin.dashboard', compact('rooms'));
})->name('admin.dashboard');

Route::get('/rooms', [RoomController::class, 'index'])->name('user.index');
Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('user.show');

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('admin.room.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('admin.store_room');
    Route::get('/rooms/{id}/edit', [RoomController::class, 'edit'])->name('admin.room.edit');
    Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('admin.update_room');
    Route::get('/rooms/{id}', [RoomController::class, 'adminShow'])->name('admin.room.show');
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('admin.room.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index'])->name('user.reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('user.reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('user.reservations.store');
    Route::get('/reservations/{id}', [ReservationController::class, 'show'])->name('user.reservations.show');
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('user.reservations.destroy');
    Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('user.reservations.update');
    Route::get('/history', [ReservationController::class, 'history'])->name('user.reservations.history');
    Route::get('reservations/{id}/pay', [ReservationController::class, 'pay'])->name('user.reservations.pay');
    Route::post('reservations/{id}/confirm-payment', [ReservationController::class, 'confirmPayment'])->name('user.reservations.confirmPayment');
    Route::get('scan/qr/checkin/{reservation_id}', [ReservationController::class, 'checkinViaQr'])->name('scan.qr.checkin');
    Route::get('/reservations/rebook/{id}', [ReservationController::class, 'rebook'])->name('reservations.rebook');
    Route::get('/reservations/{id}/download-pdf', [ReservationController::class, 'downloadPdf'])->name('user.reservations.pdf');
    Route::put('/reservations/{id}', [ReservationController::class, 'updateRecepsionist'])->name('reservations.update');
    Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('user.reservations.update');

    Route::put('/{id}', [ReservationController::class, 'updateStatus'])->name('update');

});
// Route::patch('receptionist/reservations/{id}', [ReceptionistReservationController::class, 'update'])->name('receptionist.reservations.update');

Route::get('/scan-success', function () {
    return view('user.reservations.scan_success');
})->name('scan.success');

Route::get('/qr/confirm/{id}', [ReceiptController::class, 'confirmScan'])->name('scan.qr.confirm');

Route::middleware(['auth', 'admin'])->prefix('admin/room_facility')->name('admin.room_facility.')->group(function () {
    Route::get('/index', [RoomFacilityController::class, 'adminIndex'])->name('index');
    Route::get('/create', [RoomFacilityController::class, 'create'])->name('create');
    Route::post('/', [RoomFacilityController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [RoomFacilityController::class, 'edit'])->name('edit');
    Route::put('/{id}', [RoomFacilityController::class, 'update'])->name('update');
    Route::delete('/{id}', [RoomFacilityController::class, 'destroy'])->name('destroy');
});

Route::prefix('receptionist')->group(function () {
    Route::get('/auth', [ReceptionistController::class, 'showLoginForm'])->name('receptionist.auth.index');
    Route::post('/auth', [ReceptionistController::class, 'login']);
    Route::post('/logout', [ReceptionistController::class, 'logout'])->name('receptionist.logout');
    Route::put('/receptionist/reservations/{id}/update-status', [ReceptionistController::class, 'updateStatus'])->name('receptionist.updateStatus');
    Route::get('/dashboard', [ReceptionistController::class, 'reservations'])
        ->middleware('auth:receptionist')
        ->name('receptionist.dashboard');
});
Route::get('/kamar', [RoomController::class, 'RecepsionistIndex'])->name('receptionist.kamar');

Route::get('/hotel-facility', [HotelFacilityController::class, 'index'])->name('user.hotel_facility.index');

Route::middleware(['auth', 'admin'])->prefix('admin/hotel_facility')->name('admin.hotel_facility.')->group(function () {
    Route::get('/index', [HotelFacilityController::class, 'adminIndex'])->name('index');
    Route::get('/create', [HotelFacilityController::class, 'create'])->name('create');
    Route::post('/', [HotelFacilityController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [HotelFacilityController::class, 'edit'])->name('edit');
    Route::put('/{id}', [HotelFacilityController::class, 'update'])->name('update');
    Route::delete('/{id}', [HotelFacilityController::class, 'destroy'])->name('destroy');
});

Route::get('/', function () {
    return view('awalan');
});
Auth::routes(
    [
        'verify' => true,
    ]
);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified');
