<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function userリレーションを返す()
    {
        $post = Post::factory()->create();
        $this->assertInstanceOf(User::class, $post->user);
    }

    /** @test */
    function commentsリレーションを返す()
    {
        $post = Post::factory()->create();
        $this->assertInstanceOf(Collection::class, $post->comments);
    }

    /** @test */
    function ブログの公開スコープ()
    {
        $closed_post = Post::factory()->statusClosed()->create();
        $published_post = Post::factory()->create();
        $published_posts = Post::onlyPublished()->get();

        $this->assertFalse($published_posts->contains($closed_post));
        $this->assertTrue($published_posts->contains($published_post));
    }
}
