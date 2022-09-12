<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function 会員登録画面が表示される()
    {
        $this->get('signup')
             ->assertOk();
    }

    /** @test */
    function 会員登録できる()
    {
        // データ検証
        $validData = [
            'name' => 'ユーザー太郎',
            'email' => 'test1@email.com',
            'password' => 'password'
        ];

        // $validData = User::factory()->raw();
        $validData = User::factory()->validData();

        $this->post('signup', $validData)
              ->assertOk();

        // データが保存されているかを検証する
        unset($validData['password']);  // ハッシュ化されて検証に使えないから除く
        $this->assertDatabaseHas('users', $validData);

        $user = User::firstWhere($validData);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    /** @test */
    function 不正なデータでユーザー登録できない()
    {
        $url = 'signup';

        $this->post($url, ['name' => ''])
            ->assertInvalid(['name' => '指定']);
        $this->post($url, ['name' => str_repeat('あ', 21)])
            ->assertInvalid(['name' => '20文字以下']);
        $this->post($url, ['name' => str_repeat('あ', 20)])
            ->assertValid('name');
        $this->post($url, ['email' => ''])
            ->assertInvalid(['email' => '指定']);
        $this->post($url, ['email' => 'aa@bb@cc'])
            ->assertInvalid(['email' => '有効なメールアドレス']);
    }
}
