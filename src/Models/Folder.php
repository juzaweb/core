<?php

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Juzaweb\Core\Models\Folder
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int|null $folder_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Juzaweb\Core\Models\File[] $childs
 * @property-read int|null $childs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Juzaweb\Core\Models\Folder[] $folder_childs
 * @property-read int|null $folder_childs_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Folder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Folder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Folder query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Folder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Folder whereFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Folder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Folder whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Folder whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Folder whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Folder[] $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Juzaweb\Core\Models\File[] $files
 * @property-read int|null $files_count
 * @property-read Folder|null $parent
 */
class Folder extends Model
{
    protected $table = 'folders';
    protected $fillable = [
        'name',
        'folder_id'
    ];
    
    public function files()
    {
        return $this->hasMany('Juzaweb\Core\Models\File', 'folder_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'folder_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('Juzaweb\Core\Models\Folder', 'folder_id', 'id');
    }
    
    public function deleteFolder()
    {
        foreach ($this->folder_childs as $folder_child) {
            $folder_child->deleteFolder();
        }
        
        foreach ($this->childs as $child) {
            $child->deleteFile();
        }
        
        return $this->delete();
    }
    
    public static function folderExists($name, $parentId)
    {
        return self::where('name', '=', $name)
            ->where('folder_id', '=', $parentId)
            ->exists();
    }
}
