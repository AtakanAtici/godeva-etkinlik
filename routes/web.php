<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ParticipantController;

Route::get('/', function () {
    if (session('authenticated')) {
        return redirect('/host');
    }
    return redirect('/login');
});

Route::prefix('api')->group(function () {
    Route::post('rooms', [RoomController::class, 'store']);
    Route::get('rooms/{code}', [RoomController::class, 'show']);
    Route::get('rooms/{room}/dashboard', [RoomController::class, 'dashboard']);
    Route::put('rooms/{room}/status', [RoomController::class, 'updateStatus']);

    Route::post('rooms/{room}/questions', [QuestionController::class, 'store']);
    Route::put('questions/{question}/publish', [QuestionController::class, 'publish']);
    Route::put('questions/{question}/close', [QuestionController::class, 'close']);
    Route::get('questions/{question}', [QuestionController::class, 'show']);

    Route::post('join/{roomCode}', [ParticipantController::class, 'join']);
    Route::post('heartbeat/{roomCode}', [ParticipantController::class, 'heartbeat']);

    Route::post('questions/{question}/answers', [AnswerController::class, 'store'])->middleware('throttle:10,1');
    Route::put('answers/{answer}/moderate', [AnswerController::class, 'moderate']);
});

Route::get('/join/{code}', function (string $code) {
    return view('join', ['roomCode' => $code]);
});

// Login routes
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/logout', function () {
    session()->forget('authenticated');
    return redirect('/login');
})->name('logout');

// Host panel (protected)
Route::get('/host/{room}', function ($room) {
    return view('host', ['roomId' => $room]);
})->middleware('auth.host');

// Main host page
Route::get('/host', function () {
    return view('host-list');
})->middleware('auth.host');

Route::get('/presentation/{code}', function (string $code) {
    return view('presentation', ['roomCode' => $code]);
});

// Report route
Route::get('/host/room/{room}/report', [App\Http\Controllers\ReportController::class, 'roomReport'])
    ->middleware('auth.host')
    ->name('room.report');
