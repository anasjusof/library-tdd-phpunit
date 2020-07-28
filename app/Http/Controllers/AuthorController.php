<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;

class AuthorController extends Controller
{
    public function store(){

        //dd('hello');

        $author = Author::create([
            'name'  => 'test',
            'dob'   => '2020-07-22 15:29:08'
        ]);

        
    }
}
