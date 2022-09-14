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
        $this->post('/mypage/post/create', [])->assertRedirect($login_url);
        $this->get('mypage/post/edit/1')->assertRedirect($login_url);
        $this->post('mypage/post/edit/1')->assertRedirect($login_url);
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
        [$user1, $me, $user3] = User::factory(3)->create();
        $this->actingAs($me);

        $validData = [
            'title' => '私のブログのタイトル',
            'body' => '私のブログの本文'
        ];

        $response = $this->post('mypage/post/create', $validData);
        $post = Post::first();

        $response->assertRedirect('mypage/post/edit'.$post->id);
        $this->assertDatabaseHas('posts', array_merge($validData, [
            'user_id' => $me->id,
            'status' => false
        ]));
    }

    /** @test */
    function ブログ登録のときの入力チェック()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $url = 'mypage/post/create';
        $this->from($url)->post($url, [])->assertRedirect($url);

        $this->post($url, ['title' => []])->assertInvalid(['title' => '必ず指定']);
        $this->post($url, ['title' => str_repeat('a', 256)])->assertInvalid(['title' => '文字以下で指定']);
        $this->post($url, ['title' => str_repeat('a', 255)])->assertValid('title');
        $this->post($url, ['body' => ''])->assertInvalid(['body' => '必ず指定']);
    }

    /** @test */
    function 自分のブログの編集画面は開ける()
    {
        $post = Post::factory()->create();
        $this->actingAs($post->user);

        $this->get('mypage/post/edit/' . $post->id)->assertOk();
    }

    /** @test */
    function 他人のブログの編集画面は開けない()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('mypage/post/edit/' . $post->id)->assertForbidden();
    }

    /** @test */
    function 自分のブログは更新できる()
    {
        $post = Post::factory()->create();
        $this->actingAs($post->user);

        $validData = [
            'title' => '新タイトル',
            'body' => '新しい本文です。',
            'status' => '1'
        ];

        $this->post(route('mypage.post.edit', $post->id), $validData)->assertRedirect(route('mypage.post.edit', $post->id));
        $this->get(route('mypage.post.edit', $post->id))->assertSee('ブログを更新しました。');
        $this->assertDatabaseHas('posts', $validData);
        $this->assertCount(1, Post::all());
        $post->refresh();
        $this->assertSame($validData['title'], $post->title);
        $this->assertSame($validData['body'], $post->body);
    }

    /** @test */
    function 他人のブログは更新できない()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create([
            'title' => '元タイトル'
        ]);
        $validData = [
            'title' => '新タイトル',
            'body' => '新しい本文です。',
            'status' => '1'
        ];

        $this->post(route('mypage.post.edit', $post->id), $validData)->assertForbidden();
        $this->assertSame('元タイトル', $post->fresh()->title);
    }

    /** @test */
    function 他人のブログは削除できない()
    {
        //
    }
}
