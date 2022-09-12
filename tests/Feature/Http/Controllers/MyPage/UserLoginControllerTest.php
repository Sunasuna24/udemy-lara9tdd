<?php

namespace Tests\Feature\Http\Controllers\MyPage;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
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
}