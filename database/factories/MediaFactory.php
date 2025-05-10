<?php

namespace Juzaweb\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Core\Models\Media;
use Juzaweb\Core\Models\Model;

/**
 * @extends Factory<Model>
 */
class MediaFactory extends Factory
{
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (!Storage::disk('public')->exists('/tests')) {
            Storage::disk('public')->makeDirectory('/tests');
        }

        $img = $this->faker->image(
            Storage::disk('public')->path('/tests'),
            200,
            200
        );

        $imageFile = new File($img);

        return [
            'name' => $imageFile->getBasename(),
            'path' => 'tests/' . $imageFile->getBasename(),
            'mime_type' => $imageFile->getMimeType(),
            'size' => $imageFile->getSize(),
            'type' => 'file',
            'extension' => $imageFile->getExtension(),
            'image_size' => '200x200',
        ];
    }
}
