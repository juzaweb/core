<?php

namespace Juzaweb\Core\Http\Controllers\Backend\Setting;

use Juzaweb\Core\Models\Config;
use Illuminate\Http\Request;
use Juzaweb\Core\Http\Controllers\Controller;

class CommentSettingController extends Controller
{
    public function index() {
        return view('juzaweb::backend.setting.comment.index', [
            'title' => trans('juzaweb::app.comment_setting')
        ]);
    }
    
    public function save(Request $request) {
        $this->validateRequest([
            'comment_able' => 'required|in:0,1',
            'comment_type' => 'required_if:comment_able,!=,0|string|max:300',
            'comments_per_page' => 'required|string|max:300',
            'comments_approval' => 'required|string|max:300',
        ], $request, [
            'comment_able' => trans('juzaweb::app.comment_able'),
            'comment_type' => trans('juzaweb::app.comment_type'),
            'comments_per_page' => trans('juzaweb::app.comments_per_page'),
            'comments_approval' => trans('juzaweb::app.comments_approval'),
        ]);
    
        $configs = $request->only([
            'comment_able',
            'comment_type',
            'comments_per_page',
            'comments_approval',
        ]);
        
        foreach ($configs as $key => $config) {
            Config::setConfig($key, $config);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => trans('juzaweb::app.saved_successfully'),
            'redirect' => route('admin.setting.comment'),
        ]);
    }
}
