<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable =['prev_id', 'file_name', 'file_path'];

    protected static function boot(){
        parent::boot();

        static::deleted(function ($file){
            $relativePath = str_ireplace(storage_path('app\\'), '', $file->file_path);
            Storage::delete($relativePath);
        });
    }

    public function prev_file()
    {
        return $this->hasOne(File::class, 'prev_id');
    }
}
