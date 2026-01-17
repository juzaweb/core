<?php

namespace Juzaweb\Modules\Core\FileManager\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\FileManager\Traits\HasMedia;

class Media implements CastsAttributes
{
    protected ?string $channel = null;

    public function __construct(string $channel = null)
    {
        $this->channel = $channel;
    }

    /**
     * Cast the given value.
     *
     * @param  Model|HasMedia  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        if (! method_exists($model, 'getFirstMedia')) {
            throw new \LogicException('The model must use HasMedia trait.');
        }

        return $model->getConversionResponse($this->channel ?? $key);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model|HasMedia  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        if (! method_exists($model, 'setMedia')) {
            throw new \LogicException('The model must use HasMedia trait.');
        }

        $channel = $this->channel ?? $key;

        if (! in_array($channel, $model->getMediaChannels())) {
            throw new \LogicException("The media channel {$channel} does not exist.");
        }

        return $model->setMedia($value, $channel);
    }
}
