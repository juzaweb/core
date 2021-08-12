<?php

namespace Juzaweb\Core\Http\Controllers\Backend\Setting;

use Juzaweb\Core\Models\Config;
use Illuminate\Http\Request;
use Juzaweb\Core\Http\Controllers\Controller;

class SeoSettingController extends Controller
{
    public function index() {
        return view('juzaweb::backend.setting.seo.index');
    }
    
    public function save(Request $request) {
        $this->validateRequest([
            'author_name' => 'required|string|max:300',
            'movies_title' => 'required|string|max:300',
            'movies_keywords' => 'nullable|string|max:300',
            'movies_description' => 'nullable|string|max:300',
            'tv_series_title' => 'required|string|max:300',
            'tv_series_keywords' => 'nullable|string|max:300',
            'tv_series_description' => 'nullable|string|max:300',
            'blog_title' => 'required|string|max:300',
            'blog_keywords' => 'nullable|string|max:300',
            'blog_description' => 'nullable|string|max:300',
            'facebook' => 'nullable|string|max:300',
            'twitter' => 'nullable|string|max:300',
            'pinterest' => 'nullable|string|max:300',
            'youtube' => 'nullable|string|max:300',
            'title' => 'required|string|max:300',
            'description' => 'nullable|string|max:300',
            'keywords' => 'nullable|string|max:300',
        ], $request, [
            'author_name' => trans('juzaweb::app.author_name'),
            'movies_title' => trans('juzaweb::app.movies_title'),
            'movies_keywords' => trans('juzaweb::app.movies_keywords'),
            'movies_description' => trans('juzaweb::app.movies_description'),
            'tv_series_title' => trans('juzaweb::app.tv_series_title'),
            'tv_series_keywords' => trans('juzaweb::app.tv_series_keywords'),
            'tv_series_description' => trans('juzaweb::app.tv_series_description'),
            'blog_title' => trans('juzaweb::app.blog_title'),
            'blog_keywords' => trans('juzaweb::app.blog_keywords'),
            'blog_description' => trans('juzaweb::app.blog_description'),
            'facebook' => 'Facebook URL',
            'twitter' => 'Twitter URL',
            'pinterest' => 'Linkedin URL',
            'youtube' => 'Youtube URL',
            'title' => trans('juzaweb::app.home_title'),
            'description' => trans('juzaweb::app.home_description'),
            'keywords' => trans('juzaweb::app.keywords'),
        ]);
    
        $configs = $request->only([
            'title',
            'description',
            'keywords',
            'banner',
            'author_name',
            'movies_title',
            'movies_keywords',
            'movies_description',
            'movies_banner',
            'tv_series_title',
            'tv_series_keywords',
            'tv_series_description',
            'tv_series_banner',
            'blog_title',
            'blog_keywords',
            'blog_description',
            'blog_banner',
            'latest_movies_title',
            'latest_movies_keywords',
            'latest_movies_description',
            'latest_movies_banner',
            'facebook',
            'twitter',
            'pinterest',
            'youtube',
        ]);
    
        foreach ($configs as $key => $config) {
            Config::setConfig($key, $config);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => trans('juzaweb::app.saved_successfully'),
            'redirect' => route('admin.setting.seo'),
        ]);
    }
}
