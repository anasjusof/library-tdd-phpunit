<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;

class AuthorController extends Controller
{
    public function store(){

        $author = Author::create($this->validateRequest());

    }

    public function validateRequest(){

        return request()->validate([
            'name' => 'required',
            'dob' => 'required'
        ]);
    }
}
