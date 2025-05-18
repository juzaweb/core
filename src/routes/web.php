<?php

Route::group(
    ['prefix' => 'media', 'middleware' => ['auth']],
    function () {
        \Juzaweb\FileManager\Media::browser();
    }
);
