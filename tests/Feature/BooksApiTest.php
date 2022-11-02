<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    // public function test_the_application_returns_a_successful_response()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    use RefreshDatabase;

    /** @test */
    public function can_get_all_books()
    {
        $books = Book::factory(4)->create();
        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title,
            ])->assertJsonFragment([
                'title' => $books[1]->title,
            ]);
    }

    /** @test */
    public function can_get_a_single_book()
    {
        $book = Book::factory()->create();
        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    /** @test */
    public function can_create_a_book()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'A new book',
        ])->assertJsonFragment([
            'title' => 'A new book',
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'A new book',
        ]);
    }

    /** @test */
    public function can_update_a_book()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'A new title',
        ])->assertJsonFragment([
            'title' => 'A new title',
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'A new title',
        ]);
    }

    /** @test */
    public function can_delete_a_book()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}
