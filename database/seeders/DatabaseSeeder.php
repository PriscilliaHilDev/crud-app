<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Image;
use App\Models\Category;
use App\Models\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // générer 10 faux utilisateurs
        $users = User::factory(10)->create();

        // Create Categories
        $categories = Category::factory(5)->create();

        // Create Posts and link to Users
        $posts = Post::factory(20)->create();

        // // Create Images and link to Posts (One-to-One) je donne a chaque fausses image l'id d'un post
        foreach ($posts as $post) {
            Image::factory()->create(['post_id' => $post->id]);
        }

        // Attach Categories to Posts (Many-to-Many)
        foreach ($posts as $post) {
            $post->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        }

        // Create Comments and link to Users and Posts pour chaque post insere 3 commentaire de 3 user differents pour ce meme poste
        foreach ($posts as $post) {
            Comment::factory(3)->create(['post_id' => $post->id, 'user_id' => $users->random()->id]);
        }

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            // UserRoleSeeder::class
        ]);
       
    }
}
