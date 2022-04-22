<?php

use App\Modules\vkApi;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
     dd(vkApi::getUploadSever(Config::get('vk.halloween.album'), Config::get('vk.halloween.group'), Config::get('vk.first.token'), Config::get('vk.first.version')));
});
