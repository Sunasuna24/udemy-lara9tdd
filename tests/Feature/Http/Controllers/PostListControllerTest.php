<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Post;
use App\Models\User;
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

        $post1 = Post::factory()->hasComments(3)->create(['title' => "ブログ1のタイトル"]);
        $post2 = Post::factory()->hasComments(5)->create(['title' => "ブログ2のタイトル"]);
        Post::factory()->hasComments(1)->create();

        $this->get('/')
              ->assertOk()
              ->assertSee("ブログ1のタイトル")
              ->assertSee("ブログ2のタイトル")
              ->assertSee($post1->user->name)
              ->assertSee($post2->user->name)
              ->assertSee('5件のコメント')
              ->assertSee('3件のコメント')
              ->assertSeeInOrder(['5件のコメント', '3件のコメント', '1件のコメント']);
    }

    // /** @test */
    // function factoryの観察()
    // {
    //     $post = Post::factory()->create();
    //     dump($post->toArray());
    //     dump(User::get()->toArray());
    //     $this->assertTrue(true);
    // }
}
