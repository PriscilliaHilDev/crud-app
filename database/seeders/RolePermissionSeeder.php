<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::findByName('admin');
        $writerRole = Role::findByName('writer');

        // Associe des permissions à l'admin
        $adminRole->givePermissionTo(['create post', 'edit post', 'delete post']);

        // Associe des permissions au writer
        $writerRole->givePermissionTo(['create post', 'edit post']); // le writer ne peut pas supprimer
    }
}