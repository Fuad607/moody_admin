<?php

use Illuminate\Database\Seeder;

class UserdetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Userdetail::class,30)->create();
    }
}
