<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'user_id';

    protected static function boot(){
        parent::boot();

        static::deleted(function ($student){
            GroupStudent::where('student_id', $student->user_id)->delete();
            AssignmentStudent::where('student_id', $student->user_id)->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentSubmmisionWithCertainGroup($groupID){
        $groupAssignmentIDs = Group::find($groupID)->assignments()->get()->pluck('id');

        return $this->belongsToMany(Assignment::class, AssignmentStudent::class, 'student_id', 'assignment_id', 'user_id')
                                ->orderBy('created_at', 'desc')
                                ->withPivot(['file_id', 'handed', 'grade', 'comment', 'visibility'])
                                ->wherePivotIn('assignment_id', $groupAssignmentIDs)
                                ->get();
    }

    public function submissionStatus(){
        return $this->hasMany(AssignmentStudent::class, 'student_id', 'user_id')->orderBy('created_at', 'desc');
    }

    public function groups(){
        return $this->belongsToMany(Group::class, GroupStudent::class, 'student_id', 'group_id', 'user_id')->orderBy('group_name', 'desc');
    }
}
