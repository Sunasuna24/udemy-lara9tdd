<?php

namespace Tests\Feature\Http\Controllers\MyPage;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostManageControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function 認証している場合に限りマイページを開ける()
    {
        // 認証していないとき
        $this->get('/mypage/posts')->assertRedirect(route('login'));

        // 認証済みのとき
        $user = User::factory()->create();
        $this->actingAs($user)->get('/mypage/posts')->assertOk();
    }
}
