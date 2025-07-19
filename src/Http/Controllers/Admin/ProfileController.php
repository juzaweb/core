<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\DataTables\NotificationsDataTable;
use Juzaweb\Themes\VideoSharing\Http\Requests\ProfileUpdateRequest;

class ProfileController extends AdminController
{
    public function index(Request $request)
    {
        $user = $request->user();

        Breadcrumb::add(__('Profile'));

        return view(
            'core::admin.profile.index',
            compact('user')
        );
    }

    public function notification(Request $request, NotificationsDataTable $dataTable)
    {
        $user = $request->user();

        Breadcrumb::add(__('Profile'), route('admin.profile'));

        Breadcrumb::add(__('Notifications'));

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

                return $user;
            }
        );

        return $this->success(
            __('core::translation.profile_updated_successfully'),
        );
    }
}
