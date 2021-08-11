<?php

namespace Juzaweb\Core\Http\Controllers\FileManager;

use Juzaweb\Core\Http\Controllers\Controller;
use Illuminate\Support\Str;

class FilemanagerController extends Controller
{
    protected static $success_response = 'OK';
    
    public function index()
    {
        $type = $this->getType();
        $mimeTypes = config("juzaweb.filemanager.types.{$type}.valid_mime");

        if (empty($mimeTypes)) {
            return abort(404);
        }

        return view('juzaweb::filemanager.index', [
            'mimeTypes' => $mimeTypes
        ]);
    }
    
    public function getErrors()
    {
        $arr_errors = [];

        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            array_push($arr_errors, trans('juzaweb::filemanager.message_extension_not_found'));
        }

        if (! extension_loaded('exif')) {
            array_push($arr_errors, 'EXIF extension not found.');
        }

        if (! extension_loaded('fileinfo')) {
            array_push($arr_errors, 'Fileinfo extension not found.');
        }
        
        return $arr_errors;
    }
    
    public function error($error_type, $variables = [])
    {
        throw new \Exception(trans('juzaweb::filemanager.error_' . $error_type, $variables));
    }
    
    protected function getType()
    {
        $type = strtolower(request()->get('type'));
        return Str::singular($type);
    }
    
    protected function getPath($url)
    {
        $explode = explode('uploads/', $url);
        if (isset($explode[1])) {
            return $explode[1];
        }
        return $url;
    }
    
    protected function isDirectory($file)
    {
        if (is_numeric($file)) {
            return true;
        }
        
        return false;
    }
}
