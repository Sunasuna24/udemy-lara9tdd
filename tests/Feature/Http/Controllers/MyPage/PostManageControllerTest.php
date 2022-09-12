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
    function ゲストはブログを管理できない()
    {
        $this->get('/mypage/posts')->assertRedirect(route('login'));
    }

    /** @test */
    function 認証している場合とマイページTOPを開ける()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/mypage/posts')->assertOk();
    }
}
