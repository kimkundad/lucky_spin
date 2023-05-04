<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wheelsetting')->insert([
            [
                'value' => 0,
                'text' => 'เสียใจด้วยนะ',
                'message' => 'ขอบคุณที่ร่วมสนุก ไว้ลองใหม่นะจ้ะ!!',
                'percent' => 40,
                'minDegree' => 31,
                'maxDegree' => 90
            ],
            [
                'value' => 5,
                'text' => '5 เครดิต',
                'message' => 'คุณได้รับ 5 เครดิต',
                'percent' => 20,
                'minDegree' => 0,
                'maxDegree' => 30
            ],
            [
                'value' => 10,
                'text' => '10 เครดิต',
                'message' => 'คุณได้รับ 10 เครดิต',
                'percent' => 30,
                'minDegree' => 271,
                'maxDegree' => 330
            ],
            [
                'value' => 25,
                'text' => '25 เครดิต',
                'message' => 'คุณได้รับ 25 เครดิต',
                'percent' => 0,
                'minDegree' => 211,
                'maxDegree' => 270
            ],
            [
                'value' => 50,
                'text' => '50 เครดิต',
                'message' => 'คุณได้รับ 50 เครดิต',
                'percent' => 0,
                'minDegree' => 151,
                'maxDegree' => 210
            ],
            [
                'value' => 100,
                'text' => '100 เครดิต',
                'message' => 'คุณได้รับ 100 เครดิต',
                'percent' => 0,
                'minDegree' => 91,
                'maxDegree' => 150
            ],
            [
                'value' => 5,
                'text' => '5 เครดิต',
                'message' => 'คุณได้รับ 5 เครดิต',
                'percent' => 40,
                'minDegree' => 331,
                'maxDegree' => 360
            ],
        ]);
        DB::table('settings')->insert([
            [
            'nme_website' => 'xxx',
            'facebook' => 'xxx',
            'facebook_url' => 'xxx',
            'facebook' => 'xxx',
            'phone' => '0958467417',
            'first_day' => 10,
            'mid_day' => 30,
            'last_day' => 50,
            'coint_turn' => 5,
            'email' => 'xxx@gmail.com'
            ]
        ]);
       
    }
}
