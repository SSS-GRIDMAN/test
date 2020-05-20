<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AssignmentStudent extends Pivot
{
    protected $fillable = ['student_id', 'assignment_id'];
    protected $primaryKey = ['assignment_id', 'student_id'];
}
