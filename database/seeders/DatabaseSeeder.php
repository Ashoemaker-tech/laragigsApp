<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\listing;
use Illuminate\Database\Seeder;
use Database\Factories\ListingFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'test@test.com',
        ]);;

        Listing::factory(6)->create([
            'user_id' => $user->id
        ]);
    }
}
