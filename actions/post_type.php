<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/laravel-cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

use Juzaweb\Core\Facades\PostType;

add_action('juzaweb.add_menu_items', function () {
    $postTypes = PostType::getPostTypes()
        ->where('rewrite', true);
    $postTypes->push(PostType::getPostTypes('pages'));

    $content = '';

    foreach($postTypes as $key => $postType) :
        $content .= view('jw_theme::backend.items.menu_box', [
            'label' => $postType->get('label'),
            'key' => $key,
            'type' => 'post_type',
            'slot' => view('jw_theme::backend.items.post_type_box', [
                'key' => $key,
                'postType' => $postType
            ])->render()
        ])->render();
    endforeach;

    echo $content;
});

