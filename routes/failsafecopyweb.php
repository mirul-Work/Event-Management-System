<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NavController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventsController;
//use App\Http\Controllers\SessionController;
use App\Http\Controllers\OrganizerController;

// Home Route
Route::get('/', [NavController::class, 'ReturnHome'])->name('home');
// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    //dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    //user
    Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    //organizer
    Route::get('/organizers', [AdminController::class, 'indexOrganizers'])->name('organizers.index');
    Route::get('/organizers/create', [AdminController::class, 'createOrganizer'])->name('organizers.create');
    Route::post('/organizers', [AdminController::class, 'storeOrganizer'])->name('organizers.store');
    Route::get('/organizers/{id}/edit', [AdminController::class, 'editOrganizer'])->name('organizers.edit');
    Route::put('/organizers/{id}', [AdminController::class, 'updateOrganizer'])->name('organizers.update');
    Route::delete('/organizers/{id}', [AdminController::class, 'deleteOrganizer'])->name('organizers.delete');
    //event
    Route::get('/events', [AdminController::class, 'indexEvents'])->name('events.index');
    Route::get('/events/create', [AdminController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{id}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{id}', [AdminController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{id}', [AdminController::class, 'destroy'])->name('events.delete');
});

Route::prefix('organizer')->name('organizer.')->group(function () {
    //dashboard
    Route::get('/', [OrganizerController::class, 'dashboard'])->name('dashboard');
    // Authentication routes
    Route::get('/register', [OrganizerController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [OrganizerController::class, 'register'])->name('register');
    Route::get('/login', [OrganizerController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [OrganizerController::class, 'login'])->name('login');

    Route::post('/logout', [OrganizerController::class, 'logout'])->name('logout');
    //user
    Route::get('/users', [OrganizerController::class, 'indexUsers'])->name('users.index');
    Route::get('/users/create', [OrganizerController::class, 'createUser'])->name('users.create');
    Route::post('/users', [OrganizerController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [OrganizerController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [OrganizerController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [OrganizerController::class, 'deleteUser'])->name('users.delete');
    //organizer delete soon!!!!!!!
    Route::get('/organizers', [OrganizerController::class, 'indexOrganizers'])->name('organizers.index');
    Route::get('/organizers/create', [OrganizerController::class, 'createOrganizer'])->name('organizers.create');
    Route::post('/organizers', [OrganizerController::class, 'storeOrganizer'])->name('organizers.store');
    Route::get('/organizers/{id}/edit', [OrganizerController::class, 'editOrganizer'])->name('organizers.edit');
    Route::put('/organizers/{id}', [OrganizerController::class, 'updateOrganizer'])->name('organizers.update');
    Route::delete('/organizers/{id}', [OrganizerController::class, 'deleteOrganizer'])->name('organizers.delete');
    //event
    Route::get('/events', [OrganizerController::class, 'indexEvents'])->name('events.index');
    Route::get('/events/create', [OrganizerController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [OrganizerController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{id}/edit', [OrganizerController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{id}', [OrganizerController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{id}', [OrganizerController::class, 'destroy'])->name('events.delete');

});

//User Routes
Route::prefix('user')->name('user.')->group(function(){
//    Route::get('/manage-users', [UserController::class, 'index'])->name('manage.users');
    Route::resource('user',UserController::class);
});

//Event Route
Route::get('/organizer/{organizer_id}/events', [EventsController::class, 'index'])->name('organizer.events.index');




