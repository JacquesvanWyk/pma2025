<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $languages = [
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Afrikaans', 'code' => 'af'],
            ['name' => 'Zulu', 'code' => 'zu'],
            ['name' => 'Xhosa', 'code' => 'xh'],
            ['name' => 'Sotho', 'code' => 'st'],
            ['name' => 'Tswana', 'code' => 'tn'],
            ['name' => 'Portuguese', 'code' => 'pt'],
            ['name' => 'French', 'code' => 'fr'],
        ];

        $language = fake()->randomElement($languages);

        return [
            'name' => $language['name'],
            'code' => $language['code'],
        ];
    }
}
