<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class localeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$data = [
    		[
    			'locale_slug' => 'en',
	            'locale_name' => 'English',
    		],
    		[
    			'locale_slug' => 'hi',
	            'locale_name' => 'Hindi',
    		],
    	];


        DB::table('custom_locales')->insert($data);
    }
}
