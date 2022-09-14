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
        $login_url = route('login');
        $this->get('/mypage/posts')->assertRedirect($login_url);
        $this->get('/mypage/post/create')->assertRedirect($login_url);
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

    /** @test */
    function マイページでブログの新規登録画面を開ける()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('mypage/post/create')->assertOk();
    }

    /** @test */
    function 公開ステータスのブログを新規登録する()
    {
        $this->withoutExceptionHandling();

        [$user1, $me, $user3] = User::factory(3)->create();
        $this->actingAs($me);

        $validData = [
            'title' => '私のブログのタイトル',
            'body' => '私のブログの本文',
            'status' => 1
        ];

        $response = $this->post('mypage/post/create', $validData);
        $post = Post::first();

        $response->assertRedirect('mypage/post/edit'.$post->id);
        $this->assertDatabaseHas('posts', array_merge($validData, ['user_id' => $me->id]));
    }

    /** @test */
    function 非公開ステータスのブログを新規登録する()
    {
        //
    }

    /** @test */
    function ブログ登録のときの入力チェック()
    {
        //
    }
}
