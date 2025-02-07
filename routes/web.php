<?php
use App\Http\Controllers\FriendController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;

Route::get('/', function () {
    return view('index');
});
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'create']);
    Route::post('/register', [RegisterUserController::class, 'store']);
    Route::get('/login', [SessionController::class, 'create']);
    Route::post('/login', [SessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
Route::get('fullcalender', [ScheduleController::class, 'index']);
Route::get('/events', [ScheduleController::class, 'getEvents']);
Route::get('/schedule/delete/{id}', [ScheduleController::class, 'deleteEvent']);
Route::post('/schedule/{id}', [ScheduleController::class, 'update']);
Route::post('/schedule/{id}/resize', [ScheduleController::class, 'resize']);
Route::get('/events/search', [ScheduleController::class, 'search']);
Route::view('add-schedule', 'schedule.add');
Route::post('create-schedule', [ScheduleController::class, 'create']);
Route::delete('/logout', action: [SessionController::class, 'destroy']);
});
Route::get('/friend', [FriendController::class, 'index'])->name('friend.index');
Route::get('/friend/{user}/calendar', [FriendController::class, 'showCalendar'])->name('friend.calendar');
