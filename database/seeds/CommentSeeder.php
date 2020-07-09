<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;

class CommentSeeder extends Seeder
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
        $users = DB::table('user')->select('id')->get();
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = $user->id;
        }
        for ($i = 0; $i < 20; $i++) {
            DB::table('comment')->insert([
                'product_id' => $productsArray[array_rand($productsArray)],
                'text' => mb_substr($content, 0, rand(20, 70)),
                'user_id' => $usersArray[array_rand($usersArray)],
                'created_at' => (new \DateTime())->format('Y-m-d h:s')
            ]);
        }
    }
}
