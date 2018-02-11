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
        $dailySort->time = '9:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '10:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '11:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '12:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '13:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '15:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '16:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '17:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '18:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();

        $dailySort = new DailySort();
        $dailySort->time = '19:00:00';
        $dailySort->sort_id = $sort->id;
        $dailySort->save();
    }
}
