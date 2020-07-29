<?php

namespace Tests\Feature;

use App\User;
use App\Reservation;
use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_checked_out_by_a_signed_in_user()
    {
        //$this->withoutExceptionHandling();

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create(); //Method 1

        $this->actingAs($user)
        ->post('checkout/' . $book->id);

        // $this->actingAs(factory(User::class)->create()) //Method 2
        //     ->post('checkout/' . $book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    public function only_signed_in_user_can_checkout_a_book(){
        
        $this->withoutExceptionHandling();

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create(); //Method 1

        $this->actingAs($user)
        ->post('checkout/' . $book->id);

        // $this->actingAs(factory(User::class)->create()) //Method 2
        //     ->post('checkout/' . $book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }
}
