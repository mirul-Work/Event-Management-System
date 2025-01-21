<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NavController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RSVPController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Controllers\OrganizerDashboardController;

//Home Route
Route::get('/', [NavController::class, 'ReturnHome'])->name('home');

//login route
Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'verify'])->name('auth.verify');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
//register
Route::get('/register', [AuthController::class, 'indexRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
//forgot route
Route::get('password/reset', [AuthController::class, 'indexReset'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'reset'])->name('password.update');

//dashboard
// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AdminDashboardController::class, 'showProfile'])->name('profile');
    Route::post('/profile/update', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');
    // Organizers Management
    Route::get('/organizers', [AdminDashboardController::class, 'indexOrganizers'])->name('organizers.index');
    Route::get('/organizers/create', [AdminDashboardController::class, 'createOrganizer'])->name('organizers.create');
    Route::post('/organizers', [AdminDashboardController::class, 'storeOrganizer'])->name('organizers.store');
    Route::get('/organizers/{id}/edit', [AdminDashboardController::class, 'editOrganizer'])->name('organizers.edit');
    Route::get('/organizers/{id}/events', [AdminDashboardController::class, 'showOrganizerEvents'])->name('organizers.show-events');
    Route::put('/organizers/{id}', [AdminDashboardController::class, 'updateOrganizer'])->name('organizers.update');
    Route::delete('/organizers/{id}', [AdminDashboardController::class, 'deleteOrganizer'])->name('organizers.delete');
    //new events management
    Route::resource('events', EventsController::class); // You can adjust this as needed
    // Approve and Disapprove events
    Route::post('events/{eventId}/approve', [AdminDashboardController::class, 'approve'])->name('events.approve');
    Route::post('events/{eventId}/disapprove', [AdminDashboardController::class, 'disapprove'])->name('events.disapprove');
});


Route::prefix('organizer')->name('organizer.')->middleware('auth:organizer')->group(function () {
    //dashboard
    Route::get('/dashboard', [OrganizerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [OrganizerDashboardController::class, 'showProfile'])->name('profile');
    Route::post('/profile/update', [OrganizerDashboardController::class, 'updateProfile'])->name('profile.update');


    //attendee
    // Route::get('/attendes', [AttendeeController::class, 'indexAttendes'])->name('attendees.index');
    // Route::get('/attendes/create', [AttendeeController::class, 'createAttende'])->name('attendees.create');
    // Route::post('/attendes', [AttendeeController::class, 'storeAttende'])->name('attendees.store');
    // Route::get('/attendes/{id}/edit', [AttendeeController::class, 'editAttende'])->name('attendees.edit');
    // Route::put('/attendes/{id}', [AttendeeController::class, 'updateAttende'])->name('attendees.update');
    // Route::delete('/attendes/{id}', [AttendeeController::class, 'deleteAttende'])->name('attendees.delete');
    // Route::get('/attendes/manual', [AttendeeController::class, 'indexManual'])->name('attendees.add');

    // Route::resource('attendees', AttendeeController::class);
    //attende controller
    Route::get('/attendees/{user_id}/', [AttendeeController::class, 'index'])->name('attendees.index');
    // Route::get('/attendees/search', [AttendeeController::class, 'searchAttendees']);
    Route::get('/events/attendees/{user_id}/', [AttendeeController::class, 'showEventAttendees'])->name('attendees.showEventAttendees');
    Route::delete('/events/attendees/{id}/', [AttendeeController::class, 'destroy'])->name('attendees.destroy');
    Route::get('/events/{event}/attendees/manual', [AttendeeController::class, 'create'])->name('attendees.create');
    Route::post('/organizer/events/{event}/attendees/manual', [AttendeeController::class, 'store'])->name('attendees.store');
    Route::get('/organizer/events/{id}/attendees', [AttendeeController::class, 'index'])->name('organizer.attendees.index');
    Route::post('/events/{event}/attendees/manual', [AttendeeController::class, 'addManual'])->name('attendees.addManual');

    Route::get('/events/{event}/attendees/import', [AttendeeController::class, 'showImportForm'])->name('attendees.import.form');
    Route::post('/events/{event}/attendees/import', [AttendeeController::class, 'import'])->name('attendees.import');
    Route::post('/events/{event}/attendees/{attendee}/send-email', [AttendeeController::class, 'sendEmail'])->name('attendees.sendEmail');
    // Route::post('/events/{event}/attendees/{attendee}/send-emails', [AttendeeController::class, 'sendEmailAll'])->name('attendees.sendEmailAll');
    Route::post('/events/{eventId}/attendees/send-emails', [AttendeeController::class, 'sendEmailAll'])->name('attendees.sendEmailAll');



    //Event Controller
    Route::resource('events', EventsController::class); // You can adjust this as needed
    Route::post('events/{eventId}/upload-attendees', [EventsController::class, 'uploadAttendees'])->name('events.upload-attendees');
    // Route::post('events/{event}/rsvp/{seat}', [EventsController::class, 'rsvp'])->name('rsvp');
    Route::get('events/{event}/rsvp', [EventsController::class, 'rsvp'])->name('rsvp');
    // Route::post('emails/invitation/{eventId}', [EventsController::class, 'sendInvitation'])->name('emails.invitation');
    Route::post('emails/invitation/{eventId}', [InvitationController::class, 'sendInvitation'])->name('emails.invitation');
    Route::get('events/{id}/links', [EventsController::class, 'showLinks'])->name('events.links');
    Route::get('organizer/events/{eventId}/rsvp/{token}', [EventsController::class, 'handleRSVP'])->name('events.rsvp');
    Route::post('/organizer/events/{eventId}/send-invitations', [EventsController::class, 'sendInvitations'])
        ->name('emails.invitation');
});




Route::get('rsvp/{seat_category}/{token}', [RSVPController::class, 'show'])->name('rsvp.show');
Route::post('/rsvp/{seat_category}/{token}', [RsvpController::class, 'rsvp'])->name('rsvp.submit');
Route::get('/already-responded/{seat_category}/{token}', [RsvpController::class, 'alreadyResponded'])->name('already.responded');
