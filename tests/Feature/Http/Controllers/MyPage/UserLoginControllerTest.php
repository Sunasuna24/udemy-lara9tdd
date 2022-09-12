<?php

namespace Tests\Feature\Http\Controllers\MyPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function ログイン画面を開くことができる()
    {
        $this->get('mypage/login')->assertOk();
    }
}
