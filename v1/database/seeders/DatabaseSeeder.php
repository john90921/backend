<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()
            ->workerRole()
            ->hasProfile()
            ->hasPosts(8)
            ->hasLikes(5)
            ->hasComments(4)
            ->hasWorker()
            ->hasReplies(5)
            ->hasPlants(3)
            ->count(10)
            ->create();
        User::factory()
            ->managerRole()
            ->hasProfile()
            ->hasPosts(8)
            ->hasComments(4)
            ->hasLikes(5)
            ->hasReplies(5)
            ->hasPlants(3)
            ->count(10)
            ->create();
    }
}
