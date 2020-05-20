<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'password', 'person_type', 'first_name', 'last_name', 'email', 'other_contact'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->first_name = null;
            $user->last_name = null;
            $user->email = null;
            $user->other_contact = null;
        });

        static::created(function ($user) {
            $user->typed_user()->create();
        });

        static::deleted(function ($user){
            switch ($user->person_type) {
                case 'type_student':
                    Student::find($user->id)->delete();
                    break;
                case 'type_teacher':
                    Teacher::find($user->id)->delete();
                    break;
                case 'type_admin':
                    Admin::find($user->id)->delete();
                    break;
            }
        });
    }

    public function typed_user()
    {
        $related = "App\Student";
        switch ($this->person_type)
        {
        case "type_student":
            break;
        case "type_teacher":
            $related = "App\Teacher";
            break;
        case "type_admin":
            $related = "App\Admin";
            break;
        }
        return $this->hasOne($related, 'user_id');
	}

	public function getFullNameAttribute()
	{
		$disp = "";

		if (!empty($this->first_name))
			$disp = $this->first_name;
		
		if (!empty($this->last_name))
			$disp = " ".$this->last_name;

		trim($disp);
		return empty($disp) ? "" : $disp;
	}

	public function setFullNameAttribute($value)
	{
		$arr = explode(" ", $value);
		if ($arr.count == 2)
		{
			$this->first_name = $arr[0];
			$this->last_name = $arr[1];
		}
		$this->save();
	}

	public function getDisplayNameAttribute()
	{
		$disp = "";

		if (!empty($this->first_name))
			$disp = $this->first_name;
		
		if (!empty($this->last_name))
			$disp = " ".$this->last_name;

		trim($disp);
		return empty($disp) ? $this->account : $disp;
	}
}
