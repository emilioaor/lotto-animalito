<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->email = 'emilioaor@gmail.com';
        $user->name = 'Emilio Ochoa';
        $user->identity_card = '21029522';
        $user->password = bcrypt('123456');
        $user->bank_id = 1;
        $user->number_account = '11111111111111111111';
        $user->level = User::LEVEL_ADMIN;
        $user->save();
    }
}
