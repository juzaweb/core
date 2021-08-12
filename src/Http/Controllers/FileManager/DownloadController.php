<?php

namespace Juzaweb\Core\Http\Controllers\FileManager;

use Illuminate\Support\Facades\Storage;
use Juzaweb\Core\Models\File;

class DownloadController extends FileManagerController
{
    public function getDownload()
    {
        $file = $this->getPath(request()->get('file'));
        $data = File::where('path', '=', $file)->first(['name']);
        
        $path = Storage::disk(config('juzaweb.filemanager.disk'))->path($file);
        if ($data) {
            return response()->download($path, $data->name);
        }
        
        return response()->download($path);
    }
}
