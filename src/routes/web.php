<?php

Route::group(
    ['prefix' => 'media/browser', 'middleware' => ['auth']],
    function () {
        \Juzaweb\FileManager\Media::browser();
    }
);
