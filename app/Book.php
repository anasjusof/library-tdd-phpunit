<?php

namespace App;

use App\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $guarded = [];

    public function path(){
        return '/books/' .  $this->id . '-' . Str::slug($this->title);
    }

    # On passing author id which is currently name of the author, 
    # we are either grab the First or Create a new author 
    # and return as author id ( (Author::firstOrCreate)->id) to the book model and insert it as author_id
    public function setAuthorIdAttribute($author){
        $this->attributes['author_id'] = (Author::firstOrCreate([
            'name'  => $author,
        ]))->id;
    }

    public function checkout($user){
    
        $this->reservations()->create([

            'user_id'           => $user->id,
            'checked_out_at'    => now()
        ]);
    }

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }

    public function checkIn($user){
        $reservation = Reservation::where('user_id', $user->id)
                            ->whereNotNull('checked_out_at')
                            ->whereNull('checked_in_at')
                            ->first();

        if($reservation->is_null){
            return \Exception::class;
        }

        $reservation->update([
            'checked_in_at'   => now(),
        ]);
    }
}

