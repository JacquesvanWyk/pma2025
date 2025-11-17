<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Afrikaans', 'code' => 'af'],
            ['name' => 'Zulu', 'code' => 'zu'],
            ['name' => 'Xhosa', 'code' => 'xh'],
            ['name' => 'Northern Sotho', 'code' => 'nso'],
            ['name' => 'Tswana', 'code' => 'tn'],
            ['name' => 'Southern Sotho', 'code' => 'st'],
            ['name' => 'Tsonga', 'code' => 'ts'],
            ['name' => 'Swati', 'code' => 'ss'],
            ['name' => 'Venda', 'code' => 've'],
            ['name' => 'Portuguese', 'code' => 'pt'],
        ];

        DB::table('languages')->insert($languages);
    }
}
