<?php

namespace Tests\Feature;

use App\Author;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Book;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_book_can_be_added_to_library()
    {
        $response = $this->post('/books', $this->data());

        $book = Book::first();

        //$response->assertOk();
        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    /**  */
    public function a_title_is_required()
    {
        
        $response = $this->post('/books', [
            'title'     => '',
            'author'    => 'The Author'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_author_is_required(){
        $response = $this->post('/books', array_merge($this->data(), ['author_id' => '']));

        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function a_book_can_be_update(){

        $this->post('/books', $this->data());               #[Note 1] We are creating author id of The Author

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title'     => 'The Title',
            'author_id' => 'The Author Updated'             #[Note 1 Cont.] We update the author id to The Author 2 which doesnt exist in author, so we are creating a new one
        ]);
        

        $this->assertEquals('The Title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);   #[Note 1 Cont.] We are using 2 since the updated author is does not exist, so there'll be The Author + The Author 2 records in author table
        $response->assertRedirect($book->fresh()->path()); //Fresh is grabbing the latest book after patch
    }

    /** @test */
    public function a_book_can_be_deleted(){

        // $this->withoutExceptionHandling();

        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }

    /** @test */
    public function a_new_author_is_automatically_added(){

        //$this->withoutExceptionHandling();

        $this->post('/books', [
            'title'     => 'The Title',
            'author_id'    => 'The Author' #Send author title on creating book ( Check Book model)
            //'author'    => 'The Author'
        ]);

        $book = Book::first();
        $author = Author::first();
        
        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function data(){

        return  [
            'title'     => 'The Title',
            'author_id'    => 'The Author' #Send author title on creating book ( Check Book model)
            //'author'    => 'The Author'
        ];
    }
}
