<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    
    protected $guarded = ['id'];

    protected $dates = ['dob'];

    protected function setDobAttribute($dob){
        $this->attributes['dob'] = Carbon::parse($dob);
    }
}
