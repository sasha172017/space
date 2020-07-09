<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('user')->where('email','alex@mail.com')->first();
            DB::table('role')->insert([
                'name' => 'ADMIN',
            ]);
            $role = DB::table('role')->where('name', 'ADMIN')->first();
        DB::table('user_role')->insert([
            'user_id' => $user->id,
            'role_id' => $role->id
        ]);
    }
}
