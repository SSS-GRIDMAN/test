<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected static function boot(){
        parent::boot();

        static::deleted(function ($group){
            $group->assignments->delete();
            GroupStudent::where('group_id', $group->id)->delete();
        });
    }

    public function students(){
        return $this->belongsToMany(Student::class, GroupStudent::class, 'group_id', 'student_id', 'id', 'user_id');
    }

    public function teacher(){
        return $this->hasOne(Teacher::class, 'user_id', 'teacher_id');
    }

    public function assignments(){
        return $this->hasMany(Assignment::class, 'group_id', 'id')->orderBy('created_at', 'desc');
    }
}