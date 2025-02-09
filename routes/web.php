<?php
use App\Http\Controllers\FriendController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;

Route::get('/', function () {
    return view('index');
});
//Routes for the guest user
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'store']);
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store']);
    Route::get('/about', function() {
        return view('about');
    })->name('about');
});
//Schedule Routes for the logged in user
Route::middleware('auth')->group(function () {
Route::get('fullcalender', [ScheduleController::class, 'index']);
Route::get('/events', [ScheduleController::class, 'getEvents']);
Route::get('/schedule/delete/{id}', [ScheduleController::class, 'deleteEvent']);
Route::post('/schedule/{id}', [ScheduleController::class, 'update']);
Route::post('/schedule/{id}/resize', [ScheduleController::class, 'resize']);
Route::get('/events/search', [ScheduleController::class, 'search']);
Route::view('add-schedule', 'components.calendar.add');
Route::post('create-schedule', [ScheduleController::class, 'create']);
Route::delete('/logout', action: [SessionController::class, 'destroy']);
Route::get('/schedule/{id}/info', [ScheduleController::class, 'info']);

});

//Friend Routes
Route::middleware('auth')->group(function () {
    Route::get('/friend', [FriendController::class, 'index'])->name('friend.index');
    Route::get('/friend/{user}/calendar', [FriendController::class, 'showCalendar'])->name('friend.calendar');
    Route::get('/friend/{user}/add-schedule', [FriendController::class, 'addSchedule'])->name('friend.add');
    Route::post('/friend/{user}/create-schedule', [FriendController::class, 'createSchedule'])->name('friend.create-schedule');
    Route::delete('/friend/{schedule}/delete', [FriendController::class, 'deleteEvent'])->name('friend.delete-event');
    Route::get('/friend/{user}/events', [FriendController::class, 'getEvents'])->name('friend.events');
    Route::post('/friend/{schedule}/update', [FriendController::class, 'update'])->name('friend.update');
    Route::post('/friend/{schedule}/resize', [FriendController::class, 'resize'])->name('friend.resize');
    Route::get('/friend/{schedule}/info', [FriendController::class, 'info'])->name('friend.info');
});
