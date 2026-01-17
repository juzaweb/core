<?php

namespace Juzaweb\Modules\Core\FileManager\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Juzaweb\Modules\Core\Models\Media;

class MediaExists implements Rule
{
    /**
     * @var string|null $disk The name of the disk
     */
    protected ?string $disk = null;

    /**
     * @var array $notFound Medias not found
     */
    protected array $notFound = [];

    /**
     * Specify the disk to use for the validation.
     *
     * @param  string  $disk
     * @return static
     */
    public function onDisk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! is_array($value)) {
            $value = [$value];
        }

        $medias = Media::query()
            ->when($this->disk, fn (Builder $query) => $query->where('disk', '=', $this->disk))
            ->findMany($value)
            ->get(['id']);

        $this->notFound = array_diff($value, $medias->pluck('id')->toArray());

        return !empty($this->notFound);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('core::translation.the_media_medias_does_not_exist', ['medias' => implode(',', $this->notFound)]);
    }
}
