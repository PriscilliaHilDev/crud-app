<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Image;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer les rôles et permissions d'abord
        $this->call([
            RoleSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // Créer le premier utilisateur admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User', // Nomme ton utilisateur admin
                'password' => bcrypt('adminadmin'), // Hash le mot de passe
            ]
        );

        // Récupérer le rôle admin
        $adminRole = Role::where('name', 'admin')->first();
        
        // Assigner le rôle admin à l'utilisateur
        if ($adminUser && $adminRole) {
            $adminUser->assignRole($adminRole);
        }

        // Assigner toutes les permissions à l'utilisateur admin
        if ($adminUser) {
            $adminUser->givePermissionTo(Permission::all());
        }

        // Générer 10 faux utilisateurs
        $users = User::factory(10)->create();

        // Créer des catégories
        $categories = Category::factory(5)->create();

        // Créer des posts et les lier aux utilisateurs
        $posts = Post::factory(20)->create();

        // Créer des images et les lier aux posts (One-to-One)
        foreach ($posts as $post) {
            Image::factory()->create(['post_id' => $post->id]);
        }

        // Attacher des catégories aux posts (Many-to-Many)
        foreach ($posts as $post) {
            $post->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        }

        // Créer des commentaires et les lier aux utilisateurs et posts
        foreach ($posts as $post) {
            Comment::factory(3)->create(['post_id' => $post->id, 'user_id' => $users->random()->id]);
        }
    }
}
