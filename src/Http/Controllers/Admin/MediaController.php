<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\FileManager\Enums\MediaType;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\Requests\Media\AddFolderRequest;
use Juzaweb\Modules\Core\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends AdminController
{
    public function index(Request $request, string $websiteId, ?string $folderId = null)
    {
        if ($folderId) {
            /** @var Media $folder */
            $folder = Media::with(['parents'])->findOrFail($folderId);

            Breadcrumb::add(__('admin::translation.media'), route('admin.media.index', $websiteId));

            $parent = $folder->parents;

            while ($parent) {
                Breadcrumb::add(
                    $parent->name,
                    route('admin.media.folder', [$websiteId, $parent->id])
                );

                $parent = $parent->parents;
            }

            Breadcrumb::add($folder->name);
        } else {
            Breadcrumb::add(__('admin::translation.media'));
        }

        $mediaFiles = Media::query()
            ->when(
                $folderId,
                function ($query) use ($folderId) {
                    $query->where('parent_id', $folderId);
                },
                function ($query) {
                    $query->whereRoot();
                }
            )
            //->searchAndFilter($request->all())
            ->orderBy('type', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate(36);
        $maxSize = config("media.disks.public.max_size");
        $mimeTypes = config("media.disks.public.mime_types");

        return view(
            'admin::admin.media.index',
            compact('mediaFiles', 'maxSize', 'mimeTypes', 'folderId')
        );
    }

    public function update(Request $request, string $websiteId, string $id)
    {
        $request->validate(['name' => 'required|string|max:250']);

        $model = Media::findOrFail($id);

        $model->update(['name' => $request->input('name')]);

        return $this->success([
            'message' => __('admin::translation.updated_media_successfully'),
        ]);
    }

    public function addFolder(AddFolderRequest $request, string $websiteId)
    {
        Media::create(
            [
                'name' => $request->input('name'),
                'type' => MediaType::DIRECTORY,
                'parent_id' => $request->input('folder_id'),
            ]
        );

        return $this->success([
            'message' => __('admin::translation.created_folder_successfully'),
        ]);
    }

    public function destroy(string $websiteId, string $id)
    {
        $model = Media::findOrFail($id);

        $model->delete();

        return $this->success([
            'message' => __('admin::translation.deleted_media_successfully'),
        ]);
    }

    public function download(string $websiteId, string $disk, string $id): StreamedResponse
    {
        $model = Media::findOrFail($id);
        $storage = Storage::disk($disk);
        if (! $storage->exists($model->path)) {
            abort(404, 'File not exists.');
        }

        return $storage->download($model->path);
    }

    public function loadMore(Request $request, string $websiteId, ?string $folderId = null)
    {
        $type = $request->get('type');

        $mediaFiles = Media::query()
            ->when(
                $folderId,
                function ($query) use ($folderId) {
                    $query->where('parent_id', $folderId);
                },
                function ($query) {
                    $query->whereRoot();
                }
            )
            ->when($type, function ($q) use ($type) {
                $q->where(function ($query) use ($type) {
                    $query->where('type', MediaType::DIRECTORY)
                        ->orWhere(function ($q) use ($type) {
                            $q->where('type', MediaType::FILE)
                                ->whereIn('mime_type', config("media.types.{$type}", []));
                        });
                });
            })
            //->searchAndFilter($request->all())
            ->orderBy('type', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate(36);

        $html = '';
        foreach ($mediaFiles as $item) {
            $html .= view('admin::admin.media.components.item', ['item' => $item, 'websiteId' => $websiteId])->render();
        }

        return response()->json([
            'html' => $html,
            'current_page' => $mediaFiles->currentPage(),
            'last_page' => $mediaFiles->lastPage(),
            'has_more' => $mediaFiles->hasMorePages(),
        ]);
    }
}
