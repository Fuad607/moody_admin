<?php

use Illuminate\Database\Seeder;

class UserdataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Userdata::class,30)->create();
    }
}
