<?php

use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'media', 'middleware' => ['auth']],
    function () {
        \Juzaweb\FileManager\Media::browser();
    }
);
