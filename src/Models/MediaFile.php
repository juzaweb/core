<?php

namespace Juzaweb\Core\Models;

use Illuminate\Support\Facades\Storage;

/**
 * Juzaweb\Core\Models\MediaFile
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $mime_type
 * @property string $path
 * @property string $extension
 * @property int $size
 * @property int|null $folder_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereUserId($value)
 * @mixin \Eloquent
 */
class MediaFile extends Model
{
    protected $table = 'media_files';
    protected $fillable = [
        'name',
        'path',
        'extension',
        'mime_type',
    ];
    
    public function delete()
    {
        Storage::disk('public')->delete($this->path);
        return parent::delete();
    }

    public function isImage()
    {

    }
}
