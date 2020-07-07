<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Str;
use \Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quod ea non occurrentia fingunt, vincunt Aristonem; Ut optime, secundum naturam affectum esse possit. Tu vero, inquam, ducas licet, si sequetur; Duo Reges: constructio interrete. Summae mihi videtur inscitiae.';
        $categories = DB::table('category')->select('id')->get();
        $users = DB::table('user')->select('id')->get();
        $categoriesArray = [];
        foreach ($categories as $category) {
            $categoriesArray[] = $category->id;
        }
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = $user->id;
        }
        for($i = 0; $i < 10; $i++){
            DB::table('product')->insert([
                'category_id' => $categoriesArray[array_rand($categoriesArray)],
                'user_id' => $usersArray[array_rand($usersArray)],
                'name' => Str::random(5),
                'description' => $content,
                'link' => 'https://'.\Illuminate\Support\Str::random(20)
            ]);
        }
    }
}
