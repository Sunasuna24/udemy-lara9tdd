<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostListControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function TOPページでブログ一覧が表示される()
    {
        // $post1 = Post::factory()->create();
        // $post2 = Post::factory()->create();

        // $this->get('/')
        //       ->assertOk()
        //       ->assertSee($post1->title)
        //       ->assertSee($post2->title);

        $post1 = Post::factory()->create(['title' => "ブログ1のタイトル"]);
        $post2 = Post::factory()->create(['title' => "ブログ2のタイトル"]);

        $this->get('/')
              ->assertOk()
              ->assertSee("ブログ1のタイトル")
              ->assertSee("ブログ2のタイトル");
    }
}
