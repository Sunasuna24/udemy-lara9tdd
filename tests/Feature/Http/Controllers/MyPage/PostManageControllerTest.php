<?php

namespace Tests\Feature\Http\Controllers\MyPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostManageControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function 認証している場合に限りマイページを開ける()
    {
        $this->get('/mypage/posts')->assertRedirect(route('login'));
    }
}
