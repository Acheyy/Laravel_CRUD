<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin2',
            'email' => 'admin2@email.com',
            'password' => \Illuminate\Support\Facades\Hash::make('parola'),
            'type' => User::TYPE_ADMIN
        ]);
    }
}
