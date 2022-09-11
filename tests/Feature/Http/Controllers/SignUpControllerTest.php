<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    /** @test */
    function 会員登録画面が表示される()
    {
        $this->get('signup')
             ->assertOk();
    }
}
