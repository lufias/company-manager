<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an admin user
        User::factory()->admin()->create();

        // Get the first admin user
        $admin = User::where('is_admin', true)->first();

        // Create 5 companies, all created by the first admin user
        Company::factory()->count(5)->create([
            'created_by' => $admin->id,
        ]);
    }
}
