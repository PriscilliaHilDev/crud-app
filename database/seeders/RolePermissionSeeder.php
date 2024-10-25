<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Assurez-vous d'importer le modèle User

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions pour les articles
        $postPermissions = [
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'view reports for posts', // Permission pour voir les signalements de posts
        ];

        // Permissions pour les utilisateurs
        $userPermissions = [
            'create users',
            'edit users',
            'delete users',
            'view users',
            'assign roles',
        ];

        // Permissions pour les commentaires
        $commentPermissions = [
            'create comments',
            'edit comments',
            'delete comments',
            'view comments',
            'view reports for comments', // Permission pour voir les signalements de commentaires
        ];

        // Création des permissions
        foreach (array_merge($postPermissions, $userPermissions, $commentPermissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Rôle admin avec toutes les permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Rôle writer avec seulement les permissions de posts et de commentaires
        $writerRole = Role::firstOrCreate(['name' => 'writer']);
        $writerRole->givePermissionTo(array_merge($postPermissions, $commentPermissions));

        // Assignation des rôles et permissions à l'utilisateur avec l'ID 1
        $user = User::find(1); // Récupère l'utilisateur avec l'ID 1
        if ($user) {
            $user->assignRole($adminRole); // Assigne le rôle admin
            $user->givePermissionTo(Permission::all()); // Assigne toutes les permissions
        }
    }
}
