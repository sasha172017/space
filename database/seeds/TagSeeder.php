<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Str;
use \Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = DB::table('product')->select('id')->get();
        $productsArray = [];
        foreach ($products as $product) {
            $productsArray[] = $product->id;
        }
        for($i = 0; $i < 10; $i++){
            DB::table('tag')->insert([
                'product_id' => $productsArray[array_rand($productsArray)],
                'name' => Str::random(5),
                'value' => rand(1,2),
            ]);
        }
    }
}
