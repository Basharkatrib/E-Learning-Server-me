<?php

use App\Events\MyEvent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/trigger', function () {
    $data = [
        'message' => 'Hii'
    ];
    broadcast(new MyEvent($data));
    return 'Event Triggered';
});
