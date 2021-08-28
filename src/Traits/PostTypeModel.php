<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 6/8/2021
 * Time: 8:08 PM
 */

namespace Juzaweb\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Juzaweb\Blog\Models\Comment;
use Juzaweb\Core\Facades\PostType;
use Juzaweb\Core\Models\Taxonomy;

/**
 * @method \Illuminate\Database\Eloquent\Builder wherePublish()
 * @method \Illuminate\Database\Eloquent\Builder whereTaxonomy($taxonomy)
 * @method \Illuminate\Database\Eloquent\Builder whereTaxonomyIn($taxonomies)
 * */
trait PostTypeModel
{
    use ResourceModel, UseSlug, UseThumbnail, UseChangeBy;

    public function taxonomies()
    {
        return $this->belongsToMany(Taxonomy::class, 'term_taxonomies', 'term_id', 'taxonomy_id')
            ->withPivot(['term_type'])
            ->wherePivot('term_type', '=', $this->getPostType('key'));
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'object_id', 'id')->where('object_type', '=', $this->getPostType('key'));
    }

    public function syncTaxonomies(array $attributes)
    {
        $postType = $this->getPostType();
        $taxonomies = PostType::getTaxonomies($postType);
        foreach ($taxonomies as $taxonomy) {
            if (!Arr::has($attributes, $taxonomy->get('taxonomy'))) {
                continue;
            }

            $this->syncTaxonomy($taxonomy->get('taxonomy'), $attributes, $postType);
        }
    }

    public function syncTaxonomy(string $taxonomy, array $attributes, string $postType = null)
    {
        if (!Arr::has($attributes, $taxonomy)) {
            return;
        }

        $postType = $postType ?? $this->getPostType('key');
        $data = Arr::get($attributes, $taxonomy, []);
        $detachIds = $this->taxonomies()
            ->where('taxonomy', '=', $taxonomy)
            ->whereNotIn('id', $data)
            ->pluck('id')
            ->toArray();

        $this->taxonomies()->detach($detachIds);
        $this->taxonomies()
            ->syncWithoutDetaching(combine_pivot($data, [
                'term_type' => $postType
            ]), ['term_type' => $postType]);
    }

    public function getStatuses()
    {
        return [
            'draft' => trans('juzaweb::app.draft'),
            'publish' => trans('juzaweb::app.publish'),
            'private' => trans('juzaweb::app.private')
        ];
    }

    public function getPostType($key = null)
    {
        $postType = PostType::getPostTypes()
            ->where('model', static::class)
            ->first();

        if (empty($key)) {
            return $postType;
        }

        return $postType->get($key);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    public function scopeWherePublish($builder)
    {
        $builder->where('status', '=', 'publish');
        return $builder;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int $taxonomy
     *
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    public function scopeWhereTaxonomy($builder, $taxonomy)
    {
        $builder->whereHas('taxonomies', function (Builder $q) use ($taxonomy) {
            $q->where($q->getModel()->getTable() . '.id', $taxonomy);
        });
        return $builder;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $taxonomies
     *
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    public function scopeWhereTaxonomyIn($builder, $taxonomies)
    {
        $builder->whereHas('taxonomies', function (Builder $q) use ($taxonomies) {
            $q->whereIn($q->getModel()->getTable() . '.id', $taxonomies);
        });
        return $builder;
    }

    public function getPermalink($key = null)
    {
        $permalink = apply_filters('juzaweb.permalinks', []);
        $permalink = Arr::get($permalink, $this->getPostType('key'));

        if (empty($permalink)) {
            return false;
        }

        if (empty($key)) {
            return $permalink;
        }

        return $permalink->get($key);
    }

    public function getLink()
    {
        if ($this->getTable() == 'pages') {
            return url()->to($this->slug . '/');
        }

        $permalink = $this->getPermalink('base');
        if (empty($permalink)) {
            return false;
        }

        return url()->to($permalink . '/' . $this->slug . '/');
    }
}
