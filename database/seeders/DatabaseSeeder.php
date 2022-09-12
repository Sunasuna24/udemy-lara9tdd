<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        [$first] = User::factory(15)->create()->each(function ($user) {
            Post::factory(random_int(2,5))->randomStatus()->create(['user_id' => $user->id])->each(function ($post) {
                Comment::factory(random_int(1,5))->create(['post_id' => $post->id]);
            });
        });

        $first->update([
            'name' => 'テスト太郎',
            'email' => 'user1@email.com',
            'password' => Hash::make('password')
        ]);
    }
}
