<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use App\User;
use App\Reservation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
 
    /** @test */
    public function a_book_can_be_checked_out()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    /** @test */
    public function a_book_can_be_returned(){
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkout($user);

        $book->checkin($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    /** @test */
    //If checkin without check out, throw an exception
    public function if_checkin_without_checkout_then_throw_exception(){
        $this->expectException(\Exception::class);

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkin($user);

    }

    //A user can check out a book twice
}
