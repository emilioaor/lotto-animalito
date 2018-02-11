<?php

use Illuminate\Database\Seeder;
use App\Sort;
use App\DailySort;

class SortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sort = new Sort();
        $sort->name = 'Lotto Activo';
        $sort->pay_per_100 = 3000;
        $sort->save();

        $dailySort = new DailySort();
        $dailySort->time = '2:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '3:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '4:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();
    }
}
