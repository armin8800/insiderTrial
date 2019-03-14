<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TeamsTableSeeder::class);
        $this->call(TableSeeder::class);
        $this->call(AllGamesSeeder::class);
        // $this->call(UsersTableSeeder::class);
    }
}
