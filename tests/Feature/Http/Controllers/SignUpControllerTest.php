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

        $this->post('signup', $validData)
              ->assertOk();

        // データが保存されているかを検証する
        unset($validData['password']);  // ハッシュ化されて検証に使えないから除く
        $this->assertDatabaseHas('users', $validData);

        $user = User::firstWhere($validData);
        $this->assertTrue(Hash::check('password', $user->password));
    }
}
