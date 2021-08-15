<?php

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
