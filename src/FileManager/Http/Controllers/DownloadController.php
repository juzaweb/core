<?php

namespace Juzaweb\Modules\Core\FileManager\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\Models\Media;

class DownloadController extends FileManagerController
{
    public function getDownload()
    {
        $file = $this->getPath(request()->get('file'));
        $file = $this->cleanPath($file);

        $data = Media::where('path', '=', $file)->first(['name']);

        $storage = Storage::disk(config('juzaweb.filemanager.disk'));
        if (! $storage->exists($file)) {
            abort(404);
        }

        $path = $storage->path($file);
        if ($data) {
            return response()->download($path, $data->name);
        }

        return response()->download($path);
    }
}
