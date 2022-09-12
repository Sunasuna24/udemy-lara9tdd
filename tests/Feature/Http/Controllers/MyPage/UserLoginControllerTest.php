<?php

namespace Tests\Feature\Http\Controllers\MyPage;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserLoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function ログイン画面を開くことができる()
    {
        $this->get('mypage/login')->assertOk();
    }

    /** @test */
    function 不備があるとログインできない()
    {
        $url = route('login');

        $this->from($url)->post($url, [])->assertRedirect($url);

        $this->post($url, ['email' => ''])->assertInvalid(['email' => '必ず指定']);
        $this->post($url, ['email' => 'aa@bb@cc'])->assertInvalid(['email' => '有効なメールアドレス']);
        $this->post($url, ['email' => 'aa@ああ.いい'])->assertInvalid(['email' => '有効なメールアドレス']);
        $this->post($url, ['password' => ''])->assertInvalid(['password' => '必ず指定']);
    }

    /** @test */
    function ログインできる()
    {
        $url = route('login');

        $user = User::factory()->create([
            'email' => 'user1@test.com',
            'password' => Hash::make('password')
        ]);

        $this->post($url, [
            'email' => 'user1@test.com',
            'password' => 'password'
        ])->assertRedirect('/mypage/posts');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    function 不備のある認証情報で、適切なメッセージが表示される()
    {
        $url = route('login');

        $user = User::factory()->create([
            'email' => 'user1@test.com',
            'password' => Hash::make('password')
        ]);

        $this->from($url)->post($url, [
            'email' => 'user1@test.com',
            'password' => 'notCorrectPassword'
        ])->assertRedirect($url);

        $this->get($url)
            ->assertOk()
            ->assertSee('メールアドレスかパスワードが間違っています。');
    }

    /** @test */
    function 認証エラーなのでvalidationExceptionの例外が発生する()
    {
        $login_url = route('login');

        $this->withoutExceptionHandling();
        // $this->expectException(ValidationException::class);

        try {
            $this->post($login_url, [])->assertRedirect();
            $this->fail('例外が発生しませんでした'); 
        } catch (ValidationException $e) {
            $this->assertSame('emailは必ず指定してください。', $e->errors()['email'][0]);
        }
    }

    /** @test */
    function 認証OKなのでvalidationExceptionが発生しない()
    {
        //
    }
}
