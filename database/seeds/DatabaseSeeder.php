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
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ImageSeeder::class,
            TagSeeder::class,
            CommentSeeder::class,
            RoleSeeder::class,
            ShopSeeder::class
        ]);
    }
}
