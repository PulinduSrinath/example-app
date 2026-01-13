<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Ensure password is known
        ]);

        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Create Editor Role (if not exists)
        $editorRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'editor']);
        $editorRole->givePermissionTo(['view users', 'view roles']);

        // Create Editor User
        $editor = User::factory()->create([
            'name' => 'John Editor',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);
        $editor->assignRole('editor');
    }
}
