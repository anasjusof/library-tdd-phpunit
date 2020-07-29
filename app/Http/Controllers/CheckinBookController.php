<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckinBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Book $book){

        #Try check-in the book
        try{
            $book->checkin(Auth::user());
        }
        #If failed, return reponse of 404 page
        catch(\Exception $e){
            return response([], 404);
        }
        
    }
}
