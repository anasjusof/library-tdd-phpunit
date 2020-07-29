<?php

namespace Tests\Feature;

use App\User;
use App\Reservation;
use App\Book;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_checked_out_by_a_signed_in_user()
    {
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

    /** @test */
    public function only_signed_in_user_can_checkout_a_book(){
        
        //$this->withoutExceptionHandling();

        $book = factory(Book::class)->create();

        #Post book_id to controller without having user logged in[ no Auth::user ]
        #Upon not logged in, will be redirect back to login route
        $this->/*actingAs($user)
            ->*/post('checkout/' . $book->id)
            ->assertRedirect('/login');

        #Reservation should be 0 as there's no reservation happening
        $this->assertCount(0, Reservation::all());
    }

    /** @test */
    public function only_real_books_can_be_checked_out(){

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create(); //Method 1

        #Check out not existed book will return error 404 @ Page not found
        $this->actingAs($user)
        ->post('checkout/123')
        ->assertStatus(404);

        #Reservation should be 0 as there's no reservation happening
        $this->assertCount(0, Reservation::all());

    }

    /** @test */
    public function a_book_can_be_checked_in_by_a_signed_in_user()
    {
        $this->withoutExceptionHandling();

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create(); //Method 1

        $this->actingAs($user)
        ->post('checkout/' . $book->id);

        $this->actingAs($user)
        ->post('checkin/' . $book->id);

        // $this->actingAs(factory(User::class)->create()) //Method 2
        //     ->post('checkout/' . $book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    /** @test */
    public function only_signed_in_user_can_checkin_a_book(){
        
        //$this->withoutExceptionHandling();

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        #First the user need to be sign in to checkout the books
        $this->actingAs($user)
            ->post('checkout/' . $book->id);

        #Check if reservation is performed
        $this->assertCount(1, Reservation::all());

        #Logged out the user
        Auth::logout();

        #Post book_id to controller without having user logged in[ no Auth::user ]
        #Upon not logged in, will be redirect back to login route
        $this->/*actingAs($user)
            ->*/post('checkin/' . $book->id)
            ->assertRedirect('/login');

        #Reservation checked_in_at should be null since we are unable to check in the book without sign in first
        $this->assertNull(Reservation::first()->checked_in_at);
    }

    /** @test */
    public function a_404_is_thrown_if_a_book_is_not_checked_out_first_upon_checked_in(){

        //$this->withoutExceptionHandling();

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        #User checked in the book without check out first and return 404 error page
        $this->actingAs($user)
            ->post('checkin/' . $book->id)
            ->assertStatus(404);

        $this->assertCount(0, Reservation::all());

    }
}
