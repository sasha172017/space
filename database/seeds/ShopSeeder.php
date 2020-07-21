<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quod ea non occurrentia fingunt, vincunt Aristonem; Ut optime, secundum naturam affectum esse possit. Tu vero, inquam, ducas licet, si sequetur; Duo Reges: constructio interrete. Summae mihi videtur inscitiae.';
        $products = DB::table('product')->select('id')->get();
        $productsArray = [];
        foreach ($products as $product) {
            $productsArray[] = $product->id;
        }
        for ($i = 0; $i < 10; $i++) {
            DB::table('shop')->insert([
                'name' => 'Shop' . Str::random(5),
                'description' => $content,
                'status' => true,
                'rating' => 5,
                'link' => 'https://' . \Illuminate\Support\Str::random(20)
            ]);
        }
        $shops = DB::table('shop')->select('id')->get();
        $shopsArray = [];
        foreach ($shops as $shop) {
            $shopsArray[] = $shop->id;
        }
        for ($i = 0; $i < 15; $i++) {
            DB::table('product_shop')->insert([
                'product_id' => $productsArray[array_rand($productsArray)],
                'shop_id' => $shopsArray[array_rand($shopsArray)],
                'price' => floatval(rand(200,5000)),
                'link' => 'https//:' . Str::random(10),
            ]);
        }
    }
}
