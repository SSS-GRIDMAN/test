<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected static function boot(){
        parent::boot();

        static::created(function ($assignment) {
            foreach($assignment->group->students as $student){
                AssignmentStudent::create(['student_id' => $student->user_id,
                                            'assignment_id' => $assignment->id]);
            }
        });

        static::deleted(function ($assignment){
            AssignmentStudent::where('assignment_id', $assignment->id)->delete();
            File::find($assignment->file_id)->delete();
        });
    }


    public function file()
    {
        return $this->hasOne(File::class, 'file_id');
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function studentSubmission(){
        return $this->hasMany(AssignmentStudent::class, 'assignment_id', 'id')->orderBy('handed', 'asc');
    }

    public function getFullDetail(){
        return Assignment::with(['studentSubmission' => function($query){
            $query->orderBy('handed', 'asc');
        }])->findMany($this->id);
    }
}
