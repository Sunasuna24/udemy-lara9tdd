<?php

namespace Tests\Feature\Http\Controllers;

use App\Actions\StrRandom;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Carbon;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    // use WithoutMiddleware; 

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

    /** @test */
    function ブログ一覧で非公開ステータスは表示しない()
    {
        $closed_post = Post::factory()->statusClosed()->create([
            'title' => "これは非公開のブログです"
        ]);
        $published_post = Post::factory()->create([
            'title' => "公開済みのブログ"
        ]);

        $this->get('/')
              ->assertDontSee("これは非公開のブログです")
              ->assertSee('公開済みのブログ');
    }

    // /** @test */
    // function factoryの観察()
    // {
    //     $post = Post::factory()->create();
    //     dump($post->toArray());
    //     dump(User::get()->toArray());
    //     $this->assertTrue(true);
    // }

    /** @test */
    function ブログの詳細画面を表示できる()
    {
        $post = Post::factory()->create();

        $this->get('posts/' . $post->id)
              ->assertOk()
              ->assertSee($post->title)
              ->assertSee($post->user->name);
    }

    /** @test */
    function 非公開のブログは詳細画面が表示されない()
    {
        $closed_post = Post::factory()->statusClosed()->create();

        $this->get(route('posts.show', $closed_post->id))
             ->assertForbidden();
    }

    /** @test */
    function クリスマス以外の日は「メリークリスマス！」と表示しない()
    {
        $post = Post::factory()->create();

        Carbon::setTestNow('2022-12-24');
        
        $this->get(route('posts.show', $post->id))
             ->assertOk()
             ->assertDontSee('メリークリスマス！');
    }

    /** @test */
    function クリスマスの日は「メリークリスマス」と表示する()
    {
        $post = Post::factory()->create();

        Carbon::setTestNow('2022-12-25');

        $this->get(route('posts.show', $post->id))
             ->assertSee('メリークリスマス！');

    }

    /** @test */
    function ブログの詳細ページでコメントが表示される()
    {
        $post = Post::factory()->create();
        Comment::factory()->createMany([
            ['created_at' => now()->subDays(2), 'name' => 'コメント太郎', 'post_id' => $post->id],
            ['created_at' => now()->subDays(3), 'name' => 'コメント二郎', 'post_id' => $post->id],
            ['created_at' => now()->subDays(1), 'name' => 'コメント三郎', 'post_id' => $post->id]
        ]);

        $this->get(route('posts.show', $post->id))
             ->assertOk()
             ->assertSeeInOrder(['コメント二郎', 'コメント太郎', 'コメント三郎']);
    }

    /** @test */
    function ブログの詳細画面をランダムな文字列が表示される()
    {
        // $this->instance(StrRandom::class, Mockery::mock(StrRandom::class, function (MockInterface $mock) {
        //     $mock->shouldReceive('get')->once()->with(10)->andReturn('Hello, world');
        // }));

        $mock = Mockery::mock(StrRandom::class);
        $mock->shouldReceive('get')->once()->with(10)->andReturn('Hello, world');
        $this->instance(StrRandom::class, $mock);

        $post = Post::factory()->create();

        $this->get('posts/' . $post->id)
              ->assertOk()
              ->assertSee('Hello, world');
    }
}
