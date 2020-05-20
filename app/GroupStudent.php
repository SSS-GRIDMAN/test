<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupStudent extends Pivot
{
    public $timestamps = false;
    protected $guarded = [];
    protected $primaryKey = ['group_id', 'student_id'];
}
