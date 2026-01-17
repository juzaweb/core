<?php

namespace Juzaweb\Modules\Core\FileManager\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\Http\Controllers\Controller;
use Juzaweb\Modules\Core\Models\Media;

class DownloadController extends Controller
{
    public function getDownload()
    {
        $file = $this->getPath(request()->get('file'));
        $data = Media::where('path', '=', $file)->first(['name']);

        $path = Storage::disk(config('juzaweb.filemanager.disk'))->path($file);
        if ($data) {
            return response()->download($path, $data->name);
        }

        return response()->download($path);
    }
}
