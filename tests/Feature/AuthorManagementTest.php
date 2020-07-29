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
        $this->post('/author', $this->data());

        $author = Author::first();

        $this->assertCount(1, Author::all());
        $this->assertInstanceOf(Carbon::class, $author->dob);
        $this->assertEquals('2020-22-07', $author->dob->format('Y-d-m'));
    }

    /** @test */
    public function a_name_is_required(){

        $response = $this->post('/author', array_merge($this->data(), ['name' => "" ]));

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_dob_is_required(){

        $response = $this->post('/author', array_merge($this->data(), ['dob' => "" ]));

        $response->assertSessionHasErrors('dob');
    }

    public function data(){
        return [
            'name'  => 'AuthorName',
            'dob'   => '2020-07-22'
        ];
    }
}
