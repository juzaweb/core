<?php

namespace Juzaweb\Modules\Core\FileManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

abstract class FileManagerController extends Controller
{
    protected static string $success_response = 'OK';

    public function throwError($type, $variables = []): void
    {
        throw new \RuntimeException(trans('cms::filemanager.error_'.$type, $variables));
    }

    protected function getType(): string
    {
        $type = strtolower(request()->get('type'));

        return Str::singular($type);
    }

    protected function getPath($url): string
    {
        $explode = explode('uploads/', $url);
        if (isset($explode[1])) {
            return $explode[1];
        }

        return $url;
    }

    protected function isDirectory($file): bool
    {
        if (is_numeric($file)) {
            return true;
        }

        return false;
    }
}
