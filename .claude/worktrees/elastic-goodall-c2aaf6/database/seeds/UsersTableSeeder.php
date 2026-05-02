<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        $user = App\User::create([
            'first_name' => 'super',
            'last_name'  => 'admin',
            'email'  => 'Zuper_admin@optics.com',
            'password'  => bcrypt('1234567'),
        ]);

        $user->attachRole('super_admin');
    }
}
