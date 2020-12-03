<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User([
            'name' => 'Admin',
            'email' => 'admin@acs-szak.test',
            'email_verified_at' => Date::now(),
            'password' => Hash::make('password')
        ]);
        $admin->save();
        User::factory()->count(10)->create();
    }
}
