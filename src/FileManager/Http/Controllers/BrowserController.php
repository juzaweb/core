<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Modules\Core\FileManager\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\FileManager\Enums\MediaType;
use Juzaweb\Modules\Core\Models\Media;

class BrowserController extends FileManagerController
{
    public function index(Request $request, string $websiteId, string $disk): View
    {
        if ($type = $this->getType()) {
            $mimeTypes = config("media.types.{$type}");
        } else {
            $mimeTypes = config("media.disks.{$disk}.mime_types");
        }

        $maxSize = config("media.disks.{$disk}.max_size");
        $multiChoose = $request->get('multichoose', 0);

        return view(
            'admin::file-manager.index',
            compact(
                'mimeTypes',
                'maxSize',
                'multiChoose',
                'disk',
                'type',
            )
        );
    }

    public function items(Request $request, string $websiteId, string $disk): array
    {
        $type = $this->getType();
        $currentPage = $request->input('page', 1);
        $perPage = 15;

        $workingDir = $request->get('working_dir');

        $medias = Media::query()
            ->where('parent_id', $workingDir)
            ->where('disk', $disk)
            ->when($type, function ($q) use ($type) {
                $q->where(function ($query) use ($type) {
                    $query->where('type', MediaType::DIRECTORY)
                          ->orWhere(function ($q) use ($type) {
                              $q->where('type', MediaType::FILE)
                                ->whereIn('mime_type', config("media.types.{$type}"));
                          });
                });
            })->orderBy('type', 'ASC')->orderBy('id', 'DESC')->paginate($perPage);

        $items = $medias->map(function ($item) {
            $isImage = $item->isImage();

            return [
                'id' => $item->id,
                'icon' => $item->type === MediaType::DIRECTORY
                    ? 'fa-folder-o' : ($isImage ? 'fa-image' : 'fa-file'),
                'is_file' => $item->type !== MediaType::DIRECTORY,
                'is_image' => $isImage,
                'name' => $item->name,
                'thumb_url' => $item->type === MediaType::DIRECTORY
                    ? asset('assets/images/folder.png') : ($isImage ? upload_url($item->path) : null),
                'time' => $item->type === MediaType::DIRECTORY ? false : strtotime($item->created_at),
                'url' => $item->type === MediaType::DIRECTORY ? $item->id : upload_url($item->path),
                'path' => $item->type === MediaType::DIRECTORY ? $item->id : $item->path,
            ];
        });

        return [
            'items' => $items,
            'paginator' => [
                'current_page' => $currentPage,
                'total' => $medias->total(),
                'per_page' => $perPage,
            ],
            'display' => 'grid',
            'working_dir' => $workingDir,
        ];
    }

    public function getErrors(): array
    {
        $errors = [];
        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            $errors[] = trans('admin::browser.message_extension_not_found', ['name' => 'gd']);
        }

        if (! extension_loaded('exif')) {
            $errors[] = trans('admin::browser.message_extension_not_found', ['name' => 'exif']);
        }

        if (! extension_loaded('fileinfo')) {
            $errors[] = trans('admin::browser.message_extension_not_found', ['name' => 'fileinfo']);
        }

        return $errors;
    }

    public function delete(Request $request, string $websiteId, string $disk)
    {
        $itemNames = $request->post('items');
        $errors = [];

        foreach ($itemNames as $file) {
            if (is_null($file)) {
                $errors[] = parent::error('folder-name');
                continue;
            }

            $is_directory = $this->isDirectory($file);
            if ($is_directory) {
                Media::where(['disk' => $disk, 'id' => $file])->first()->deleteFolder();
            } else {
                $file_path = $this->getPath($file);
                Media::where('path', '=', $file_path)
                    ->first()
                    ->delete();
            }
        }

        if (count($errors) > 0) {
            return $errors;
        }

        return static::$success_response;
    }

    public function showFile($path)
    {
        $storage = Storage::disk(config('juzaweb.filemanager.disk'));

        if (! $storage->exists($path)) {
            abort(404);
        }

        return response($storage->get($path))
            ->header('Content-Type', $storage->mimeType($path));
    }
}
