<?php

namespace Tests\Feature\Http\Controllers\MyPage;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpParser\Node\Expr\AssignOp\Pow;
use Tests\TestCase;

class PostManageControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function ゲストはブログを管理できない()
    {
        $this->get('/mypage/posts')->assertRedirect(route('login'));
    }

    /** @test */
    function マイページ、ブログ一覧で自分のデータのみ表示される()
    {
        $user = User::factory()->create();
        $my_post = Post::factory()->create(['user_id' => $user->id]);
        $other_post = Post::factory()->create();

        $this->actingAs($user)->get('/mypage/posts')
            ->assertOk()
            ->assertDontSee($other_post->title)
            ->assertSee($my_post->title);
    }
}
