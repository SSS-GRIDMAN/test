<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'user_id';

    protected static function boot(){
        parent::boot();

        static::deleted(function ($teacher){
            Group::where('teacher_id', $teacher->user_id)->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groups(){
        return $this->hasMany(Group::class, 'teacher_id', 'user_id')->orderBy('group_name', 'desc');
    }

}
