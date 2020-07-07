<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 0; $i < 5; $i++) {
            DB::table('user')->insert([
                'name' => Str::random(5),
                'email' => Str::random(7) . '@mail.com',
                'status' => true,
                'token' => Str::random(60),
                'date_of_birth' => (new DateTime())->format('Y-m-d'),
                'password' => \Illuminate\Support\Facades\Hash::make('1235')
            ]);
        }

    }
}
