<?php

namespace App\Http\Controllers;

use App\Book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Book $book){
        $book->checkout(Auth::user());
    }
}
