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
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\DataTables\NotificationsDataTable;
use Juzaweb\Modules\Core\Http\Requests\ProfileUpdateRequest;

class ProfileController extends AdminController
{
    public function index(Request $request)
    {
        $user = $request->user();

        Breadcrumb::add(__('core::translation.profile'));

        return view(
            'core::admin.profile.index',
            compact('user')
        );
    }

    public function notification(Request $request, NotificationsDataTable $dataTable)
    {
        $user = $request->user();

        Breadcrumb::add(__('core::translation.profile'), route('admin.profile'));

        Breadcrumb::add(__('core::translation.notifications'));

        return $dataTable->render(
            'core::admin.profile.notification',
            compact('user')
        );
    }

    public function update(ProfileUpdateRequest $request)
    {
        DB::transaction(
            function () use ($request) {
                $user = $request->user();
                $user->fill($request->safe()->except(['password', 'password_confirmation']));

                if ($request->filled('password')) {
                    $user->password = bcrypt($request->input('password'));
                }

                $user->save();

                $user->logActivity()
                    ->performedOn($user)
                    ->event('change_profile')
                    ->log('Updated profile information' . ($user->wasChanged('password') ? ' and password' : ''));

                return $user;
            }
        );

        return $this->success(
            __('core::translation.profile_updated_successfully'),
        );
    }

    public function changeAvatar(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

        $user = $request->user();

        DB::transaction(fn() => $user->changeAvatar($request->file('avatar')));

        $user->loadMedia('avatar');

        return response()->json(
            [
                'status' => 'success',
                'file_path' => $user->getAvatarUrl(),
                'message' => __('core::translation.profile_updated_successfully'),
            ]
        );
    }
}
