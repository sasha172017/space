<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;

class ImageSeeder extends Seeder
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
        $images = array_diff(scandir(__DIR__ . '/../../public/upload/seeder'), array('.', '..'));
        $j = 0;
        for ($i = 0; $i < 10; $i++) {
            $image = $images[array_rand($images)];
            $imageNewName = uniqid() . '-' . $image;
            DB::table('image')->insert([
                'product_id' => $productsArray[array_rand($productsArray)],
                'name' => $imageNewName,
                'sort' => $j
            ]);
            $j = $j + 10;
            copy(__DIR__ . '/../../public/upload/seeder/' . $image ,__DIR__ . '/../../storage/app/public/product/'.$imageNewName);
        }
    }
}
