<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'first_name' => 'Abdessamad',
            'last_name' => 'EL FEDALI',
            'phone' => '0627018957',
            'avatar' => 'https://avatars.githubusercontent.com/u/56188780?v=4',
            'is_active' => true,
            'is_admin' => true,

            'name' => 'Abdessamad-FDL',
            'email' => 'me@restoly.ma',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);

        // call CountrySeeder
        $this->call(CountrySeeder::class);
    }
}
