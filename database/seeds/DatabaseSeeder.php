<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BankSeeder::class);
        $this->call(SortSeeder::class);
        $this->call(AnimalSeeder::class);
        $this->call(UserSeeder::class);
    }
}
