<?php

namespace Juzaweb\Modules\Core\FileManager\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\FileManager\Jobs\PerformConversions;
use Juzaweb\Modules\Core\Models\Media;

/**
 * @property array $mediaChannels
 * @property Collection $media
 * @method static Builder|static withMedia(string|array|null $channel = null)
 */
trait HasMedia
{
    protected array $mediaAttributes = [];

    public static function bootHasMedia(): void
    {
        static::created(
            function ($model) {
                $model->processMediaAttributes();
            }
        );

        static::updated(
            function ($model) {
                $model->processMediaAttributes();
            }
        );
    }

    /**
     * Returns a morph to many relationship with the Media model.
     *
     * @return MorphToMany
     */
    public function media(): MorphToMany
    {
        return $this->morphToMany(
            Media::class,
            'mediable',
            'mediable',
            'mediable_id',
            'media_id',
            'id',
            'id'
        )->withPivot('channel')->withTimestamps();
    }

    public function scopeWithMedia(Builder $builder, string|array|null $channel = null): Builder
    {
        if ($channel !== null && !is_array($channel)) {
            $channel = [$channel];
        }

        if ($channel === null) {
            return $builder->with(['media']);
        }

        return $builder->with(
            [
                'media' => fn($query) => $query->wherePivotIn('channel', $channel)
            ]
        );
    }

    public function scopeWithMediaOfColumns(Builder $builder, string $column): Builder
    {
        // if (!is_array($columns)) {
        //     $columns = [$columns];
        // }

        return $builder->with(
            [
                'media' => fn($query) => $query->whereIn(
                    'id',
                    [$this->{$column}]
                )
            ]
        );
    }

    public function loadMedia(string|array|null $channel = null): void
    {
        if ($channel !== null && !is_array($channel)) {
            $channel = [$channel];
        }

        $this->load(['media' => fn ($q) => $q->when($channel, fn ($q2) => $q2->where('channel', $channel))]);
    }

    /**
     * Attaches a media object to the current instance.
     *
     * @param  Media|int|string|Collection  $media  The media object to attach. It can be an instance
     * of the Media class, an integer representing the media ID, or a string representing the media ID.
     * @param  string  $channel  The channel to attach the media to. Defaults to 'default'.
     * @return static The current instance with the media attached.
     * @throws ModelNotFoundException|\Throwable If the media object is not found.
     */
    public function attachMedia(Media|int|string|array|Collection $media, string $channel = 'default', bool $detach = false): static
    {
        $mediaIds = $this->parseMediaIds($media);
        // $params = collect($mediaIds)->mapWithKeys(fn($id) => [$id => ['channel' => $channel]])->toArray();

        // throw_unless($params, ModelNotFoundException::class);

        // $this->media()->sync($params, $detach);

        $exists = $this->media()
            ->wherePivot('channel', $channel)
            ->pluck('id')
            ->toArray();
        $toAttach = array_diff($mediaIds, $exists);

        if ($detach) {
            $toDetach = array_diff($exists, $mediaIds);

            if (!empty($toDetach)) {
                $this->media()->detach($toDetach);
            }
        }

        if ($toAttach) {
            $attachParams = collect($toAttach)
                ->mapWithKeys(fn($id) => [$id => ['channel' => $channel]])
                ->toArray();
            $this->media()->attach($attachParams);
        }

        if ($conversions = $this->getConversions()) {
            if (!$media instanceof Collection) {
                $media = Media::findMany($mediaIds);
            }

            PerformConversions::dispatch($media, $conversions);
        }

        Media::flushQueryCache();

        return $this;
    }

    public function syncMedia(Media|int|string|array|Collection $media, string $channel = 'default')
    {
        return $this->attachMedia($media, $channel, true);
    }

    /**
     * Attach or update a media object to the current instance.
     *
     * @param  Media|int|string  $media  The media object to attach or update. It can be an instance of the Media class,
     *                               an integer representing the media ID, or a string representing the media ID.
     * @param  string  $channel  The channel to attach or update the media to. Defaults to 'default'.
     * @return static The current instance with the media attached or updated.
     * @throws \Throwable
     */
    public function attachOrUpdateMedia(Media|int|string|null $media, string $channel = 'default'): static
    {
        if ($old = $this->getFirstMedia($channel)) {
            $this->detachMedia($old);
        }

        if (! $media) {
            return $this;
        }

        return $this->attachMedia($media, $channel);
    }

    /**
     * Determine if there is any media in the specified group.
     *
     * @param  string  $channel
     * @return bool
     */
    public function hasMedia(string $channel = 'default'): bool
    {
        return $this->getMedia($channel)->isNotEmpty();
    }

    /**
     * Get all the media in the specified group.
     *
     * @param  string  $group
     * @return Collection<Media>
     */
    public function getMedia(string $channel = 'default'): Collection
    {
        if ($channel) {
            return $this->media->where('pivot.channel', $channel);
        }

        return $this->media;
    }

    public function setMedia(Media|string $media, string $channel = 'default'): static
    {
        $this->mediaAttributes[$channel] = $media;

        return $this;
    }

    /**
     * Get the first media item in the specified channel.
     *
     * @param  string  $channel
     * @return ?Media
     */
    public function getFirstMedia(string $channel = 'default'): ?Media
    {
        return $this->getMedia($channel)->first();
    }

    /**
     * Get the url of the first media item in the specified channel.
     *
     * @param  string  $channel
     * @param  string  $conversion
     * @return string
     */
    public function getFirstMediaUrl(string $channel = 'default', string|null $conversion = null): ?string
    {
        if (!$media = $this->getFirstMedia($channel)) {
            return null;
        }

        return $media->getUrl($conversion);
    }

    /**
     * Detach the specified media.
     *
     * @param  int|string|Media|null  $media
     * @return int|null
     */
    public function detachMedia(Media|int|string|null $media = null): ?int
    {
        $count = $this->media()->detach($this->parseMediaId($media));

        Media::flushQueryCache();

        return $count > 0 ? $count : null;
    }

    /**
     * Clear all the media in the specified channel.
     *
     * @return int
     */
    public function clearMedia(): int
    {
        return $this->media()->detach();
    }

    /**
     * Detach all the media in the specified channel.
     *
     * @param  string  $channel
     * @return void
     */
    public function clearMediaChannel(string $channel = 'default'): int
    {
        return $this->media()->wherePivot('channel', $channel)->detach();
    }

    public function getMediaChannels(): array
    {
        return $this->mediaChannels ?? ['default'];
    }

    /**
     * Retrieves the conversions for the media channels.
     *
     * This function collects the media channels and maps each conversion and key.
     * If the key is numeric, an empty array is returned.
     * If the conversion is a string, an array with the conversion is returned.
     * Otherwise, the conversion is returned as is.
     * The resulting array is filtered to remove any empty arrays and then converted to a flat array.
     *
     * @return array The conversions for the media channels.
     */
    public function getConversions(): array
    {
        return collect($this->getMediaChannels())->map(
            function ($conversion, $key) {
                if (is_numeric($key)) {
                    return [];
                }

                if (is_string($conversion)) {
                    return [$conversion];
                }

                return $conversion;
            }
        )->filter()->values()->flatten(1)->toArray();
    }

    /**
     * Retrieves the conversion response for the specified media channel.
     *
     * @param string $channel The name of the media channel. Defaults to 'default'.
     * @return array|null The conversion response, or null if no media is found.
     */
    public function getConversionResponse(string $channel = 'default'): ?array
    {
        return $this->getFirstMedia($channel)?->getConversionResponse();
    }

    public function hasMediaChannel(string $channel): bool
    {
        return in_array($channel, $this->mediaChannels);
    }

    /**
     * Process the media attributes of the given model.
     *
     * This function iterates over the media attributes of the model and attaches or updates each media object
     * using the `attachOrUpdateMedia` method. After processing all the media attributes, it clears the
     * `mediaAttributes` property of the model.
     *
     * @param mixed $model The model object to process the media attributes for.
     * @return void
     */
    protected function processMediaAttributes(): void
    {
        if ($this->mediaAttributes) {
            foreach ($this->mediaAttributes as $channel => $media) {
                $this->attachOrUpdateMedia($media, $channel);
            }

            $this->mediaAttributes = [];
        }
    }

    /**
     * Parse the media id's from the mixed input.
     *
     * @param  array|Collection|Media|string  $media
     * @return array
     */
    protected function parseMediaIds(Collection|array|Media|string $media): array
    {
        if ($media instanceof Collection) {
            return $media->modelKeys();
        }

        if ($media instanceof Media) {
            return [$media->getKey()];
        }

        if (is_string($media) && Str::isUuid($media)) {
            $media = [$media];
        }

        if (is_string($media)) {
            $media = [Media::findByPath($media)?->id];
        }

        if (is_array($media)) {
            $media = Media::findMany(
                collect($media)->map(fn($item) => $this->parseMediaId($item))->toArray()
            )->modelKeys();
        }

        return (array) $media;
    }

    /**
     * Parse the media id from the mixed input.
     *
     * @param  Media|string  $media The media object to parse, or its ID, or a path to the media file.
     * @return string                The ID of the media object.
     */
    protected function parseMediaId(Media|string $media): string
    {
        // If the input is a media object, return its ID.
        if ($media instanceof Media) {
            return $media->getKey();
        }

        if (is_string($media) && Str::isUuid($media)) {
            return Media::find($media, ['id'])?->id;
        }

        // If the input is a string, try to find the media object by its path and return its ID.
        if (is_string($media)) {
            return Media::findByPath($media)?->id ?? $media;
        }

        // If the input is numeric, return it as is.
        return $media;
    }
}
