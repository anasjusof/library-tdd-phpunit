<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Author;
use Carbon\Carbon;  

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_author_can_be_created()
    {
        $this->post('/author', [
            'name'  => 'AuthorName',
            'dob'   => '2020-07-22'
        ]);

        $author = Author::first();

        $this->assertCount(1, Author::all());
        $this->assertInstanceOf(Carbon::class, $author->dob);
        $this->assertEquals('2020-22-07', $author->dob->format('Y-d-m'));
    }
}
